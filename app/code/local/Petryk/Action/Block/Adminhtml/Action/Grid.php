<?php

/**
 * Грід
 *
 * @category Petryk
 * @package Petryk_Action
 * @author Victor Petryk <victor.petryk@gmail.com>
 */
class Petryk_Action_Block_Adminhtml_Action_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('actionGrid');
        $this->setDefaultSort('action_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * Визначаємо колекцію для гріду
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('petryk_action/action')->getCollection();

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Визначаємо колонки для гріду
     */
    protected function _prepareColumns()
    {
        // Визначаємо source для активності та статусу акції та використання значень у select-ах
        $statusSource = Mage::getModel('petryk_action/source_status')->toArray();
        $activeSource = Mage::getModel('petryk_action/source_active')->toArray();

        // Задаємо формат дати
        $dateTimeFormat = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);

        $this->addColumn('action_id', array(
            'header' => Mage::helper('petryk_action')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'type' => 'number',
            'index' => 'action_id',
        ));

        $this->addColumn('image', array(
            'header' => Mage::helper('petryk_action')->__('Зображення'),
            'align' => 'center',
            'width' => '60px',
            'renderer' => Mage::getBlockSingleton('petryk_action/adminhtml_action_grid_renderer_image'),
            'index' => 'image',
            'filter' => false,
            'sortable' => false,
        ));

        $this->addColumn('name', array(
            'header' => Mage::helper('petryk_action')->__('Назва'),
            'align' => 'left',
            'type' => 'text',
            'index' => 'name',
        ));

        $this->addColumn('short_description', array(
            'header' => Mage::helper('petryk_action')->__('Короткий опис'),
            'align' => 'left',
            'type' => 'text',
            'index' => 'short_description',
            'truncate' => 120,
            'escape' => true,
        ));

        $this->addColumn('is_active', array(
            'header' => Mage::helper('petryk_action')->__('Активна'),
            'align' => 'left',
            'width' => '70px',
            'type' => 'options',
            'index' => 'is_active',
            'options' => $activeSource,
        ));

        $this->addColumn('status', array(
            'header' => Mage::helper('petryk_action')->__('Статус'),
            'align' => 'left',
            'width' => '130px',
            'type' => 'options',
            'index' => 'status',
            'options' => $statusSource,
        ));

        $this->addColumn('start_datetime', array(
            'header' => Mage::helper('petryk_action')->__('Дата початку'),
            'type' => 'datetime',
            'format' => $dateTimeFormat,
            'index' => 'start_datetime',
        ));

        $this->addColumn('end_datetime', array(
            'header' => Mage::helper('petryk_action')->__('Дата закінчення'),
            'type' => 'datetime',
            'format' => $dateTimeFormat,
            'index' => 'end_datetime',
        ));

        return parent::_prepareColumns();
    }

    /**
     * Налаштовуємо масове видалення в гріді
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('action_id');
        $this->getMassactionBlock()->setFormFieldName('action_ids');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('petryk_action')->__('Видалити'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('petryk_action')->__('Ви впевнені?'),
        ));

        parent::_prepareMassaction();
    }

    /**
     * Визначаємо url для рядку в гріді (перехід на редагування акції при натисканні на елемент акції в гріді)
     */
    public function getRowUrl($action)
    {
        return $this->getUrl('*/*/edit', array('action_id' => $action->getId()));
    }

    /**
     * Визначаємо url для ajax-запиту на грід
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }
}
