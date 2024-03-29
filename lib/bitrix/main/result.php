<?php


namespace WC\Core\Bitrix\Main;


use Bitrix\Main\Engine\Response\AjaxJson;
use Bitrix\Main\Error;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Web\Json;
use WC\Core\Handlers\ReformatArray;

class Result extends \Bitrix\Main\Result
{
    /**
     * @param Error|string $error
     * @param array        $params = ['REPLACE', 'LANGUAGE', 'CUSTOM_DATA']
     *
     * @return Result
     */
    public function addError($error, array $params = []): Result
    {
        if ($error instanceof Error) {
            $obError = $error;
        } else if ($message = Loc::getMessage($error, $params['REPLACE'], $params['LANGUAGE'])) {
            $obError = new Error($message, $error, $params['CUSTOM_DATA']);
        } else {
            $obError = new Error($error, 0, $params['CUSTOM_DATA']);
        }

        return parent::addError($obError);
    }

    public function echoJson(): void
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

        $result = ReformatArray::init($result);

        echo Json::encode($result);

        \CMain::FinalActions();
    }

    public function prepareAjaxJson(): AjaxJson
    {
        $data = ReformatArray::init($this->getData());
        $isSuccess = $this->isSuccess() ? AjaxJson::STATUS_SUCCESS : AjaxJson::STATUS_ERROR;

        return new AjaxJson($data, $isSuccess, $this->getErrorCollection());
    }

    public function getDataField($field)
    {
        return $this->data[$field];
    }

    public function setSuccess(bool $bool): void
    {
        $this->isSuccess = $bool;
    }

    public function getFirstError(): ?Error
    {
        return $this->getErrors()[0];
    }

    /**
     * @deprecated
     *
     * @param object $result
     */
    public function mergeResult(object $result): void
    {
        if ($data = $result->getData()) {
            $this->setData($data);
        }

        if ($errors = $result->getErrors()) {
            $this->addErrors($errors);
        }
    }
}
