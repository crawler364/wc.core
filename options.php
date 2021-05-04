<?php

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$controller = new WC\Core\Ui\Options();
$controller->show();
