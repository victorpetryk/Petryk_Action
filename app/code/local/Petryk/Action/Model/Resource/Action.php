<?php

/**
 * Ресурсна модель акції
 *
 * @category Petryk
 * @package Petryk_Action
 * @author Victor Petryk <victor.petryk@gmail.com>
 */
class Petryk_Action_Model_Resource_Action extends Mage_Core_Model_Resource_Db_Abstract
{
    // Таблиця зв'язку акції з товарами
    protected $_actionProductTable;

    protected function _construct()
    {
        $this->_init('petryk_action/action', 'action_id');
        $this->_actionProductTable = $this->getTable('petryk_action/action_product');
    }

    /**
     * Виконуємо дії по збереженню/видаленню товарів при'язаних до акції
     *
     * @param Mage_Core_Model_Abstract $action
     * @return $this|Mage_Core_Model_Resource_Db_Abstract
     */
    protected function _afterSave(Mage_Core_Model_Abstract $action)
    {
        $id = $action->getId();

        // Отримуємо товари, які вибрані для прив'язки до акції
        $products = array_flip($action->getActionProducts());

        // Якщо товари не вибрані, то нічого не робимо
        if ($products === null) {
            return $this;
        }

        // Отримуємо вже збережені товари
        $oldProducts = $this->getLinkedProducts($id);

        // З'ясовуємо чи потрібно додавати товари чи видаляти
        $insert = array_diff_key($products, $oldProducts);
        $delete = array_diff_key($oldProducts, $products);

        $adapter = $this->_getWriteAdapter();

        // Видаляємо товари
        if (!empty($delete)) {
            $cond = array(
                'product_id IN(?)' => array_keys($delete),
                'action_id=?' => $id
            );

            $adapter->delete($this->_actionProductTable, $cond);
        }

        // Додаємо товари
        if (!empty($insert)) {
            $data = array();

            foreach ($insert as $key => $value) {
                $data[] = array(
                    'action_id' => (int)$id,
                    'product_id' => (int)$key
                );
            }

            $adapter->insertMultiple($this->_actionProductTable, $data);
        }

        return parent::_afterSave($action);
    }

    /**
     * Отримуємо товари, при'язані до акції
     *
     * @param $actionId
     * @return array
     */
    public function getLinkedProducts($actionId)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->_actionProductTable, array('product_id'))
            ->where('action_id = :action_id');

        $bind = array('action_id' => (int)$actionId);

        return $this->_getReadAdapter()->fetchPairs($select, $bind);
    }

    /**
     * Отримуємо акції, прив'язані до товару
     *
     * @param $productId
     * @return array
     */
    public function getLinkedActions($productId)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->_actionProductTable, array('action_id'))
            ->where('product_id = :product_id');

        $bind = array('product_id' => (int)$productId);

        return $this->_getReadAdapter()->fetchPairs($select, $bind);
    }
}
