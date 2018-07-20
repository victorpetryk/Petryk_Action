<?php

/**
 * Блок акції
 *
 * @category Petryk
 * @package Petryk_Action
 * @author Victor Petryk <victor.petryk@gmail.com>
 */
class Petryk_Action_Block_View extends Mage_Core_Block_Template
{
    /**
     * Повертає акцію
     */
    public function getAction()
    {
        return Mage::registry('petryk_viewed_action');
    }

    /**
     * Повертає товари, прив'язані до акції
     */
    public function getProducts()
    {
        $actionId = $this->getAction()->getId();

        $productsIds = array_keys(Mage::getResourceModel('petryk_action/action')->getLinkedProducts($actionId));

        $products = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('entity_id')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('small_image')
            ->addAttributeToSelect('price')
            ->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
            ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
            ->addFieldToFilter('entity_id', array('in' => $productsIds));

        return $products;
    }
}
