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
}
