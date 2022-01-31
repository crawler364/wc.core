<?php

namespace WC\Core\Handlers\UrnToSefRewriter;

use Bitrix\Main\Context;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Request;
use Bitrix\Main\Server;
use Bitrix\Main\UrlRewriter;

/**
 * Класс для работы с URN страницами
 */
class UrnHandler
{
public const REQUEST_URN = 'REQUEST_URI';
public const REQUEST_URN_REAL = 'REQUEST_URI_REAL';
    /** @var Context */
    private $context;
    /** @var HttpRequest|Request */
    private $request;
    /** @var string */
    private $requestUrn;
        /** @var Server */
    private $server; // URN загружаемой страницы
        /** @var array|string|null */
    private $requestUrnReal; // URN виртуальной страницы

    public function __construct()
    {
        $this->context = Context::getCurrent();
        $this->request = $this->context->getRequest();
        $this->server = $this->context->getServer();
        $this->requestUrn = $this->getRequestUrn();
        $this->requestUrnReal = $this->getRequestUrnReal();
    }

    /**
     * Переписать URN + 301 редирект
     */
    public function rewriteUrn(): void
    {
        if (defined('ADMIN_SECTION') && (ADMIN_SECTION === true)) {
            return;
        }

        $rwRule = RwRuleManager::find(['QUERY' => $this->requestUrn]);

        if (UrlRewriter::CheckPath($rwRule['PATH'])) {
            if ($rule = RuleManager::getRuleByConditionUrn($this->requestUrn)) {
                global $APPLICATION;
                $response = $this->context->getResponse();
                $cookies = $this->request->getCookieRawList()->getValues();
                $this->server->set(self::REQUEST_URN, $rule['BASE_URN']['VALUE']);
                $this->server->set(self::REQUEST_URN_REAL, $rule['CONDITION_URN']['VALUE']);
                $this->context->initialize(new HttpRequest($this->server, [], [], [], $cookies), $response, $this->server);
                $APPLICATION->reinitPath();
            } elseif (($rule = RuleManager::getRuleByBaseUrn($this->requestUrn)) && !empty($rule['REDIRECT']['VALUE'])) {
                LocalRedirect($this->getRedirectUri($rule), false, '301 Moved Permanently');
            }
        }
    }

    /**
     * Переписать теги + текст
     */
    public function rewriteMeta(): void
    {
        if (defined('ADMIN_SECTION') && (ADMIN_SECTION === true)) {
            return;
        }

        $rule = RuleManager::getRuleByBaseUrn($this->requestUrn);

        if (empty($rule)) {
            return;
        }

        if (
            (empty($rule['CONDITION_URN']['VALUE']) && empty($rule['REDIRECT']['VALUE'])) || // переписать для BASE_URN
            (!empty($this->requestUrnReal) && $this->requestUrnReal === $rule['CONDITION_URN']['VALUE']) // переписать для CONDITION_URN
        ) {
            global $APPLICATION;

            if (!empty($rule['TITLE']['VALUE'])) {
                $APPLICATION->SetPageProperty('title', $rule['TITLE']['VALUE']);
                $APPLICATION->SetTitle($rule['TITLE']['VALUE']);
            }

            if (!empty($rule['META_KEYWORDS']['VALUE'])) {
                $APPLICATION->SetPageProperty('keywords', $rule['KEYWORDS']['VALUE']);
            }

            if (!empty($rule['META_DESCRIPTION']['VALUE'])) {
                $APPLICATION->SetPageProperty('description', $rule['META_DESCRIPTION']['VALUE']);
            }

            if (!empty($rule['H1']['VALUE'])) {
                $APPLICATION->AddViewContent('sef_h1', $rule['H1']['VALUE']);
            }

            if (!empty($rule['DESCRIPTION']['~VALUE']['TEXT'])) {
                $APPLICATION->AddViewContent('sef_description', $rule['DESCRIPTION']['~VALUE']['TEXT']);
            }
        }
    }

    private function getRequestUrnReal()
    {
        return $this->server->get(self::REQUEST_URN_REAL) ?? '';
    }

    private function getRequestUrn()
    {
        return strtok($this->server->get(self::REQUEST_URN), '?');
    }

    private function getRedirectUri($rule)
    {
        $getParams = $this->server->get('QUERY_STRING');

        if (!empty($getParams)) {
            $redirectUri = $rule['CONDITION_URN']['VALUE'] . '?' . $this->server->get('QUERY_STRING');
        } else {
            $redirectUri = $rule['CONDITION_URN']['VALUE'];
        }

        return $redirectUri;
    }
}
