<?php

/**
 * Хелпер
 *
 * @category Petryk
 * @package Petryk_Action
 * @author Victor Petryk <victor.petryk@gmail.com>
 */
class Petryk_Action_Helper_Data extends Mage_Core_Helper_Abstract
{
    // Шлях до директорії з зображеннями
    protected $_imageFolderPath = 'petryk' . DS . 'action' . DS;

    /*
     * Повертаємо шлях до директорії з зображеннями
     * враховуючи директорію media
     */
    public function getImageFolderPath()
    {
        return Mage::getBaseDir('media') . DS . $this->_imageFolderPath;
    }

    /**
     * Повертаємо url до директорії з зображеннями
     */
    public function getImageFolderUrl()
    {
        return Mage::getBaseUrl('media') . $this->_imageFolderPath;
    }

    /**
     * Повертаємо шлях або url до зображення
     *
     * @param $imageFile
     * @param string $size (50x50, 300x300, full)
     * @param false $url
     * @return string
     */
    public function getImageFile($imageFile, $size = 'full', $url = false)
    {
        // Визначаємо, повертати шлях до директорії чи url
        if (!$url) {
            $path = $this->getImageFolderPath();
        } else {
            $path = $this->getImageFolderUrl();
        }

        if ($size !== 'full') {
            $imageFileChunks = explode('.', $imageFile);

            return $path . $imageFileChunks['0'] . '_' . $size . '.' . $imageFileChunks['1'];
        }

        return $path . $imageFile;
    }

    /**
     * Завантажуємо зображення
     *
     * @param $fieldName
     * @param $model
     */
    public function uploadImage($fieldName, $model)
    {
        if (isset($_FILES[$fieldName]['name']) and (file_exists($_FILES[$fieldName]['tmp_name']))) {
            try {
                /* @var $uploader Mage_Core_Model_File_Uploader */
                $uploader = new Mage_Core_Model_File_Uploader($fieldName);
                $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(false);

                $uploader->save($this->getImageFolderPath(), $_FILES[$fieldName]['name']);

                // Створюємо різні розміри зображень
                $this->_resizeImage($uploader->getUploadedFileName());
                $this->_resizeImage($uploader->getUploadedFileName(), 200, 200);
                $this->_resizeImage($uploader->getUploadedFileName(), 300, 300);

                $model->setData($fieldName, $uploader->getUploadedFileName());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addException($e,
                    $this->__('Під час завантаження зображення виникла помилка.'));
            }
        } else {
            if ($model->getData($fieldName . '/delete') && $model->getData($fieldName . '/delete') == 1) {
                $imageFile = $model->getData($fieldName)['value'];

                // Видаляємо всі розміри зображень
                $this->deleteImage($imageFile);
                $this->deleteImage($imageFile, '50x50');
                $this->deleteImage($imageFile, '200x200');
                $this->deleteImage($imageFile, '300x300');

                $model->setData($fieldName, '');
            }
        }
    }

    /**
     * Змінюємо розмір зображення
     *
     * @param $imageFile
     * @param int $width
     * @param int $height
     */
    protected function _resizeImage($imageName, $width = 50, $height = 50)
    {
        $path = $this->getImageFolderPath();

        $imageNameChunks = explode('.', $imageName);
        $resizedImageName = $imageNameChunks['0'] . '_' . $width . 'x' . $height . '.' . $imageNameChunks['1'];

        $image = new Varien_Image($path . $imageName);
        $image->constrainOnly(true);
        $image->keepAspectRatio(true);
        $image->keepFrame(true);
        $image->backgroundColor(array(255, 255, 255));
        $image->quality(100);
        $image->resize($width, $height);

        $image->save($path . $resizedImageName);
    }

    /**
     * Видаляємо зображення
     *
     * @param $imageFile
     * @param string $size
     */
    public function deleteImage($imageFile, $size = 'full')
    {
        $imageFilePath = $this->getImageFile($imageFile, $size);

        if (file_exists($imageFilePath)) {
            unlink($imageFilePath);
        }
    }
}
