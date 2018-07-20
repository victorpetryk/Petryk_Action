<?php

/**
 * Контролер акцій
 *
 * @category Petryk
 * @package Petryk_Action
 * @author Victor Petryk <victor.petryk@gmail.com>
 */
class Petryk_Action_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->listAction();
    }

    /**
     * Відображення списку акцій
     */
    public function listAction()
    {
        $this->_title('Акції');

        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Відобрадення акції
     */
    public function viewAction()
    {
        $actionId = $this->getRequest()->getParam('id');

        $viewedActionModel = Mage::getModel('petryk_action/action')->load($actionId);

        if (!$viewedActionModel->getId()) {
            $this->_forward('noRoute');
        } else {
            Mage::register('petryk_viewed_action', $viewedActionModel);

            $this->_title('Акції')->_title(Mage::registry('petryk_viewed_action')->getName());

            $this->loadLayout();
            $this->renderLayout();
        }
    }
}
