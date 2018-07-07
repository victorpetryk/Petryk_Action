<?php

/**
 * Рендерер для відображення зображення в гріді
 *
 * @category Petryk
 * @package Petryk_Action
 * @author Victor Petryk <victor.petryk@gmail.com>
 */
class Petryk_Action_Block_Adminhtml_Action_Grid_Renderer_Image
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $imageFile = $row->getImage();

        if (!$imageFile) {
            $imageSource = Mage::helper('petryk_action')->getImageFile('placeholder.jpg', '50x50', true);
        } else {
            $imageSource = Mage::helper('petryk_action')->getImageFile($imageFile, '50x50', true);
        }

        $imageHTML = "<img src='{$imageSource}' width='50' height='50' alt='' style='margin-top: 5px; border: 1px solid #dadfe0;' />";

        return $imageHTML;
    }
}
