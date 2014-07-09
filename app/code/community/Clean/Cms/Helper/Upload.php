<?php

class Clean_Cms_Helper_Upload extends Mage_Core_Helper_Abstract
{
    public function saveUploadedFile($fieldName)
    {
        if (!isset($_FILES['cleancms']['name'][$fieldName])) {
            throw new Exception("Wasn't able to find this file field in _FILES: $fieldName");
        }

        $uploadedFilePath = $this->_moveUploadedFile($fieldName);
        return $uploadedFilePath;
    }

    public function safeFileName($fieldName)
    {
        $fileName = $_FILES['cleancms']['name'][$fieldName];
        $fileExtension = $this->_getFileExtension($fileName);
        $fileNameWithoutExtension = $this->_removeFileExtension($fileName);
        $fileName = str_replace(' ', '-', $fileNameWithoutExtension) . '.' . $fileExtension;

        return $fileName;
    }

    protected function _getUniqueFileName($fieldName)
    {
        $fileName = $this->safeFileName($fieldName);
        if (file_exists($this->_getAbsolutePath($fileName))) {
            $origFileName = $fileName;
            $i = 1;
            while (file_exists($this->_getAbsolutePath($fileName))) {
                $origFileNameWithoutExtension = $this->_removeFileExtension($origFileName);
                $fileName = $origFileNameWithoutExtension . "-" . $i . "." . $this->_getFileExtension($origFileName);
                $i++;
            }
        }

        return $fileName;
    }

    protected function _getAbsolutePath($relativePath)
    {
        return $this->_getDirectory() . DS . $relativePath;
    }

    protected function _removeFileExtension($filePath)
    {
        $fileExtension = $this->_getFileExtension($filePath);
        $fileNameTrimmed = rtrim(rtrim($filePath, $fileExtension), '.');

        return $fileNameTrimmed;
    }

    protected function _getFileExtension($filePath)
    {
        $fileExtension = strtolower(substr(strrchr($filePath, "."), 1));
        return $fileExtension;
    }

    protected function _getDirectory()
    {
        $path = Mage::getBaseDir('media') . DS . 'cleancms';

        if(!is_dir($path)){
            mkdir($path, 0777, true);
        }

        return $path;
    }

    protected function _moveUploadedFile($fieldName)
    {
        if (! isset($_FILES['cleancms']['tmp_name'][$fieldName])) {
            throw new Exception("Wasn't able to find tmp_name for $fieldName");
        }

        $tmpName = $_FILES['cleancms']['tmp_name'][$fieldName];
        $fileName = $this->_getUniqueFileName($fieldName);
        $result = move_uploaded_file($tmpName, $this->_getAbsolutePath($fileName));
        if (! $result) {
            throw new Exception("There was an error uploading the file");
        }

        return $fileName;
    }

    public function url($fileName)
    {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . "cleancms/$fileName";
    }
}