<?php


namespace WC\Core\ORM\Captcha;


use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\DatetimeField,
    Bitrix\Main\ORM\Fields\StringField,
    Bitrix\Main\ORM\Fields\Validators\LengthValidator,
    Bitrix\Main\SystemException,
    Bitrix\Main\ArgumentTypeException;

Loc::loadMessages(__FILE__);

/**
 * Class CaptchaTable
 *
 * Fields:
 * <ul>
 * <li> ID string(32) mandatory
 * <li> CODE string(20) mandatory
 * <li> IP string(15) mandatory
 * <li> DATE_CREATE datetime mandatory
 * </ul>
 *
 * @package Bitrix\Captcha
 **/
class CaptchaTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return 'b_captcha';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     * @throws SystemException
     */
    public static function getMap(): array
    {
        return [
            'ID' => new StringField('ID', [
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('ELEMENT_ENTITY_ID_FIELD'),
            ]),
            'CODE' => new StringField('CODE', [
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('ELEMENT_ENTITY_CODE_FIELD'),
            ]),
            'IP' => new StringField('IP', [
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('ELEMENT_ENTITY_IP_FIELD'),
            ]),
            'DATE_CREATE' => new DatetimeField('DATE_CREATE', [
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('ELEMENT_ENTITY_DATE_CREATE_FIELD'),
            ]),
        ];
    }

    /**
     * Returns validators for ID field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateId(): array
    {
        return [
            new LengthValidator(null, 32),
        ];
    }

    /**
     * Returns validators for CODE field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateCode(): array
    {
        return [
            new LengthValidator(null, 20),
        ];
    }

    /**
     * Returns validators for IP field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateIp(): array
    {
        return [
            new LengthValidator(null, 15),
        ];
    }
}
