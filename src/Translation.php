<?php

namespace App\Service\Origami;

use App\Enum\Language;
use App\Enum\Origami\EntityEnum;

class Translation extends BaseOrigami
{

    private string $entity = 'portal_translation';
    public function getTranslation(Language $language): array
    {
        $response = $this->getList([
            'entity_data_name' => $this->entity,
        ])->json();


        $result = [];
        foreach ($response['data'] as $data) {
            foreach ($data['instance_data']['field_groups'] as $groups) {
                if (!empty($groups['fields_data'])) {
                    foreach ($groups['fields_data'] as $fieldsData) {
                        $item = [];
                        foreach ($fieldsData as $field) {
                            $item[$field['field_name']] = $field['value'] ?? '';
                        }
                        $result[$item['Key']] = $item[$language->name];
                    }
                }
            }
        }

        return $result;
    }
}
