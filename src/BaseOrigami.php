<?php

namespace App\Service\Origami;

use App\Enum\Origami\EntityEnum;
use App\Service\Origami\Dto\CreateEntityResponseDto;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

abstract class BaseOrigami
{
    protected const ROUTE = '/entities/api/{action}/format/json';
    /**
     * @var string
     */
    private string $username;
    /**
     * @var string
     */
    private string $password;
    /**
     * @var string
     */
    private string $url;
    private EntityEnum $action;

    public function __construct()
    {
        $this->username = config('origami.username');
        $this->password = config('origami.password');
        $this->url = config('origami.url');
    }

    private function getAction(): EntityEnum
    {
        return $this->action;
    }

    private function setAction(EntityEnum $entityEnum): void
    {
        $this->action = $entityEnum;
    }

    protected function getStructure(array $data): Response
    {
        $this->setAction(EntityEnum::EntityStructure);
        return $this->makeRequest($data);
    }
    protected function getList(array $data): Response
    {
        $this->setAction(EntityEnum::InstanceData);
        return $this->makeRequest($data);
    }

    protected function getInstance(array $data): Response
    {
        $this->setAction(EntityEnum::InstanceData);
        return $this->makeRequest($data);
    }

    protected function store(string $entity, array $storeData): CreateEntityResponseDto
    {
        $structure = Cache::remember($entity, Carbon::now()->addHour(), function () use ($entity) {
           return $this->getStructure(['entity_data_name' => $entity])->json();
        });

        $formData = [];
        foreach ($structure['instance_data'] as $group) {
            if (!empty($group['fields_data'])) {
                $groupData = [
                    'group_data_name' => $group['field_group_data']['group_data_name']
                ];
                $data = [];
                foreach ($group['fields_data'] as $field) {
                    if (isset($storeData[$field['field_data_name']])) {
                        $data[$field['field_data_name']] = $storeData[$field['field_data_name']];
                    }
                }
                $groupData['data'][] = $data;
                $formData[] = $groupData;
            }
        }

        $this->setAction(EntityEnum::CreateStructure);

        $result = $this->makeRequest([
            'entity_data_name' => $entity,
            'form_data' => $formData
        ])->json();

        $this->responseValidation($result);

        return CreateEntityResponseDto::fromArray($result);
    }

    protected function update(array $data): Response
    {
        $this->setAction(EntityEnum::UpdateInstanceFields);
        $response = $this->makeRequest($data);

        $this->responseValidation($response->json());

        return $response;
    }

    protected function responseValidation(array $result): void
    {
        if (!empty($result['error'])) {

            if (!empty($result['error']['column'])) {
                $errors = [];
                foreach ($result['error']['column'] as $column) {
                    $errors[$column['field_data_name']] = __($column['message']);
                }

                throw ValidationException::withMessages($errors);
            }

            throw ValidationException::withMessages($result['error']);
        }
    }

    private function makeRequest(array $data = []): Response
    {
        return Http::contentType('application/json')
            ->post(
                $this->url($this->getAction()->value),
                array_merge($data, [
                    'username' => $this->username,
                    'password' => $this->password
                ])
            );
    }

    private function url(string $action): string
    {
        return $this->url.str_replace('{action}', $action, self::ROUTE);
    }
}
