<?php

/**
 * Контролер адміністративної частини керування акціями
 *
 * @category Petryk
 * @package Petryk_Action
 * @author Victor Petryk <victor.petryk@gmail.com>
 */
class Petryk_Action_Adminhtml_Petryk_ActionController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Відображення гріду з акціями
     */
    public function indexAction()
    {
        // Задаємо заголовок сторінки
        $this->_title($this->__('Акції'))
            ->_title($this->__('Керування акціями'));

        $this->loadLayout();

        // Визначаємо який пункт меню буде активним
        $this->_setActiveMenu('promo/petryk_action');

        $this->renderLayout();
    }

    /**
     * Відображення форми створення нової акції
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * Відображення форми редагування акції
     */
    public function editAction()
    {
        $this->_title($this->__('Акції'))
            ->_title($this->__('Керування акціями'));

        // Визначаємо ID акції з запиту
        $id = $this->getRequest()->getParam('action_id');

        $model = Mage::getModel('petryk_action/action');

        // Якщо є ID, то завантажуємо відповідну акцію
        if ($id) {
            $model->load($id);

            // Якщо немає акції з таким ID, виводимо повідомлення про помилку
            // та робимо редирект на сторінку з грідом
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('petryk_action')->__('Ця акція більше не існує.'));

                $this->_redirect('*/*/');

                return;
            }
        }

        // В залежності від того існує акція чи ні, виводимо різні значення в заголовок
        $this->_title($model->getId() ? $model->getName() : $this->__('Нова акція'));

        // Беремо з сесії дані форми
        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);

        // Якщо дані з сесії не порожні, то встановлюємо їх для модель
        if (!empty($data)) {
            $model->setData($data);
        }

        // Записуємо модель в реєстр
        Mage::register('petryk_action', $model);

        $this->loadLayout();

        $this->_setActiveMenu('promo/petryk_action');

        $this->renderLayout();
    }

    /**
     * Збереження даних, що введені в форму редагування/створення акції
     */
    public function saveAction()
    {
        $id = $this->getRequest()->getParam('action_id');

        // Беремо дані з POST-запиту
        $data = $this->getRequest()->getPost();

        if ($data) {
            $model = Mage::getModel('petryk_action/action');

            if ($id) {
                $model->load($id);
            }

            // Встановлюємо дані для моделі
            $model->setData($data);

            // Якщо зображення вже завантажене, то беремо попереднє значення поля image
            if (isset($data['image']['value']) && !isset($data['image']['delete'])) {
                $model->setData('image', $data['image']['value']);
            }

            // Беремо прив'язані товари та декодуємо в правильний формат
            if (isset($data['links']['products'])) {
                $model->setData('action_products',
                    Mage::helper('adminhtml/js')->decodeGridSerializedInput($data['links']['products']));
            }

            // Завантажуємо зображення
            Mage::helper('petryk_action')->uploadImage('image', $model);

            // Намагаємося зберегти дані в базу даних
            try {
                // Зберігаємо дані
                $model->save();

                // Виводимо успішне повідомлення
                $this->_getSession()->addSuccess(Mage::helper('petryk_action')->__('Акція збережена.'));

                // Чистимо збережені дані з сесії
                $this->_getSession()->setFormData(false);

                // Перевіряємо чи не було натиснуто кнопку "Зберегти і продовжити редагувати"
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('action_id' => $model->getId(), '_current' => true));

                    return;
                }

                // Редирект на сторінку з грідом
                $this->_redirect('*/*/');

                return;

                // Відловлюємо помилки і відображаємо їх
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addException($e,
                    Mage::helper('petryk_action')->__('Під час збереження акції сталася помилка.'));
            }

            // Зберігаємо дані з форми в сесію
            $this->_getSession()->setFormData($data);

            $this->_redirect('*/*/edit', array('action_id' => $this->getRequest()->getParam('action_id')));

            return;
        }

        $this->_redirect('*/*/');
    }

    /**
     * Видалення акції
     */
    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('action_id');

        if ($id) {
            try {
                $model = Mage::getModel('petryk_action/action');
                $model->setId($id)->delete();

                $this->_getSession()->addSuccess(Mage::helper('petryk_action')->__('Акція була видалена.'));

                $this->_redirect('*/*/');

                return;

            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());

                $this->_redirect('*/*/edit', array('action_id' => $id));

                return;
            }
        }

        $this->_getSession()->addError(Mage::helper('petryk_action')->__('Не вдається знайти акцію для видалення.'));

        $this->_redirect('*/*/');
    }

    /**
     * Масове видалення акцій
     */
    public function massDeleteAction()
    {
        $actionIds = $this->getRequest()->getParam('action_ids');

        if (!is_array($actionIds)) {
            $this->_getSession()->addError($this->__('Будь-ласка виберіть акцію(ї).'));
        } else {
            if (!empty($actionIds)) {
                try {
                    foreach ($actionIds as $actionId) {
                        $action = Mage::getSingleton('petryk_action/action')->load($actionId);
                        $action->delete();
                    }

                    $this->_getSession()->addSuccess(
                        $this->__('Всього було видалено %d акцій.', count($actionIds))
                    );

                } catch (Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                }
            }
        }

        $this->_redirect('*/*/');
    }

    /**
     * Вивід гріда акцій для ajax-запиту
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('petryk_action/adminhtml_action_grid')->toHtml()
        );
    }

    /**
     * Вивід гріда товарів в табі
     */
    public function productsAction()
    {
        $this->loadLayout();

        $this->getLayout()->getBlock('petryk_action.tab.products')
            ->setActionProducts($this->getRequest()->getPost('action_products', null));

        $this->renderLayout();
    }

    /**
     * Вивід гріда товарів для ajax-запиту
     */
    public function productsGridAction()
    {
        $this->loadLayout();

        $this->getLayout()->getBlock('petryk_action.tab.products')
            ->setActionProducts($this->getRequest()->getPost('action_products', null));

        $this->renderLayout();
    }
}
