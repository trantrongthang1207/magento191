<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Gayan
 * Date: 7/30/13
 * Time: 8:40 AM
 * To change this template use File | Settings | File Templates.
 */ 
class Netstarter_Seo_Model_Catalog_Category_Attribute_Backend_Image extends Mage_Catalog_Model_Category_Attribute_Backend_Image {

    public function afterSave($object)
    {
        $value = $object->getData($this->getAttribute()->getName());

        if (is_array($value) && !empty($value['delete'])) {
            $object->setData($this->getAttribute()->getName(), '');
            $this->getAttribute()->getEntity()
                ->saveAttribute($object, $this->getAttribute()->getName());
            return;
        }

        /* Workaround to avoid exception '$_FILES array is empty' when saving
         * category or creating a category with the API.
         * Inspired by http://www.magentocommerce.com/bug-tracking/issue/?issue=11597
         */

        if (!isset($_FILES) || count($_FILES) == 0)
        {
            return;
        }
        $path = Mage::getBaseDir('media') . DS . 'catalog' . DS . 'category' . DS;

        try {
            $uploader = new Mage_Core_Model_File_Uploader($this->getAttribute()->getName());
            $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
            $uploader->setAllowRenameFiles(true);
            $uploader->addValidateCallback(
                Mage_Core_Model_File_Validator_Image::NAME,
                new Mage_Core_Model_File_Validator_Image(),
                "validate"
            );
            $result = $uploader->save($path);

            $object->setData($this->getAttribute()->getName(), $result['file']);
            $this->getAttribute()->getEntity()->saveAttribute($object, $this->getAttribute()->getName());
        } catch (Exception $e) {
            if ($e->getCode() != Mage_Core_Model_File_Uploader::TMP_NAME_EMPTY) {
                Mage::logException($e);
            }
            /** @TODO ??? */
            return;
        }
    }
}
