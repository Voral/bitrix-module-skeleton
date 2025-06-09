<?php
/** @noinspection PhpUnused */

declare(strict_types=1);

use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main;
use Bitrix\Main\Application;

Loc::loadMessages(__FILE__);

class vendor_skeleton extends CModule
{
    var $MODULE_ID = "vendor.skeleton";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_GROUP_RIGHTS = "Y";
    var $SHOW_SUPER_ADMIN_GROUP_RIGHTS = "Y";

    public function __construct()
    {
        $arModuleVersion = [];
        include_once __DIR__ . '/version.php';
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = Loc::getMessage('VENDOR_SKELETON_CORE_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('VENDOR_SKELETON_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = '';
        $this->PARTNER_URI = '';
    }

    /**
     * @return void
     * @throws Main\LoaderException
     * @throws Main\SystemException
     */
    private function initModules(): void
    {
        if (!Loader::includeModule('vasoft.core')) {
            throw new Main\SystemException(Loc::getMessage('VENDOR_SKELETON_VASOFT_CORE_NOT_REGISTERED'));
        }
    }

    /**
     * @return void
     * @throws Main\LoaderException
     * @throws Main\SystemException
     */
    private function initModule(): void
    {
        if (!Loader::includeModule($this->MODULE_ID)) {
            throw new Main\SystemException(Loc::getMessage('VENDOR_SKELETON_MODULE_NOT_REGISTERED'));
        }
    }

    public function DoInstall(): bool
    {
        global $APPLICATION, $USER;
        if (!$USER->IsAdmin()) {
            return false;
        }
        $result = false;
        try {
            ModuleManager::registerModule($this->MODULE_ID);
            $result = true;
        } catch (Exception $exception) {
            $APPLICATION->ThrowException($exception->getMessage());
        }
        return $result;
    }

    /**
     * @return bool
     * @throws Main\SystemException
     */
    public function DoUninstall(): bool
    {
        global $USER;
        if (!$USER->IsAdmin()) {
            return false;
        }
        Option::delete($this->MODULE_ID);
        ModuleManager::unRegisterModule($this->MODULE_ID);
        return true;
    }
}
