<?php

namespace Vendor\Skeleton\System\Updater;

use Vasoft\Core\Updater\HandlerDto;
use Vasoft\Core\Updater\HandlerInstaller;
use Vendor\Skeleton\System\Handlers\VasoftDependencyHandler;
use Vendor\Skeleton\System\ModuleSettings;


class Handlers extends HandlerInstaller
{
    public function __construct()
    {
        parent::__construct(ModuleSettings::MODULE_ID, [
            new HandlerDto(
                'vasoft.core',
                'onBeforeRemoveVasoftCore',
                VasoftDependencyHandler::class,
                'onBeforeRemoveVasoftCore'
            ),
        ]);
    }
}