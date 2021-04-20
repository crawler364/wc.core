<?php
namespace WC\Core\ORM\Captcha;

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields;

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
    public static function getTableName()
    {
        return 'b_captcha';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return [
            (new Fields\StringField('ID',
                [
                    'validation' => [__CLASS__, 'validateId']
                ]
            ))->configureTitle(Loc::getMessage('CAPTCHA_ENTITY_ID_FIELD'))
                ->configurePrimary(true),
            (new Fields\StringField('CODE',
                [
                    'validation' => [__CLASS__, 'validateCode']
                ]
            ))->configureTitle(Loc::getMessage('CAPTCHA_ENTITY_CODE_FIELD'))
                ->configureRequired(true),
            (new Fields\StringField('IP',
                [
                    'validation' => [__CLASS__, 'validateIp']
                ]
            ))->configureTitle(Loc::getMessage('CAPTCHA_ENTITY_IP_FIELD'))
                ->configureRequired(true),
            (new Fields\DatetimeField('DATE_CREATE',
                []
            ))->configureTitle(Loc::getMessage('CAPTCHA_ENTITY_DATE_CREATE_FIELD'))
                ->configureRequired(true),
        ];
    }

    /**
     * Returns validators for ID field.
     *
     * @return array
     */
    public static function validateId()
    {
        return [
            new Fields\Validators\LengthValidator(null, 32),
        ];
    }

    /**
     * Returns validators for CODE field.
     *
     * @return array
     */
    public static function validateCode()
    {
        return [
            new Fields\Validators\LengthValidator(null, 20),
        ];
    }

    /**
     * Returns validators for IP field.
     *
     * @return array
     */
    public static function validateIp()
    {
        return [
            new Fields\Validators\LengthValidator(null, 15),
        ];
    }
}
