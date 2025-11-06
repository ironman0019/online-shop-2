<?php 

namespace App\Http\Services\ImageService;

use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ImageCacheService {

    /**
     * Cache and return processed image URL
     * 
     * @param string $imagePath Original image path
     * @param string|null $size Size alias from config
     * @return string URL to cached image
     */
    public function cache($imagePath, $size = null)
    {
        // set image size
        $imageSizes = config('image.cache-image-sizes');

        if(!isset($imageSizes[$size]))
        {
            $size = config('image.default-current-cache-image');
        }

        $width = $imageSizes[$size]['width'];
        $height = $imageSizes[$size]['height'];

        // Generate cache key and path
        $cacheKey = $this->generateCacheKey($imagePath, $size, $width, $height);
        $cachePath = $this->getCachePath($cacheKey);

        // Check if cached image exists and is not expired
        if($this->isCacheValid($cachePath)) {
            return $this->getCacheUrl($cachePath);
        }

        // Process and cache image
        if(file_exists($imagePath))
        {
            return $this->processAndCacheImage($imagePath, $width, $height, $cachePath);
        }
        else
        {
            return $this->createPlaceholderImage($width, $height, $cachePath);
        }
    }

    /**
     * Generate cache key based on image path and size
     */
    private function generateCacheKey($imagePath, $size, $width, $height)
    {
        $fileModified = file_exists($imagePath) ? filemtime($imagePath) : 0;
        return md5($imagePath . $size . $width . $height . $fileModified);
    }

    /**
     * Get cache file path (relative to storage disk)
     */
    private function getCachePath($cacheKey)
    {
        $cacheDir = 'image-cache';
        
        // Ensure directory exists using Storage
        Storage::disk('public')->makeDirectory($cacheDir);

        return $cacheDir . DIRECTORY_SEPARATOR . $cacheKey . '.jpg';
    }

    /**
     * Get public URL for cached image
     */
    private function getCacheUrl($cachePath)
    {
        // Use config to get the base URL for public storage
        $baseUrl = config('filesystems.disks.public.url', '/storage');
        return rtrim($baseUrl, '/') . '/' . str_replace('\\', '/', $cachePath);
    }

    /**
     * Check if cache is valid (exists and not expired)
     */
    private function isCacheValid($cachePath)
    {
        if(!Storage::disk('public')->exists($cachePath)) {
            return false;
        }

        $cacheLifetime = config('image.image-cache-lifetime', 10);
        $lastModified = Storage::disk('public')->lastModified($cachePath);
        $cacheAge = time() - $lastModified;

        return $cacheAge < ($cacheLifetime * 60); // Convert minutes to seconds
    }

    /**
     * Process image and save to cache
     */
    private function processAndCacheImage($imagePath, $width, $height, $cachePath)
    {
        try {
            $image = Image::read($imagePath);
            $image->cover($width, $height);
            
            // Get full path for saving
            $fullPath = Storage::disk('public')->path($cachePath);
            $image->save($fullPath, quality: 90);
            
            // Return public URL
            return $this->getCacheUrl($cachePath);
        } catch (\Exception $e) {
            // If processing fails, return placeholder
            return $this->createPlaceholderImage($width, $height, $cachePath);
        }
    }

    /**
     * Create placeholder image when original doesn't exist
     */
    private function createPlaceholderImage($width, $height, $cachePath)
    {
        try {
            // Get full path for saving
            $fullPath = Storage::disk('public')->path($cachePath);
            
            // Create a simple placeholder using GD directly
            $img = imagecreatetruecolor($width, $height);
            $bgColor = imagecolorallocate($img, 205, 205, 205); // #cdcdcd
            imagefill($img, 0, 0, $bgColor);
            
            // Add text
            $textColor = imagecolorallocate($img, 51, 51, 51); // #333333
            $text = 'no image - 404';
            $fontSize = min(16, $width / 20); // Scale font based on width
            $textX = ($width - (strlen($text) * $fontSize * 0.6)) / 2;
            $textY = ($height + $fontSize) / 2;
            
            imagestring($img, 5, $textX, $textY, $text, $textColor);
            
            // Save the image
            imagejpeg($img, $fullPath, 90);
            imagedestroy($img);
            
            // Return public URL
            return $this->getCacheUrl($cachePath);
        } catch (\Exception $e) {
            // Fallback: create simple colored image without text
            $fullPath = Storage::disk('public')->path($cachePath);
            $img = imagecreatetruecolor($width, $height);
            $bgColor = imagecolorallocate($img, 205, 205, 205);
            imagefill($img, 0, 0, $bgColor);
            imagejpeg($img, $fullPath, 90);
            imagedestroy($img);
            
            // Return public URL
            return $this->getCacheUrl($cachePath);
        }
    }
}