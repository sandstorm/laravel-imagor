<?php

if (! function_exists('imgproxy')) {
    function imgproxy($url, $width = 0, $height = 0, $preset = null)
    {
        return app(Imsus\ImgProxy\ImgProxy::class)->url($url, $width, $height, $preset);
    }
}
