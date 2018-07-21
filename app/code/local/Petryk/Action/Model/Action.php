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

    protected function _afterLoad()
    {
        parent::_afterLoad();

        $formattedStartDatetime = Mage::helper('core')->formatDate($this->getData('start_datetime'), 'short', true);
        $this->setData('start_datetime', $formattedStartDatetime);

        if ($this->getData('end_datetime')) {
            $formattedEndDatetime = Mage::helper('core')->formatDate($this->getData('end_datetime'), 'short', true);

            $this->setData('end_datetime', $formattedEndDatetime);
        }
    }

    protected function _beforeDelete()
    {
        $id = $this->getId();
        $model = $this->load($id);

        // Видаляємо зображення перед видаленням акції
        Mage::helper('petryk_action')->deleteImage($model->getImage());
        Mage::helper('petryk_action')->deleteImage($model->getImage(), '50x50');
        Mage::helper('petryk_action')->deleteImage($model->getImage(), '200x200');
        Mage::helper('petryk_action')->deleteImage($model->getImage(), '300x300');

        return parent::_beforeDelete();
    }
}
