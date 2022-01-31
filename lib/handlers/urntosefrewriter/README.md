# UrnToSefRewriter

### UrnToSefRewriter - SEO инструмент для управления тегами и перезаписи URN страниц компонентов с ЧПУ. 
Создание ИБ и добавление событий не автоматизировано.

#### ИБ
* Внешний код - urltosefrewriter

#### Свойства ИБ
* Базовый URN - BASE_URN
* Новый URN - CONDITION_URN
* Редирект - REDIRECT
* Тег Title - TITLE
* Meta Keywords - META_KEYWORDS
* Meta Description - META_DESCRIPTION
* Заголовок h1 - H1
* Описание - DESCRIPTION

Пример ИБ и свойств
* ИБ - https://crawler364.tinytake.com/msc/NjQ3MzIzOF8xODkyMjY3MA
* Свойства ИБ - https://crawler364.tinytake.com/msc/NjQ3MzI0M18xODkyMjY3NQ
* Пример элемента - https://crawler364.tinytake.com/msc/NjQ3MzIzMl8xODkyMjY2NA

#### События
```php
use WC\Core\Handlers\UrnToSefRewriter\EventsHandler;

$eventManager = Bitrix\Main\EventManager::getInstance();
$eventManager->addEventHandler('iblock', 'OnAfterIBlockElementAdd', [EventsHandler::class, 'addRwRule']);
$eventManager->addEventHandler('iblock', 'OnIBlockElementUpdate', [EventsHandler::class, 'updateRwRule']);
$eventManager->addEventHandler('iblock', 'OnIBlockElementDelete', [EventsHandler::class, 'deleteRwRule']);
$eventManager->addEventHandler('main', 'OnPageStart', [EventsHandler::class, 'rewriteUrn']);
$eventManager->addEventHandler('main', 'OnEpilog', [EventsHandler::class, 'rewriteMeta']);
```

#### Переписать h1

```php
if (Loader::includeModule('wc.core') && \WC\Core\Handlers\UrnToSefRewriter\RuleManager::isRuleExists()) {
    $APPLICATION->ShowViewContent('sef_h1');
} 
```

#### Переписать Описание
```php
if (Loader::includeModule('wc.core') && \WC\Core\Handlers\UrnToSefRewriter\RuleManager::isRuleExists()) {
    $APPLICATION->ShowViewContent('sef_description');
} 
```
