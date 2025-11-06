<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports “GD Library” and “Imagick” to process images
    | internally. Depending on your PHP setup, you can choose one of them.
    |
    | Included options:
    |   - \Intervention\Image\Drivers\Gd\Driver::class
    |   - \Intervention\Image\Drivers\Imagick\Driver::class
    |
    */

    'driver' => \Intervention\Image\Drivers\Gd\Driver::class,

    /*
    |--------------------------------------------------------------------------
    | Configuration Options
    |--------------------------------------------------------------------------
    |
    | These options control the behavior of Intervention Image.
    |
    | - "autoOrientation" controls whether an imported image should be
    |    automatically rotated according to any existing Exif data.
    |
    | - "decodeAnimation" decides whether a possibly animated image is
    |    decoded as such or whether the animation is discarded.
    |
    | - "blendingColor" Defines the default blending color.
    |
    | - "strip" controls if meta data like exif tags should be removed when
    |    encoding images.
    */

    'options' => [
        'autoOrientation' => true,
        'decodeAnimation' => true,
        'blendingColor' => 'ffffff',
        'strip' => false,
    ],


    'index-image-sizes' => [

        'large' => [
            'width' => 800,
            'height' => 600
        ],

        'medium' => [
            'width' => 400,
            'height' => 300
        ],

        'small' => [
            'width' => 80,
            'height' => 60
        ]

    ],

    'default-current-index-image' => 'medium',

    // for image cache
    'cache-image-sizes' => [

        'large' => [
            'width' => 800,
            'height' => 600
        ],

        'medium' => [
            'width' => 400,
            'height' => 300
        ],

        'small' => [
            'width' => 80,
            'height' => 60
        ]

    ],

    'default-current-cache-image' => 'medium',

    'image-cache-lifetime' => 10,

    'image-not-found' => ''





];
