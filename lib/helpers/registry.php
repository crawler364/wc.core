<?php

namespace WC\Core\Helpers;

class Registry
{
    private $registry;

    public function __construct($registryClass, $registryType)
    {
        $this->registry = $registryClass::getInstance($registryType);
    }

    public function set($registryEntity, $class): void
    {
        $this->registry->set($registryEntity, $class);
    }
}
