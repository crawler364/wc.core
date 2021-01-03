<?php


namespace WC\Main;


use Bitrix\Main\Error;
use Bitrix\Main\Web\Json;

class Result extends \Bitrix\Main\Result
{
    /**
     * @param Error|array $error
     * @return Result
     */
    final public function addError($error): Result
    {
        if (!$error instanceof Error) {
            $error = new Error($error['MESSAGE'], $error['CODE'], $error['CUSTOM_DATA']);
        }

        return parent::addError($error);
    }

    final public function echoJson()
    {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();
        define('NO_KEEP_STATISTIC', true);
        header('Content-Type: application/json');

        $result = [
            'IS_SUCCESS' => $this->isSuccess(),
            'DATA' => $this->getData(),
            'ERRORS' => $this->getErrors(),
        ];

        $result = \WC\Main\Tools::reformatArrayKeys($result);

        echo Json::encode($result);
    }

    final public function getDataField($field)
    {
        return $this->data[$field];
    }

    final public function setSuccess(bool $bool)
    {
        $this->isSuccess = $bool;
    }

    final public function getFirstError()
    {
        return $this->getErrors()[0];
    }

    final public function mergeResult(object $result)
    {
        if ($data = $result->getData()) {
            $this->setData($data);
        }

        if ($errors = $result->getErrors()) {
            $this->addErrors($errors);
        }
    }
}