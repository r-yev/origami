<?php

namespace App\Service\Origami;

use App\Exceptions\SaveDataException;
use App\Service\Origami\Dto\Client\ClientDto;
use App\Service\Origami\Dto\CreateEntityResponseDto;
use App\Service\Origami\Trait\FieldsKeyValueTrait;
use App\Service\Origami\Trait\GetByIdTrait;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class Client extends BaseOrigami
{
    use FieldsKeyValueTrait, GetByIdTrait;
    private string $entity = 'portal_clients';

    public function getById(string $id): ?ClientDto
    {
        $data = $this->fetchById($id);

        if (empty($data['data'])) {
            return null;
        }

        return ClientDto::fromArray($this->getFieldsKeyValue($data));
    }

    public function getByPhone(string $phone): ?ClientDto
    {
        $data = $this->getList([
            'entity_data_name' => $this->entity,
            'filter' => [
                ['phone', '=', $phone]
            ]
        ])->json();

        if (empty($data['data'])) {
            return null;
        }

        return ClientDto::fromArray($this->getFieldsKeyValue($data));
    }
    public function getByToken(string $token): ?ClientDto
    {
        $data = $this->getList([
            'entity_data_name' => $this->entity,
            'filter' => [
                ['token', '=', $token]
            ]
        ])->json();

        if (empty($data['data'])) {
            return null;
        }

        return ClientDto::fromArray($this->getFieldsKeyValue($data));
    }
    public function create(ClientDto $clientDto): CreateEntityResponseDto
    {

        return $this->store($this->entity, $clientDto->toArray());
    }

    /**
     * @throws SaveDataException
     * @throws ValidationException
     */
    public function save(ClientDto $clientDto): ClientDto
    {
        $data = [
            'entity_data_name' => $this->entity
        ];
        $data['filter'] = [
            ['_id', '=', $clientDto->id]
        ];

        foreach ($clientDto->toArray() as $key => $value) {
            $data['field'][] = [
                $key,
                $value
            ];
        }
        $result = $this->update($data);

        if ($result->ok() && $result->json()['success'] == 'ok') {
            return $clientDto;
        }

        throw new SaveDataException('Save data error', Response::HTTP_BAD_REQUEST);
    }

}
