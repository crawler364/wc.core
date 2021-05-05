<?php


namespace WC\Core\Components;


use Bitrix\Main\Application;

class AdminFormEdit extends \CBitrixComponent
{
    public function __construct($component = null)
    {
        parent::__construct($component);

        $this->request = Application::getInstance()->getContext()->getRequest();
    }

    public function executeComponent()
    {
        if ($this->request->isPost() && $this->request->get('save') && check_bitrix_sessid()) {
            $this->saveSettings();
        }

        $this->includeComponentTemplate();
    }

    public function saveSettings(): void
    {
        foreach ($this->arParams['TABS'] as $tab) {
            __AdmSettingsSaveOptions($this->arParams['MODULE_ID'], $tab['OPTIONS']);
        }
    }
}
