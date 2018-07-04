<?php

/**
 * @category Petryk
 * @package Petryk_Action
 * @author Victor Petryk <victor.petryk@gmail.com>
 */
class Petryk_Action_Model_Source_Status
{
    const NOT_START = 1;
    const IS_VALID = 2;
    const CLOSED = 3;

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => self::NOT_START, 'label' => Mage::helper('petryk_action')->__('Не почалася')),
            array('value' => self::IS_VALID, 'label' => Mage::helper('petryk_action')->__('Діє')),
            array('value' => self::CLOSED, 'label' => Mage::helper('petryk_action')->__('Закрита')),
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
            self::NOT_START => Mage::helper('petryk_action')->__('Не почалася'),
            self::IS_VALID => Mage::helper('petryk_action')->__('Діє'),
            self::CLOSED => Mage::helper('petryk_action')->__('Закрита'),
        );
    }

}
