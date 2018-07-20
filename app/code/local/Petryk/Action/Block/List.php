<?php

/**
 * Блок списку акцій
 *
 * @category Petryk
 * @package Petryk_Action
 * @author Victor Petryk <victor.petryk@gmail.com>
 */
class Petryk_Action_Block_List extends Mage_Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();

        $isActive = Mage::getSingleton('petryk_action/source_active');

        $actionsCollection = Mage::getModel('petryk_action/action')->getCollection()
            ->addFieldToFilter('is_active', array('eq' => $isActive::YES));

        $this->setCollection($actionsCollection);
    }

    protected function _prepareLayout()
    {
        // Блок тулбару
        $toolbar = Mage::getBlockSingleton('catalog/product_list')->getToolbarBlock();

        // Блок для відобрадення посторінкової навігації
        $pager = $this->getLayout()->createBlock('page/html_pager');

        $toolbar->setAvailableOrders(array(
            'start_datetime' => 'Початок',
            'end_datetime' => 'Кінець'
        ));

        $toolbar->setDefaultOrder('start_datetime');
        $toolbar->setDefaultDirection('desc');
        $toolbar->disableViewSwitcher();
        $toolbar->setCollection($this->getCollection());

        $toolbar->setChild('product_list_toolbar_pager', $pager);

        $this->setChild('toolbar', $toolbar);

        $this->getCollection()->load();

        return parent::_prepareLayout();
    }

    /**
     * Відображаємо тулбар в шаблоні
     *
     * @return string
     */
    public function getToolbarHtml()
    {
        return $this->getChildHtml('toolbar');
    }

    /**
     * Отримуємо посилання для перегляду акції
     *
     * @param $actionId
     * @return string
     */
    public function getActionUrl($actionId)
    {
        return $this->getUrl('*/*/view', array('id' => $actionId));
    }
}
