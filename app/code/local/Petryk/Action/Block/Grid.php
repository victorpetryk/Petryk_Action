<?php

/**
 * Блок гріду з акціями
 *
 * @category Petryk
 * @package Petryk_Action
 * @author Victor Petryk <victor.petryk@gmail.com>
 */
class Petryk_Action_Block_Grid extends Mage_Core_Block_Template
{
    /**
     * Отримуємо акції, що прив'язані до товару
     */
    public function getActions()
    {
        $productId = $this->getRequest()->getParam('id');
        $actionIds = array_keys(Mage::getResourceModel('petryk_action/action')->getLinkedActions($productId));

        $isActive = Mage::getSingleton('petryk_action/source_active');

        $actionsCollection = Mage::getModel('petryk_action/action')->getCollection()
            ->addFieldToFilter('is_active', array('eq' => $isActive::YES))
            ->addFieldToFilter('action_id', array('in' => $actionIds));

        return $actionsCollection;
    }

    /**
     * Отримуємо посилання для перегляду акції
     *
     * @param $actionId
     * @return string
     */
    public function getActionUrl($actionId)
    {
        return $this->getUrl('petryk_action/index/view', array('id' => $actionId));
    }
}
