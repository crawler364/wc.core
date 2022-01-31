# wc:ajax.component

### wc:ajax.component - компонент для ajax загрузки других компонентов

#### Пример использования

```php
CUtil::InitJSCore(['wc:ajax.component']);
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
