<?php

/**
 * Блок таби "Загальна"
 *
 * @category Petryk
 * @package Petryk_Action
 * @author Victor Petryk <victor.petryk@gmail.com>
 */
class Petryk_Action_Block_Adminhtml_Action_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Готуємо форму для редагування/створення акції
     */
    protected function _prepareForm()
    {
        // Отримуємо модель з регістру
        $model = Mage::registry('petryk_action');

        // Визначаємо опції для select-ів
        $statusSource = Mage::getModel('petryk_action/source_status')->toOptionArray();
        $activeSource = Mage::getModel('petryk_action/source_active')->toOptionArray();

        // Задаємо формат дати
        $dateFormat = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);

        // Створюємо форму
        $form = new Varien_Data_Form();

        // Додаємо до форми fieldset
        $fieldset = $form->addFieldset('base_fieldset',
            array('legend' => Mage::helper('petryk_action')->__('Загальна')));

        // Якщо існує ID, то додаємо до форми прихований input з цим ID
        if ($model->getActionId()) {
            $fieldset->addField('action_id', 'hidden', array(
                'name' => 'action_id',
            ));
        }

        $fieldset->addField('name', 'text', array(
            'name' => 'name',
            'label' => Mage::helper('petryk_action')->__('Назва'),
            'title' => Mage::helper('petryk_action')->__('Назва'),
            'required' => true,
        ));

        $fieldset->addField('is_active', 'select', array(
            'name' => 'is_active',
            'label' => Mage::helper('petryk_action')->__('Активна'),
            'title' => Mage::helper('petryk_action')->__('Активна'),
            'values' => $activeSource,
        ));

        $fieldset->addField('status', 'select', array(
            'name' => 'status',
            'label' => Mage::helper('petryk_action')->__('Статус'),
            'title' => Mage::helper('petryk_action')->__('Статус'),
            'values' => $statusSource,
            'disabled' => true,
        ));

        $fieldset->addField('start_datetime', 'date', array(
            'name' => 'start_datetime',
            'label' => Mage::helper('petryk_action')->__('Дата початку'),
            'title' => Mage::helper('petryk_action')->__('Дата початку'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATE_INTERNAL_FORMAT,
            'format' => $dateFormat,
            'required' => true,
        ));

        $fieldset->addField('end_datetime', 'date', array(
            'name' => 'end_datetime',
            'label' => Mage::helper('petryk_action')->__('Дата закінчення'),
            'title' => Mage::helper('petryk_action')->__('Дата закінчення'),
            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATE_INTERNAL_FORMAT,
            'format' => $dateFormat,
        ));

        // Задаємо свій рендерер для відображення зображення у формі
        $fieldset->addType('image', 'Petryk_Action_Block_Adminhtml_Action_Edit_Renderer_Image');

        $fieldset->addField('image', 'image', array(
            'name' => 'image',
            'label' => Mage::helper('petryk_action')->__('Зображення'),
            'title' => Mage::helper('petryk_action')->__('Зображення'),
        ));

        $fieldset->addField('short_description', 'textarea', array(
            'name' => 'short_description',
            'label' => Mage::helper('petryk_action')->__('Короткий опис'),
            'title' => Mage::helper('petryk_action')->__('Короткий опис'),
        ));

        $fieldset->addField('description', 'textarea', array(
            'name' => 'description',
            'label' => Mage::helper('petryk_action')->__('Опис'),
            'title' => Mage::helper('petryk_action')->__('Опис'),
        ));

        $form->setValues($model->getData());

        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Повертаємо мітку таби
     */
    public function getTabLabel()
    {
        return Mage::helper('petryk_action')->__('Загальна');
    }

    /**
     * Повертаємо назву таби
     */
    public function getTabTitle()
    {
        return Mage::helper('petryk_action')->__('Загальна');
    }

    /**
     * Таба відображається
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Таба не прихована
     */
    public function isHidden()
    {
        return false;
    }
}
