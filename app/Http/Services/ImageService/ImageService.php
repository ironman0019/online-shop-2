<?php

namespace App\Http\Services\ImageService;

use Intervention\Image\Laravel\Facades\Image;

class ImageService extends ImageToolsService {


    public function save($image)
    {   
        // set image
        $this->setImage($image);
        
        // execute provider
        $this->provider();

        // save image
        $result = Image::read($image->getPathname())->save(public_path($this->getImageAddress()), format: $this->getImageFormat());
        return $result ? $this->getImageAddress() : false;
    }


    public function fitAndSave($image, $width, $height)
    {
        // set image
        $this->setImage($image);
        
        // execute provider
        $this->provider();

        // save image
        $result = Image::read($image->getPathname())->cover($width, $height)->save(public_path($this->getImageAddress()), format: $this->getImageFormat());
        return $result ? $this->getImageAddress() : false;
    }


    public function createIndexAndSave($image)
    {
        $imageSizes = config('image.index-image-sizes');

        // set image
        $this->setImage($image);

        // set directory
        $this->getImageDirectory() ?? $this->setImageDirectory(date('Y') . DIRECTORY_SEPARATOR . date('m') . DIRECTORY_SEPARATOR . date('d'));
        $this->setImageDirectory($this->getImageDirectory() . DIRECTORY_SEPARATOR . time());

        // set name
        $this->getImageName() ?? $this->setImageName(time());
        $imageName = $this->getImageName();


        $indexArray = [];
        foreach($imageSizes as $sizeAlias => $imageSize) {

            // create and set this size name
            $currentImageName = $imageName . '_' . $sizeAlias;
            $this->setImageName($currentImageName);

            $this->provider();

            // save image
            $result = Image::read($image->getPathname())->cover($imageSize['width'], $imageSize['height'])->save(public_path($this->getImageAddress()), format: $this->getImageFormat());
            $result ? $indexArray[$sizeAlias] = $this->getImageAddress() : false;
        }

        $images['indexArray'] = $indexArray;
        $images['directory'] = $this->getFinalImageDirectory();
        $images['currentImage'] = config('image.default-current-index-image');
        return $images;
    }


    public function deleteImage($imagePath)
    {
        if(file_exists($imagePath))
        {
            unlink($imagePath);
        }
    }


    public function deleteIndex($images)
    {
        $directory = public_path($images['directory']);
        $this->deleteDirectoryAndFiles($directory);
    }


    private function deleteDirectoryAndFiles($directory)
    {
        if(!is_dir($directory)) {
            return false;
        }

        $files = glob($directory . DIRECTORY_SEPARATOR . '*', GLOB_MARK);
        foreach($files as $file)
        {
            if(is_dir($file))
            {
                $this->deleteDirectoryAndFiles($file);
            }
            else
            {
                unlink($file);
            }
        }

        $result = rmdir($directory);
        return $result;
    }



}