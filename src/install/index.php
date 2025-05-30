<?php
/** @noinspection PhpUnused */

declare(strict_types=1);

use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\Config\Option;
use Bitrix\Main\DB\SqlQueryException;
use Bitrix\Main\IO\FileNotFoundException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\SystemException;
use Bitrix\Main\ArgumentException;


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

    public function DoUninstall(): bool
    {
        global $APPLICATION, $USER;
        if (!$USER->IsAdmin()) {
            return false;
        }
        $result = false;

        try {
            Option::delete($this->MODULE_ID);
            ModuleManager::unRegisterModule($this->MODULE_ID);
            $result = true;
        } catch (Throwable $exception) {
            $APPLICATION->ThrowException($exception->getMessage());
        }
        return $result;
    }
}
