<?php
namespace Concrete\Attribute\Select;

use Concrete\Core\Entity\Attribute\Key\Key;
use Concrete\Core\Entity\Attribute\Key\Type\SelectType;
use Concrete\Core\Entity\Attribute\Value\Value\SelectValueOption;


/**
 * @deprecated
 */
class Option
{

    /**
     * @param Key $ak
     * @param $option
     * @param int $isEndUserAdded
     * @return Option
     */
    public static function add($ak, $value, $isEndUserAdded = 0)
    {
        /**
         * @var $type SelectType
         */
        $type = $ak->getAttributeKeyType();
        $list = $type->getOptionList();

        $option = new SelectValueOption();
        $option->setSelectAttributeOptionValue($value);
        $option->setIsEndUserAdded($isEndUserAdded);
        $option->setOptionList($list);

        $em = \Database::connection()->getEntityManager();
        $em->persist($option);
        $em->flush();

        return $option;
    }

    public static function getByID($id)
    {
        $em = \Database::connection()->getEntityManager();
        return $em->find('\Concrete\Core\Entity\Attribute\Value\Value\SelectValueOption', $id);
    }


    public static function getByValue($value, $ak = false)
    {
        $em = \Database::connection()->getEntityManager();
        $controller = new \Concrete\Attribute\Select\Controller($em);
        return $controller->getOptionByValue($value, $ak);
    }


}