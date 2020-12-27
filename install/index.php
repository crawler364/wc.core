<?

use Bitrix\Main\ModuleManager;
use Bitrix\Main;
use Bitrix\Main\Localization\Loc;

class wc_main extends CModule
{
    var $MODULE_ID = 'wc.main';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;

    public function __construct()
    {
        Loc::loadMessages(__FILE__);
        include __DIR__ . '/version.php';

        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }

        $this->MODULE_NAME = Loc::getMessage('WC_MAIN_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('WC_MAIN_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('WC_MAIN_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('WC_MAIN_PARTNER_URI');
    }

    function DoInstall()
    {
        global $APPLICATION;
        $result = true;

        try {
            $this->checkRequirements();
            Main\ModuleManager::registerModule($this->MODULE_ID);
            if (Main\Loader::includeModule($this->MODULE_ID)) {
                $this->InstallEvents();
            } else {
                throw new Main\SystemException(Loc::getMessage('WC_MAIN_MODULE_NOT_REGISTERED'));
            }
        } catch (Main\SystemException $exception) {
            $result = false;
            $APPLICATION->ThrowException($exception->getMessage());
        }

        return $result;
    }

    function DoUninstall()
    {
        if (Main\Loader::includeModule($this->MODULE_ID)) {
            $this->UnInstallEvents();
        }

        Main\ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    function InstallEvents()
    {
        // todo \WC\IBlock\UniqueSymbolCode
    }

    function UnInstallEvents()
    {
        // todo \WC\IBlock\UniqueSymbolCode
    }

    private function checkRequirements()
    {
        $requirePhp = '7.1';
        if (CheckVersion(PHP_VERSION, $requirePhp) === false) {
            throw new Main\SystemException(Loc::getMessage('WC_MAIN_INSTALL_REQUIRE_PHP', ['#VERSION#' => $requirePhp]));
        }

        $requireModules = [
            'main' => '17.5.0',
            'iblock' => '15.0.0',
        ];

        if (class_exists(ModuleManager::class)) {
            foreach ($requireModules as $moduleName => $moduleVersion) {
                $currentVersion = Main\ModuleManager::getVersion($moduleName);
                if (CheckVersion($currentVersion, $moduleVersion) === false) {
                    throw new Main\SystemException(Loc::getMessage('WC_MAIN_INSTALL_REQUIRE_MODULE', [
                        '#MODULE#' => $moduleName,
                        '#VERSION#' => $moduleVersion,
                    ]));
                }
            }
        }
    }
}
