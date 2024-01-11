<?php

namespace Origami\Enum;

enum EntityEnum: string
{
    case EntitiesList = 'entities_list';
    case EntityStructure = 'entity_structure';
    case CreateStructure = 'create_instance';
    case InstanceData = 'instance_data';
    case UpdateInstanceFields = 'update_instance_fields';
}
