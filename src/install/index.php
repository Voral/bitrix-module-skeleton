<?php
/** @noinspection PhpUnused */

declare(strict_types=1);

use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main;
use Vendor\Skeleton\System\Updater\Handlers;
use Vendor\Skeleton\System\Updater\Tables;
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
    private int $step = 1;
    private bool $removeData = true;

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
            $this->initModules();
            ModuleManager::registerModule($this->MODULE_ID);
            $this->initModule();
            $this->InstallDB();
            $this->InstallEvents();
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
        global $APPLICATION, $USER;
        if (!$USER->IsAdmin()) {
            return false;
        }
        $this->processRequest();
        if ($this->step === 1) {
            $APPLICATION->IncludeAdminFile(Loc::getMessage('VENDOR_SKELETON_UNINSTALL_TITLE'), __DIR__ . '/unstep1.php');
            return false;
        }
        $result = false;
        if ($this->step === 2) {
            try {
                $this->initModules();
                $this->initModule();
                $this->UnInstallEvents();
                if ($this->removeData) {
                    $this->UnInstallDB();
                    Option::delete($this->MODULE_ID);
                }
                ModuleManager::unRegisterModule($this->MODULE_ID);
                $APPLICATION->IncludeAdminFile(
                    Loc::getMessage('VENDOR_SKELETON_UNINSTALL_TITLE'),
                    __DIR__ . '/unstep2.php'
                );
                $result = true;
            } catch (Throwable $exception) {
                $APPLICATION->ThrowException($exception->getMessage());
            }
        }
        return $result;
    }

    /**
     * @return bool
     * @throws Main\ArgumentException
     * @throws Main\SystemException
     */
    public function InstallDB(): bool
    {
        (new Tables())->check();
        return true;
    }

    /**
     * @return bool
     * @throws Main\ArgumentException
     * @throws Main\SystemException
     */
    public function UnInstallDB(): bool
    {
        (new Tables())->clean();
        return true;
    }

    /**
     * @return bool
     * @throws Main\DB\SqlQueryException
     */
    public function InstallEvents(): bool
    {
        (new Handlers())->check();
        return true;
    }

    /**
     * @throws Main\DB\SqlQueryException
     */
    public function UnInstallEvents(): bool
    {
        (new Handlers())->clean();
        return true;
    }

    /**
     * @return void
     * @throws Main\SystemException
     */
    private function processRequest(): void
    {
        $app = Application::getInstance();
        if ($app === null) {
            throw new Main\SystemException(Loc::getMessage('VENDOR_SKELETON_ERROR_CORE'));
        }
        $request = $app->getContext()->getRequest();
        $this->step = (int)$request->get('step');
        $this->removeData = trim($request->get('savedata') ?? '') !== 'Y';
        if (!check_bitrix_sessid() || $this->step > 2 || $this->step < 1) {
            $this->step = 1;
        }
    }
}
