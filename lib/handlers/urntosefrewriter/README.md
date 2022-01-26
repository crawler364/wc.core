# UrnToSefRewriter

### UrnToSefRewriter - SEO инструмент для управления тегами и перезаписи URN страниц компонентов с ЧПУ.

#### Свойства ИБ

* Базовый URN - BASE_URN
* Новый URN - CONDITION_URN
* Редирект - REDIRECT
* Тег Title - TITLE
* Meta Keywords - META_KEYWORDS
* Meta Description - META_DESCRIPTION
* Заголовок h1 - H1
* Описание - DESCRIPTION

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
