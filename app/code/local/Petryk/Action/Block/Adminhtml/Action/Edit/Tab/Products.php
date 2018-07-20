<?php

/**
 * Таба з грідом товарів
 *
 * @category Petryk
 * @package Petryk_Action
 * @author Victor Petryk <victor.petryk@gmail.com>
 */
class Petryk_Action_Block_Adminhtml_Action_Edit_Tab_Products extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('actionProductsGrid');
        $this->setDefaultSort('product_entity_id');
        $this->setSaveParametersInSession(false);
        $this->setUseAjax(true);

        if ($this->_getAction()->getId()) {
            $this->setDefaultFilter(array('in_action' => 1));
        }
    }

    /**
     * Отримуємо модель акції
     *
     * @return mixed
     * @throws Mage_Core_Exception
     */
    protected function _getAction()
    {
        if (!Mage::registry('current_action')) {
            $current_action = Mage::getModel('petryk_action/action')
                ->load($this->getRequest()->getParam('action_id'));

            Mage::register('current_action', $current_action);
        }

        return Mage::registry('current_action');
    }

    /**
     * Фільтрація колекції
     *
     * @param $column
     * @return $this|Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_action') {
            $productIds = $this->_getSelectedProducts();

            if (empty($productIds)) {
                $productIds = 0;
            }

            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in' => $productIds));
            } else {
                if ($productIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin' => $productIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    /**
     * Визначаємо колекцію для гріду
     */
    protected function _prepareCollection()
    {
        // Колекція вебсайтів
        $websiteModel = Mage::getModel('core/website')->getCollection();

        // Оримуємо ідентифікатор вебсайту (потрібно для виключення товарів, які не прив'язані до жодного сайту)
        $websiteId = $websiteModel->getFirstItem()->getId();

        $collection = Mage::getModel('catalog/product')
            ->getCollection()
            ->addAttributeToSelect('entity_id')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('type')
            ->addAttributeToSelect('status')
            ->addAttributeToSelect('visibility')
            ->addAttributeToSelect('sku')
            ->addWebsiteFilter($websiteId)
            ->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
            ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Визначаємо колонки для гріду
     */
    protected function _prepareColumns()
    {
        $this->addColumn('in_action', array(
            'header_css_class' => 'a-center',
            'type' => 'checkbox',
            'name' => 'in_action',
            'values' => $this->_getSelectedProducts(),
            'align' => 'center',
            'index' => 'entity_id'
        ));

        $this->addColumn('product_entity_id', array(
            'header' => Mage::helper('catalog')->__('ID'),
            'width' => '50px',
            'type' => 'number',
            'index' => 'entity_id',
        ));

        $this->addColumn('product_name', array(
            'header' => Mage::helper('catalog')->__('Name'),
            'index' => 'name',
        ));

        $this->addColumn('product_type', array(
            'header' => Mage::helper('catalog')->__('Type'),
            'width' => '60px',
            'index' => 'type_id',
            'type' => 'options',
            'options' => Mage::getSingleton('catalog/product_type')->getOptionArray(),
        ));

        $this->addColumn('product_status', array(
            'header' => Mage::helper('catalog')->__('Status'),
            'width' => '70px',
            'index' => 'status',
            'type' => 'options',
            'options' => Mage::getSingleton('catalog/product_status')->getOptionArray(),
        ));

        $this->addColumn('product_visibility', array(
            'header' => Mage::helper('catalog')->__('Visibility'),
            'width' => '70px',
            'index' => 'visibility',
            'type' => 'options',
            'options' => Mage::getModel('catalog/product_visibility')->getOptionArray(),
        ));

        $this->addColumn('product_sku', array(
            'header' => Mage::helper('catalog')->__('SKU'),
            'width' => '80px',
            'index' => 'sku',
        ));

        return parent::_prepareColumns();
    }

    /**
     * Отримуємо посилання гріду для ajax-запиту
     *
     * @return mixed|string
     */
    public function getGridUrl()
    {
        return $this->getData('grid_url')
            ? $this->getData('grid_url')
            : $this->getUrl('*/*/productsGrid', array('_current' => true));
    }

    /**
     * Отримуємо вибрані товари (для фільтрації в гріді)
     *
     * @return array
     */
    protected function _getSelectedProducts()
    {
        $products = $this->getActionProducts();

        if (!is_array($products)) {
            $products = array_values($this->getSelectedActionProducts());
        }

        return $products;
    }

    /**
     * @return array
     * @throws Mage_Core_Exception
     */
    public function getSelectedActionProducts()
    {
        $id = $this->_getAction()->getId();

        $products = array();

        foreach ($this->_getAction()->getResource()->getLinkedProducts($id) as $key => $value) {
            $products[] = $key;
        }

        return $products;
    }
}
