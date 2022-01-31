<?php

namespace WC\Core\Handlers\UrnToSefRewriter;

use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\PropertyTable;

/**
 * Класс для обработки событий
 */
class EventsHandler
{
    public static function rewriteMeta(): void
    {
        $urlHandler = new UrnHandler();
        $urlHandler->rewriteMeta();
    }

    public static function rewriteUrn(): void
    {
        try {
            $urnHandler = new UrnHandler();
            $urnHandler->rewriteUrn();
        } catch (\Throwable $e) {

        }
    }

    public static function addRwRule($arFields): void
    {
        if (self::checkIblockId($arFields['ID']) === true) {
            RwRuleManager::add(RwRuleManager::createRwRule(self::prepareProperties($arFields)));
            $rule = RuleManager::getRuleById($arFields['ID']);
            RuleManager::cleanCache($rule);
        }
    }

    public static function updateRwRule($arFields): void
    {
        if (self::checkIblockId($arFields['ID']) === true) {
            $rule = RuleManager::getRuleById($arFields['ID']);
            $filter = RwRuleManager::createRwRuleFilter($rule['CONDITION_URN']['VALUE']);
            $rwRule = RwRuleManager::createRwRule(self::prepareProperties($arFields));

            if (RwRuleManager::find($filter)) {
                RwRuleManager::update($filter, $rwRule);
            } else {
                RwRuleManager::add($rwRule);
            }

            RuleManager::cleanCache($rule);
        }
    }

    public static function deleteRwRule($arFields): void
    {
        if (self::checkIblockId($arFields) === true) {
            $rule = RuleManager::getRuleById($arFields);
            RwRuleManager::delete(RwRuleManager::createRwRuleFilter($rule['CONDITION_URN']['VALUE']));
            RuleManager::cleanCache($rule);
        }
    }

    private static function checkIblockId($elementId): bool
    {
        $element = ElementTable::getRow([
            'filter' => ['=ID' => $elementId],
            'select' => ['IBLOCK_ID'],
            'cache' => ['ttl' => 60 * 60 * 24],
        ]);

        return $element['IBLOCK_ID'] === RuleManager::getIblockId();
    }

    private static function prepareProperties($arFields): array
    {
        $properties = [];

        foreach ($arFields['PROPERTY_VALUES'] as $key => $property) {
            $prow = PropertyTable::getRow([
                'filter' => ['=ID' => $key],
                'select' => ['CODE'],
                'cache' => ['ttl' => 60 * 60 * 24],
            ]);
            $properties[$prow['CODE']] = array_shift($property);
        }

        return $properties;
    }
}
