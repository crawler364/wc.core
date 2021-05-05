<?php


namespace WC\Core\Ui;


use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class Options
{
    public function show(): void
    {
        global $APPLICATION;

        $APPLICATION->IncludeComponent('wc:admin.form.edit', '', [
            'FORM_ID' => 'wc_core_admin_options',
            'MODULE_ID' => \WC\Core\Config::getModuleName(),
            'TABS' => $this->getTabs(),
        ]);
    }

    protected function getTabs(): array
    {
        return [
            [
                'DIV' => 'unique_symbol_code',
                'TAB' => Loc::getMessage('WC_CORE_TAB_OPTIONS_UNIQUE_SYMBOL_CODE'),
                'OPTIONS' => [
                    Loc::getMessage('WC_CORE_TAB_OPTIONS_SYMBOL_CODE_NOTE'),
                    [
                        'max_len',
                        Loc::getMessage('WC_CORE_TAB_OPTIONS_MAX_LEN'),
                        \WC\Core\Config::getOption('max_len'),
                        ['text', 10, 100],
                    ],
                    [
                        'change_case',
                        Loc::getMessage('WC_CORE_TAB_OPTIONS_MAX_LEN_CHANGE_CASE'),
                        \WC\Core\Config::getOption('change_case'),
                        [
                            'selectbox',
                            [
                                'L' => Loc::getMessage('WC_CORE_TAB_OPTIONS_MAX_LEN_CHANGE_CASE_L'),
                                'U' => Loc::getMessage('WC_CORE_TAB_OPTIONS_MAX_LEN_CHANGE_CASE_U'),
                                'false' => Loc::getMessage('WC_CORE_TAB_OPTIONS_MAX_LEN_CHANGE_CASE_D'),
                            ],
                        ],
                    ],
                    [
                        'replace_space',
                        Loc::getMessage('WC_CORE_TAB_OPTIONS_REPLACE_SPACE'),
                        \WC\Core\Config::getOption('replace_space'),
                        ['text', 1, 10],
                    ],
                    [
                        'replace_other',
                        Loc::getMessage('WC_CORE_TAB_OPTIONS_REPLACE_OTHER'),
                        \WC\Core\Config::getOption('replace_other'),
                        ['text', 1, 10],
                    ],
                    [
                        'delete_repeat_replace',
                        Loc::getMessage('WC_CORE_TAB_OPTIONS_DELETE_REPEAT_REPLACE'),
                        \WC\Core\Config::getOption('delete_repeat_replace'),
                        ['checkbox'],
                    ],
                ],
            ],
        ];
    }
}
