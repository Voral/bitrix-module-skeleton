<?php
/** @noinspection DuplicatedCode */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @var $APPLICATION CAllMain */
/** @global $REQUEST_METHOD */
/** @global $REQUEST_METHOD */
/** @var $RestoreDefaults */
/** @var $Update */

/** @var $mid */

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Vasoft\Core\Settings\Entities\Fields;
use Vasoft\Core\Settings\Entities\Tab;
use Vasoft\Core\Settings\Render\Controller;
use Vendor\Skeleton\System\ModuleSettings;

Loc::loadMessages(__FILE__);
$request = Main\Context::getCurrent()->getRequest();

$module_id = "vendor.skeleton";
$rights = CMain::GetGroupRight($module_id);
if ($rights < "R") {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}
Main\Loader::includeModule($module_id);
$config = ModuleSettings::getInstance(false);
$tabMain= new Tab(
    'main',
    Loc::getMessage('VENDOR_SKELETON_TAB_MAIN'),
    [
        (new Fields\NotZeroIntField(
            ModuleSettings::PROP_EXAMPLE,
            $config->getOptionName(ModuleSettings::PROP_EXAMPLE),
            $config->getExample(...)
        ))->configureWidth(50),
    ]
);
$controller = new Controller(
    [
        $tabMain,
    ],
    true
);

// Обработка запроса
if ($request->isPost()) {
    try {
        if ($rights < "W") {
            throw new Main\AccessDeniedException();
        }
        if (!check_bitrix_sessid()) {
            throw new Main\ArgumentException("Bad sessid.");
        }
        $config->saveFromArray($request->getPostList()->getValues());
    } catch (Exception $exception) {
        CAdminMessage::ShowMessage($exception->getMessage());
    }
} elseif ($request->get("restore") !== null && check_bitrix_sessid()) {
    $config->clean();
    $v1 = "id";
    $v2 = "asc";
    $z = CGroup::GetList($v1, $v2, array("ACTIVE" => "Y", "ADMIN" => "N"));
    while ($zr = $z->Fetch()) {
        CMain::DelGroupRight($module_id, array($zr["ID"]));
    }
}

$tabControl = $controller->startTabControl('tabControl');
?>
    <form method="post"
          action="<?= $APPLICATION->GetCurPage() ?>?mid=<?= htmlspecialcharsbx($mid) ?>&lang=<?= LANGUAGE_ID ?>">
        <?php
        $controller->echoTabs($tabControl);
        require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/admin/group_rights.php");
        $tabControl->Buttons(); ?>
        <script>
            function RestoreDefaults() {
                if (confirm('<?= AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>'))
                    window.location = "<?= $APPLICATION->GetCurPage()?>?restore=Y&lang=<?=LANGUAGE_ID?>&mid=<?= urlencode($mid)?>&<?=bitrix_sessid_get()?>";
            }
        </script>
        <input <?php if ($rights < "W") {
            echo "disabled";
        } ?> type="submit" name="Update" value="Сохранить">
        <input type="hidden" name="Update" value="Y">
        <input type="reset" name="reset" value="Сбросить">
        <input <?php if ($rights < "W") {
            echo "disabled";
        } ?> type="button"
             title="<?= GetMessage("MAIN_HINT_RESTORE_DEFAULTS") ?>"
             OnClick="RestoreDefaults();"
             value="Восстановить по умолчанию">
    </form>
<?php
$tabControl->End();
