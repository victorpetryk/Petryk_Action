<?php

/**
 * Контейнер для гріду
 *
 * @category Petryk
 * @package Petryk_Action
 * @author Victor Petryk <victor.petryk@gmail.com>
 */
class Petryk_Action_Block_Adminhtml_Action extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'petryk_action';
        $this->_controller = 'adminhtml_action';

        // Заголовок контейнеру
        $this->_headerText = Mage::helper('petryk_action')->__('Керування акціями');

        // Назва кнопки додавання нової акції
        $this->_addButtonLabel = Mage::helper('petryk_action')->__('Додати нову акцію');

        parent::__construct();
    }

    /**
     * Виводимо css-клас для іконки заголовку контейнеру
     */
    public function getHeaderCssClass()
    {
        return 'icon-head head-cms-page';
    }
}
