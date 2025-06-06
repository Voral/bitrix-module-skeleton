<?php
/** @noinspection PhpUnused */

namespace Vendor\Skeleton\System\Handlers;

use Bitrix\Main\Event;
use Bitrix\Main\EventResult;
use Bitrix\Main\Localization\Loc;
use Vendor\Skeleton\System\ModuleSettings;


class VasoftDependencyHandler
{
    /**
     * Предотвращение удаления модуля vasoft.core
     * @param Event $event
     * @return EventResult
     * @noinspection PhpUnusedParameterInspection
     * @noinspection PhpUnused
     */
    public static function onBeforeRemoveVasoftCore(Event $event): EventResult
    {
        return new EventResult(
            EventResult::ERROR, Loc::getMessage('VENDOR_SKELETON_MODULE_NAME'), ModuleSettings::MODULE_ID
        );
    }
}