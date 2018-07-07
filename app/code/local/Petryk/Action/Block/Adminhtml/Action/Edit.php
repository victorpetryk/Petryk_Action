<?php

/**
 * Блок редагування
 *
 * @category Petryk
 * @package Petryk_Action
 * @author Victor Petryk <victor.petryk@gmail.com>
 */
class Petryk_Action_Block_Adminhtml_Action_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Ініціалізуємо блок
     */
    public function __construct()
    {
        $this->_objectId = 'action_id';
        $this->_controller = 'adminhtml_action';
        $this->_blockGroup = 'petryk_action';

        parent::__construct();

        // Оновлюємо напис на кнопці збереження
        $this->_updateButton('save', 'label', Mage::helper('petryk_action')->__('Зберегти акцію'));

        // Оновлюємо напис на кнопці видалення
        $this->_updateButton('delete', 'label', Mage::helper('petryk_action')->__('Видалити акцію'));

        // Додаємо кнопку "Зберегти та продовжити редагувати"
        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('petryk_action')->__('Зберегти та продовжити редагувати'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
        ), -100);

        // Додаємо скрипт для кнопки "Зберегти та продовжити редагувати"
        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * Задаємо заголовок форми в залежності від того
     * редагується акція чи створюється нова
     */
    public function getHeaderText()
    {
        if (Mage::registry('petryk_action')->getId()) {
            return Mage::helper('petryk_action')->__("Редагувати акцію '%s'",
                $this->escapeHtml(Mage::registry('petryk_action')->getName()));
        } else {
            return Mage::helper('petryk_action')->__('Нова акція');
        }
    }

    /**
     * Задаємо іконку біля заголовку форми
     */
    public function getHeaderCssClass()
    {
        return 'icon-head head-cms-page';
    }
}
