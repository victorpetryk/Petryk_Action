<?php

/**
 * Модель акції
 *
 * @category Petryk
 * @package Petryk_Action
 * @author Victor Petryk <victor.petryk@gmail.com>
 */
class Petryk_Action_Model_Action extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('petryk_action/action');
    }
}
