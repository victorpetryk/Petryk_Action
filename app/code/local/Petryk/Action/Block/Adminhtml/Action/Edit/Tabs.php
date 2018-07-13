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

    public function _beforeToHtml()
    {
        // Таба з формою редагування/створення акції
        $this->addTab('action_main_tab', 'petryk_action/adminhtml_action_edit_tab_general');

        // Таба з товарами
        $this->addTab('action_products_tab', array(
            'label' => Mage::helper('petryk_action')->__('Товари'),
            'url' => $this->getUrl('*/*/products', array('_current' => true)),
            'class' => 'ajax'
        ));

        return parent::_beforeToHtml();
    }
}
