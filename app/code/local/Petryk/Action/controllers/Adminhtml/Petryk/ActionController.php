<?php
/**
 * Action manage promotions controller
 *
 * @category Petryk
 * @package Petryk_Action
 * @author Victor Petryk <victor.petryk@gmail.com>
 */
class Petryk_Action_Adminhtml_Petryk_ActionController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_title($this->__('Promotions'))
            ->_title(Mage::helper('petryk_action')->__('Manage Promotions'));

        $this->loadLayout();

        $this->_setActiveMenu('promo/petryk_action');
        $this->_addBreadcrumb(Mage::helper('petryk_action')->__('Manage Promotions'), Mage::helper('petryk_action')->__('Manage Promotions'));

        $this->renderLayout();
    }
}
