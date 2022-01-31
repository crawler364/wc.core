<?php

namespace WC\Core\Handlers\UrnToSefRewriter;

use Bitrix\Main\Context;
use Bitrix\Main\SiteTable;
use Bitrix\Main\UrlRewriter;

/**
 * Класс для работы с правилами urlrewrite.php
 */
class RwRuleManager
{
    /**
     * Добавить правило в urlrewrite.php
     *
     * @param array $rule
     */
    public static function add(array $rule): void
    {
        if (self::validate($rule)) {
            UrlRewriter::add(self::getSiteId(), $rule);
        }
    }

    /**
     * Обновить правило в urlrewrite.php
     *
     * @param array $filter
     * @param array $rule
     */
    public static function update(array $filter, array $rule): void
    {
        if (self::validate($rule)) {
            UrlRewriter::update(self::getSiteId(), $filter, $rule);
        }
    }

    /**
     * Удалить правило в urlrewrite.php
     *
     * @param array $filter
     */
    public static function delete(array $filter): void
    {
        UrlRewriter::delete(self::getSiteId(), $filter);
    }

    /**
     * Получить правило из urlrewrite.php
     *
     * @param array $filter
     *
     * @return mixed|null
     */
    public static function find(array $filter)
    {
        $urlRewrite = UrlRewriter::getList(
            self::getSiteId(), $filter
        );

        return array_shift($urlRewrite);
    }

    /**
     * Создать фильтр для поиска правила в urlrewrite.php
     *
     * @param string $urn
     *
     * @return string[]
     */
    public static function createRwRuleFilter(string $urn): array
    {
        return ['CONDITION' => self::formatUrn($urn)];
    }

    /**
     * Создать правило для urlrewrite.php из свойств элемента ИБ
     *
     * @param array $properties
     *
     * @return array
     */
    public static function createRwRule(array $properties): array
    {
        $rule = [];

        foreach ($properties as $key => $property) {
            switch ($key) {
                case 'CONDITION_URN':
                    if (!empty($property['VALUE'])) {
                        $rule['CONDITION'] = self::formatUrn($property['VALUE']);
                    }
                    break;
                case 'BASE_URN':
                    $rewriteRule = self::find(['QUERY' => $property['VALUE']]);
                    $rule["ID"] = $rewriteRule['ID'];
                    $rule["PATH"] = $rewriteRule['PATH'];
                    break;
            }
        }

        if (!empty($rule)) {
            $rule["SORT"] = 100;
        }

        return $rule;
    }

    public static function formatUrn(string $urn): string
    {
        return "#^$urn#";
    }

    private static function getSiteId(): string
    {
        $siteId = Context::getCurrent()->getSite();

        if (empty($siteId)) {
            $siteId = SiteTable::getList([
                'order' => ['SORT' => 'ASC'],
                'select' => ['LID'],
                'cache' => ['ttl' => 60 * 60 * 24],
            ])->fetch()['LID'];
        }

        return $siteId;
    }

    private static function validate(array $rule): bool
    {
        if (empty($rule['CONDITION'])) {
            return false;
        }

        if (empty($rule['PATH'])) {
            return false;
        }

        return true;
    }
}
