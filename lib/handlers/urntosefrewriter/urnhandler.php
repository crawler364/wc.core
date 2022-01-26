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
    /** @var Context */
    private $context;
    /** @var HttpRequest|Request */
    private $request;
    /** @var string */
    private $urn;
    /** @var Server */
    private $server;

    public function __construct()
    {
        $this->context = Context::getCurrent();
        $this->request = $this->context->getRequest();
        $this->server = $this->context->getServer();
        $this->urn = $this->server->get('REQUEST_URI');
    }

    /**
     * Переписать URN + 301 редирект
     */
    public function rewriteUrn(): void
    {
        if (defined('ADMIN_SECTION') && (ADMIN_SECTION === true)) {
            return;
        }

        $rwRule = RwRuleManager::find(['QUERY' => $this->urn]);

        if (UrlRewriter::CheckPath($rwRule['PATH'])) {
            if ($rule = RuleManager::getRuleByConditionUrn($this->urn)) {
                global $APPLICATION;
                $response = $this->context->getResponse();
                $cookies = $this->request->getCookieRawList()->getValues();
                $this->server->set('REQUEST_URI', $rule['BASE_URN']['VALUE']);
                $this->server->set('REQUEST_URI_REAL', $rule['CONDITION_URN']['VALUE']);
                $this->context->initialize(new HttpRequest($this->server, [], [], [], $cookies), $response, $this->server);
                $APPLICATION->reinitPath();
            } elseif (($rule = RuleManager::getRuleByBaseUrn($this->urn)) && $rule['REDIRECT']['VALUE'] != null) {
                LocalRedirect($rule['CONDITION_URN']['VALUE'], false, '301 Moved Permanently');
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

        global $APPLICATION;

        $urn = $this->server->get('REQUEST_URI_REAL') ?? '';
        $rule = RuleManager::getRuleByConditionUrn($urn);

        if (empty($rule)) {
            return;
        }

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
