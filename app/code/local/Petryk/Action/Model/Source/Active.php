<?php

/**
 * @category Petryk
 * @package Petryk_Action
 * @author Victor Petryk <victor.petryk@gmail.com>
 */
class Petryk_Action_Model_Source_Active
{
    const YES = 1;
    const NO = 0;

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => self::YES, 'label' => Mage::helper('petryk_action')->__('Так')),
            array('value' => self::NO, 'label' => Mage::helper('petryk_action')->__('Ні')),
        );
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            self::YES => Mage::helper('petryk_action')->__('Так'),
            self::NO => Mage::helper('petryk_action')->__('Ні'),
        );
    }
}
