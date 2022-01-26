<?php

namespace WC\Core\Handlers\UrnToSefRewriter;

use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Context;
use Bitrix\Main\Data\Cache;

/**
 * Класс для работы с правилами ИБ
 */
class RuleManager
{
    private const IBLOCK_CODE = 'urltosefrewriter';
    private const RULE_ID = 'ID';
    private const RULE_CONDITION_URN = 'PROPERTY_CONDITION_URN';
    private const RULE_BASE_URN = 'PROPERTY_BASE_URN';

    /**
     * Пересоздать все правила из ИБ в urlrewrite.php
     */
    public static function reindexAll(): void
    {
        $res = \CIBlockElement::GetList(
            [],
            ['IBLOCK_ID' => self::getIblockId(), 'ACTIVE' => 'Y'],
            false,
            false,
            ['IBLOCK_ID', 'ID']
        );

        while ($element = $res->GetNextElement()) {
            RwRuleManager::add(RwRuleManager::createRwRule($element->GetProperties()));
        }
    }

    /**
     * Получить правило из ИБ
     *
     * @param array $filter
     *
     * @return array
     */
    public static function getRule(array $filter): array
    {
        $cache = Cache::createInstance();

        if ($cache->initCache(60 * 60 * 24 * 7, json_encode($filter, JSON_THROW_ON_ERROR), self::getInitDir())) {
            $rule = $cache->getVars();
        } elseif ($cache->startDataCache()) {
            $rule = [];

            $res = \CIBlockElement::GetList(
                [],
                [
                    'IBLOCK_ID' => self::getIblockId(),
                    'ACTIVE' => 'Y',
                    $filter,
                ],
                false,
                false,
                ['IBLOCK_ID', 'ID']
            );
            if ($element = $res->GetNextElement()) {
                $rule = $element->GetProperties();
                $fields = $element->GetFields();
                $rule['ID'] = (int)$fields['ID'];
            }

            $cache->endDataCache($rule);
        }

        return $rule;
    }

    public static function getRuleById(int $id): array
    {
        $filter = [self::RULE_ID => $id];

        return self::getRule($filter);
    }

    public static function getRuleByConditionUrn(string $urn): array
    {
        $rule = [];

        if (!empty($urn)) {
            $rule = self::getRule([self::RULE_CONDITION_URN => $urn]);
        }

        return $rule;
    }

    public static function getRuleByBaseUrn(string $urn): array
    {
        $rule = [];

        if (!empty($urn)) {
            $rule = self::getRule([self::RULE_BASE_URN => $urn]);
        }

        return $rule;
    }

    /**
     * Проверить существует ли правило для текущего URN
     *
     * @return bool
     */
    public static function isRuleExists(): bool
    {
        $urn = Context::getCurrent()->getServer()->get('REQUEST_URI_REAL') ?: '';

        return !empty(self::getRuleByConditionUrn($urn));
    }

    /**
     * Очистить кеш для правила ИБ
     *
     * @param array $rule
     */
    public static function cleanCache(array $rule): void
    {
        $cache = Cache::createInstance();

        foreach (self::getCacheTags($rule) as $cacheTag) {
            $cache->clean(json_encode([$cacheTag['KEY'] => $cacheTag['VALUE']], JSON_THROW_ON_ERROR), self::getInitDir());
        }
    }

    public static function getIblockId()
    {
        return IblockTable::getRow([
            'filter' => ['=CODE' => self::IBLOCK_CODE],
            'select' => ['ID'],
            'cache' => ['ttl' => 60 * 60 * 24],
        ])['ID'];
    }

    private static function getCacheTags($rule): array
    {
        return [
            ['KEY' => self::RULE_ID, 'VALUE' => $rule['ID']],
            ['KEY' => self::RULE_CONDITION_URN, 'VALUE' => $rule['CONDITION_URN']['VALUE']],
            ['KEY' => self::RULE_BASE_URN, 'VALUE' => $rule['BASE_URN']['VALUE']],
            ['KEY' => self::RULE_CONDITION_URN, 'VALUE' => $rule['BASE_URN']['VALUE']],
            ['KEY' => self::RULE_BASE_URN, 'VALUE' => $rule['CONDITION_URN']['VALUE']],
        ];
    }

    private static function getInitDir()
    {
        return str_replace('\\', '/', __CLASS__);
    }
}
