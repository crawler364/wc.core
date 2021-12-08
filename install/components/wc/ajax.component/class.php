<?php

namespace WC\Core\Components;

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;

/**
 * Компонент для ajax загрузки других компонентов
 */
class AjaxComponent extends \CBitrixComponent
{
    public function __construct($component = null)
    {
        parent::__construct($component);

        static::checkModules(['wc.sale']);

        $this->request = Application::getInstance()->getContext()->getRequest();
    }

    public function executeComponent()
    {
        if (!$this->isComponentAllowed()) {
            return;
        }

        $this->includeComponentTemplate();
    }

    public function onPrepareComponentParams($arParams): array
    {
        $arParams['COMPONENT_NAME'] = $this->request['COMPONENT_NAME'];
        $arParams['COMPONENT_TEMPLATE'] = $this->request['COMPONENT_TEMPLATE'];
        $arParams['COMPONENT_PARAMS'] = $this->request['COMPONENT_PARAMS'];

        return $arParams;
    }

    private function isComponentAllowed(): bool
    {
        // todo
        return true;
    }

    public static function checkModules(array $modules): void
    {
        foreach ($modules as $module) {
            if (!Loader::includeModule($module)) {
                throw new LoaderException(Loc::getMessage('WC_CORE_MODULE_NOT_INCLUDED', ['#REPLACE#' => $module]));
            }
        }
    }
}
