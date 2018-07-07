<?php

/**
 * Блок з табами
 *
 * @category Petryk
 * @package Petryk_Action
 * @author Victor Petryk <victor.petryk@gmail.com>
 */
class Petryk_Action_Block_Adminhtml_Action_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Ініціалізуємо блок
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('action_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('petryk_action')->__('Інформація про акцію'));
    }

    public function _prepareLayout()
    {
        /**
         * Додаємо таби
         */
        $this->addTab('action_main_tab', 'petryk_action/adminhtml_action_edit_tab_general');
        //$this->addTab('action_product_tab', 'petryk_action/adminhtml_action_edit_tab_product');

        return parent::_prepareLayout();
    }
}
