<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('__imagor-configtest', function () {
    Storage::disk('local')->put('__imagor-configtest_test1.jpg', file_get_contents(__DIR__ . '/../test_images/test1.jpg'));

    return Blade::render('If this image shows, laravel + imagor are set up correctly: <img src="{{ $imgUrl }}" />', [
        'imgUrl' => imagor()
            ->resize(width: 100, height: 100)
            ->uriFor(Storage::disk('local')->path('__imagor-configtest_test1.jpg'))
    ]);
});
