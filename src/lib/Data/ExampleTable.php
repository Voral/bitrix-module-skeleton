<?php

namespace Vendor\Skeleton\Data;

use Bitrix\Main\Entity;

class ExampleTable extends Entity\DataManager
{
    /** @noinspection PhpMissingReturnTypeInspection
     * @noinspection ReturnTypeCanBeDeclaredInspection
     */
    public static function getTableName()
    {
        return 'vendor_skeleton_example';
    }

    /**
     * @return array
     *
     * @noinspection PhpMissingReturnTypeInspection
     * @noinspection ReturnTypeCanBeDeclaredInspection
     */
    public static function getMap()
    {
        return [
            (new Entity\IntegerField('ID'))
                ->configureAutocomplete()
                ->configurePrimary(),
            new Entity\StringField('NAME'),
        ];
    }
}