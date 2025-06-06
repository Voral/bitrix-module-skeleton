<?php

namespace Vendor\Skeleton\System\Updater;

use Vasoft\Core\Updater\TableInstaller;
use Vendor\Skeleton\Data\ExampleTable;
use Vendor\Skeleton\System\ModuleSettings;

class Tables extends TableInstaller
{
    public function __construct()
    {
        parent::__construct(ModuleSettings::MODULE_ID, [
            ExampleTable::class
        ]);
    }
}