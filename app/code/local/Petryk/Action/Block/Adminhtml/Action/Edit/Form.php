<?php

/**
 * Блок форми
 *
 * @category Petryk
 * @package Petryk_Action
 * @author Victor Petryk <victor.petryk@gmail.com>
 */
class Petryk_Action_Block_Adminhtml_Action_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'html_id_prefix' => 'action_',
            'use_container' => true,
            'action' => $this->getUrl('*/*/save', array('action_id' => $this->getRequest()->getParam('action_id'))),
            'method' => 'post',
            'enctype' => 'multipart/form-data',
        ));

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
