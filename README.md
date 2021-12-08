# Модуль для 1С-Битрикс WC.Core

### wc:ajax.component - компонент для ajax загрузки других компонентов
#### Пример использования
```php
Bitrix\Main\Loader::includeModule('wc.core');
```

```js
let data = {
    COMPONENT_NAME: 'bitrix:catalog.section',
    COMPONENT_TEMPLATE: '',
    COMPONENT_PARAMS: {
        'IBLOCK_ID': 2,
    },
};

WC.Ajax.Component.load(data).then((response) => {
    $(document.body).append(response);
});

```
