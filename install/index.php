<?
/** @noinspection AccessModifierPresentedInspection */

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\SystemException;

Loc::loadMessages(__FILE__);

class wc_core extends CModule
{
    var $MODULE_ID = 'wc.core';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    private $kernelDir;

    public function __construct()
    {
        include __DIR__ . '/version.php';

        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }

        $this->MODULE_NAME = Loc::getMessage('WC_CORE_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('WC_CORE_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('WC_CORE_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('WC_CORE_PARTNER_URI');

        $this->kernelDir = $this->getKernelDir();
    }

    function DoInstall(): bool
    {
        global $APPLICATION;
        $result = true;

        try {
            $this->checkRequirements();
            ModuleManager::registerModule($this->MODULE_ID);
            if (Loader::includeModule($this->MODULE_ID)) {
                $this->InstallDB();
                $this->InstallEvents();
                $this->InstallFiles();
            } else {
                throw new SystemException(Loc::getMessage('WC_CORE_MODULE_NOT_REGISTERED'));
            }
        } catch (SystemException $exception) {
            $result = false;
            $APPLICATION->ThrowException($exception->getMessage());
        }

        return $result;
    }

    function DoUninstall(): void
    {
        if (Loader::includeModule($this->MODULE_ID)) {
            $this->UninstallFiles();
            $this->UnInstallEvents();
            $this->UnInstallDB();
        }

        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    function InstallEvents()
    {
    }

    function InstallDB(): bool
    {
        \WC\Core\Handlers\UniqueSymbolCode::setDefaultSettings();

        return true;
    }

    function UnInstallEvents()
    {

    }

    function InstallFiles(): void
    {
        CopyDirFiles(__DIR__ . '/components', $this->kernelDir . "/components", true, true);
    }

    function UnInstallFiles(): void
    {
        Directory::deleteDirectory($this->kernelDir . '/components/wc/admin.form.edit');
    }

    function UnInstallDB(): void
    {
        \WC\Core\Config::removeOption();
    }

    private function checkRequirements(): void
    {
        $requirePhp = '7.1';

        if (CheckVersion(PHP_VERSION, $requirePhp) === false) {
            throw new SystemException(Loc::getMessage('WC_CORE_INSTALL_REQUIRE_PHP', ['#VERSION#' => $requirePhp]));
        }

        $requireModules = [
            'main' => '17.5.0',
            'iblock' => '15.0.0',
        ];

        if (class_exists(ModuleManager::class)) {
            foreach ($requireModules as $moduleName => $moduleVersion) {
                $currentVersion = ModuleManager::getVersion($moduleName);
                if (CheckVersion($currentVersion, $moduleVersion) === false) {
                    throw new SystemException(Loc::getMessage('WC_CORE_INSTALL_REQUIRE_MODULE', [
                        '#MODULE#' => $moduleName,
                        '#VERSION#' => $moduleVersion,
                    ]));
                }
            }
        }
    }

    private function getKernelDir(): string
    {
        $kernelDir = Directory::isDirectoryExists($_SERVER['DOCUMENT_ROOT'] . '/local') ? '/local' : '/bitrix';
        return $_SERVER['DOCUMENT_ROOT'] . $kernelDir;
    }
}
