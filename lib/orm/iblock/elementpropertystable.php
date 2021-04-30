<?php


namespace WC\Core\ORM\IBlock;


use Bitrix\Iblock\PropertyEnumerationTable;
use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\Entity\FloatField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\SystemException;

Loc::loadMessages(__FILE__);

class ElementPropertySTable extends DataManager
{
    protected static $iBlockId;

    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return 'b_iblock_element_prop_s' . static::$iBlockId;
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     * @throws SystemException
     */
    public static function getMap(): array
    {
        $map = [
            'IBLOCK_ELEMENT_ID' => new IntegerField('IBLOCK_ELEMENT_ID', [
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('IBLOCK_ELEMENT_ID_FIELD'),
            ]),
        ];

        $properties = \Bitrix\Iblock\PropertyTable::getList([
            'select' => ['ID', 'PROPERTY_TYPE'],
            'filter' => ['=IBLOCK_ID' => self::$iBlockId],
        ])->fetchAll();

        foreach ($properties as $property) {
            $fieldName = 'PROPERTY_' . $property['ID'];
            $fieldEnumName = 'PROPERTY_' . $property['ID'] . '_ENUM';

            switch ($property['PROPERTY_TYPE']) {
                case 'L':
                    $map[$fieldName] = new IntegerField($fieldName);
                    $map[$fieldEnumName] = new Reference(
                        $fieldEnumName,
                        PropertyEnumerationTable::class,
                        Join::on("this.$fieldName", 'ref.ID')
                    );
                    break;
                case 'F':
                case 'E':
                case 'G':
                    $map[$fieldName] = new IntegerField($fieldName);
                    break;
                case 'N':
                    $map[$fieldName] = new FloatField($fieldName);
                    break;
                case 'S':
                default:
                    $map[$fieldName] = new StringField($fieldName);
            }
        }

        return $map;
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

    /**
     * @param int $iBlockId
     * @return DataManager
     */
    public static function compileEntity(int $iBlockId): DataManager
    {
        self::$iBlockId = $iBlockId;
        $class = 'ElementPropertyS' . self::$iBlockId . 'Table';

        if (!class_exists($class)) {
            $eval = "class {$class} extends " . __CLASS__ . "{}";
            eval($eval);
        }

        return new $class;
    }
}
