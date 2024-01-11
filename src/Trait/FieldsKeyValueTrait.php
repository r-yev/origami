<?php

namespace App\Service\Origami\Trait;

use App\Service\Origami\Dto\PaginationListDto;

trait FieldsKeyValueTrait
{
    protected function getFieldsKeyValue(array $data): array
    {
        return $this->getInstanceData($data['data'][0]['instance_data']['field_groups']);
    }

    protected function makePagination(array $data, $makeItem = null): PaginationListDto
    {
        $pagination = new PaginationListDto(
            totalItems: $data['info']['total_count'] ?? 0,
            prePage: $data['info']['max_each_page'] ?? 0,
            currentPage: $data['info']['current_page_number'] ?? 0,
            totalPages: $data['info']['total_pages'] ?? 0
        );

        foreach ($data['data'] as $datum) {
            $pagination->items[] = $makeItem ? $makeItem(
                $this->getInstanceData($datum['instance_data']['field_groups'])
            ) : $this->getInstanceData($datum['instance_data']['field_groups']);
        }

        return $pagination;
    }

    private function getInstanceData(array $fieldGroups): array
    {
        $result = [];
        foreach ($fieldGroups as $group) {
            foreach ($group['fields_data'] as $fields) {
                foreach ($fields as $field) {
                    $result[$field['field_data_name']] = match ($field['field_type_name']) {
                        'input-datetime' => $field['value']['timestamp'] ?? null,
                        'metadata-field' => $field['default_value'] ?? $field['value'] ?? null,
                        default => $field['value'] ?? null
                    };
                }
            }
        }

        return $result;
    }

}
