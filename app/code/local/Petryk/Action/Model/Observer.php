<?php

/**
 * Спостерігач
 *
 * @category Petryk
 * @package Petryk_Action
 * @author Victor Petryk <victor.petryk@gmail.com>
 */
class Petryk_Action_Model_Observer
{
    /**
     * Додаємо пункт меню
     */
    public function addLinkToTopmenu(Varien_Event_Observer $observer)
    {
        $menu = $observer->getMenu();
        $tree = $menu->getTree();
        $linkNode = new Varien_Data_Tree_Node(array(
            'name' => Mage::helper('petryk_action')->__('Акції'),
            'id' => 'petryk_action',
            'url' => Mage::getUrl('petryk_action/index/list')
        ), 'id', $tree, $menu);
        $menu->addChild($linkNode);
    }

    /**
     * Змінюємо статус акції по крону
     */
    public function changeActionStatus()
    {
        $isActive = Mage::getSingleton('petryk_action/source_active');
        $status = Mage::getSingleton('petryk_action/source_status');

        // Вибираємо акції, що повинні стартувати
        $startActions = Mage::getModel('petryk_action/action')->getCollection()
            ->addFieldToFilter('is_active', array('eq' => $isActive::YES))
            ->addFieldToFilter('status', array('eq' => $status::NOT_START))
            ->addFieldToFilter('start_datetime', array('to' => Mage::getModel('core/date')->gmtDate()));

        // Змінюємо статус акцій, що стартують
        foreach ($startActions as $startAction) {
            $startAction->setStatus($status::IS_VALID)
                ->save();
        }

        // Вибираємо акції, що повинні закінчуватися
        $endActions = Mage::getModel('petryk_action/action')->getCollection()
            ->addFieldToFilter('is_active', array('eq' => $isActive::YES))
            ->addFieldToFilter('status', array('eq' => $status::IS_VALID))
            ->addFieldToFilter('end_datetime', array(
                'to' => Mage::getModel('core/date')->gmtDate(),
                'notnull' => '',
            ));

        // Змінюємо статус та актисність акцій, що закінчуються
        foreach ($endActions as $endAction) {
            $endAction->setStatus($status::CLOSED)
                ->setIsActive($isActive::NO)
                ->save();
        }

    }
}
