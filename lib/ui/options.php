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
            'FORM_ID' => 'WC_CORE_ADMIN_OPTIONS',
            'TABS' => $this->getTabs(),
            'FIELDS' => $this->getOptions(),
            'BUTTONS' => [
                ['BEHAVIOR' => 'save'],
                ['BEHAVIOR' => 'reset'],
            ],
        ]);
    }

    protected function getTabs(): array
    {
        return [
            'COMMON' => [
                'name' => Loc::getMessage('WC_CORE_TAB_OPTIONS'),
            ],
        ];
    }

    public function getOptions(): array
    {
        return $this->getUniqueSymbolCodeOptions();
    }

    protected function getUniqueSymbolCodeOptions(): array
    {
        return [
            'max_len' => [
                'TYPE' => 'integer',
                'GROUP' => Loc::getMessage('WC_CORE_TAB_OPTIONS_UNIQUE_SYMBOL_CODE'),
                'NAME' => Loc::getMessage('WC_CORE_TAB_OPTIONS_MAX_LEN'),
                'HELP_MESSAGE' => Loc::getMessage('WC_CORE_TAB_OPTIONS_MAX_LEN_HELP'),
                'SETTINGS' => [
                    'DEFAULT_VALUE' => 100,
                    'MIN_VALUE' => 1,
                ],
            ],
            'change_case' => [
                'TYPE' => 'enumeration',
                'GROUP' => Loc::getMessage('WC_CORE_TAB_OPTIONS_UNIQUE_SYMBOL_CODE'),
                'NAME' => Loc::getMessage('WC_CORE_TAB_OPTIONS_MAX_LEN_CASE'),
                'HELP_MESSAGE' => Loc::getMessage('WC_CORE_TAB_OPTIONS_MAX_LEN_CASE_HELP'),
                'VALUES' => [
                    [
                        'ID' => 'L',
                        'VALUE' => 'L',
                    ],
                    [
                        'ID' => 'l',
                        'VALUE' => 'l',
                    ],
                ],
                'SETTINGS' => [
                    'DEFAULT_VALUE' => 'L',
                    'ALLOW_NO_VALUE' => 'N',
                ],
            ],
            'replace_space' => [
                'TYPE' => 'string',
                'GROUP' => Loc::getMessage('WC_CORE_TAB_OPTIONS_UNIQUE_SYMBOL_CODE'),
                'NAME' => Loc::getMessage('WC_CORE_TAB_OPTIONS_REPLACE_SPACE'),
                'HELP_MESSAGE' => Loc::getMessage('WC_CORE_TAB_OPTIONS_REPLACE_SPACE_HELP'),
                'SETTINGS' => [
                    'DEFAULT_VALUE' => '-',
                ],
            ],
            'replace_other' => [
                'TYPE' => 'string',
                'GROUP' => Loc::getMessage('WC_CORE_TAB_OPTIONS_UNIQUE_SYMBOL_CODE'),
                'NAME' => Loc::getMessage('WC_CORE_TAB_OPTIONS_REPLACE_OTHER'),
                'HELP_MESSAGE' => Loc::getMessage('WC_CORE_TAB_OPTIONS_REPLACE_OTHER_HELP'),
                'SETTINGS' => [
                    'DEFAULT_VALUE' => '',
                ],
            ],
            'delete_repeat_replace' => [
                'TYPE' => 'boolean',
                'GROUP' => Loc::getMessage('WC_CORE_TAB_OPTIONS_DELETE_REPEAT'),
                'NAME' => Loc::getMessage('WC_CORE_TAB_OPTIONS_DELETE_REPEAT_HELP'),
            ],
        ];
    }
}
