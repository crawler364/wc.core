<?php


namespace WC\Core\Bitrix\Main;


use Bitrix\Main\Engine\Response\AjaxJson;
use Bitrix\Main\Error;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Web\Json;

class Result extends \Bitrix\Main\Result
{
    /**
     * @param Error|string $error
     * @param array $params = ['REPLASE', 'LANGUAGE', 'CUSTOM_DATA']
     * @return Result
     */
    final public function addError($error, $params = []): Result
    {
        if (!$error instanceof Error) {
            $message = Loc::getMessage($error, $params['REPLASE'], $params['LANGUAGE']);
            $error = new Error($message, $error, $params['CUSTOM_DATA']);
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

        $result = \WC\Core\Helpers\Main::reformatArrayKeys($result);

        echo Json::encode($result);

        \CMain::FinalActions();
        die();
    }

    final public function prepareAjaxJson(): AjaxJson
    {
        $data = \WC\Core\Helpers\Main::reformatArrayKeys($this->getData());
        $isSuccess = $this->isSuccess() ? AjaxJson::STATUS_SUCCESS : AjaxJson::STATUS_ERROR;

        return new AjaxJson($data, $isSuccess, $this->getErrorCollection());
    }


    final public function getDataField($field)
    {
        return $this->data[$field];
    }

    final public function setSuccess(bool $bool)
    {
        $this->isSuccess = $bool;
    }

    final public function getFirstError(): ?Error
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
