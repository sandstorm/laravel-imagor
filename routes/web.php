<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('__imagor-configtest', function () {
    Storage::disk('local')->put('__imagor-configtest_test1.jpg', file_get_contents(__DIR__ . '/../test_images/test1.jpg'));
    $simpleImageUrl = Storage::disk('local')->path('__imagor-configtest_test1.jpg');

    Storage::disk('local')->put('__imagor-configtest_WHukFiPIgKf8LE2UX2Mm5rLXYEJ9cv-metaU0NSLTIwMjUwOTI1LXJvZ2YuanBlZw==-.jpeg', file_get_contents(__DIR__ . '/../test_images/test1.jpg'));
    $tempUploadUrl = Storage::disk('local')->path('__imagor-configtest_WHukFiPIgKf8LE2UX2Mm5rLXYEJ9cv-metaU0NSLTIwMjUwOTI1LXJvZ2YuanBlZw==-.jpeg');

    Storage::disk('local')->put('__imagor-configtest_(name with special - characters?).jpeg', file_get_contents(__DIR__ . '/../test_images/test1.jpg'));
    $specialCharUrl = Storage::disk('local')->path('__imagor-configtest_(name with special - characters?).jpeg');

    return Blade::render(<<<'EOF'
<p>
If the following two images show, laravel-imagor is working:
</p>

<p>
- 1) SIMPLE images without special characters
<img src="{{ imagor()->resize(width: 100, height: 100)->uriFor($simpleImageUrl) }}" />
</p>

<p>
- 2) Images with special characters like temporary uploads
<img src="{{ imagor()->resize(width: 100, height: 100)->uriFor($tempUploadUrl) }}" />
(if this image does not show up, ensure that <code>FILE_SAFE_CHARS=--</code> is configured in the Imagor container.)
</p>

<p>
- 3) Images with arbitrary special characters
<img src="{{ imagor()->resize(width: 100, height: 100)->uriFor($specialCharUrl) }}" />
(if this image does not show up, ensure that <code>FILE_SAFE_CHARS=--</code> is configured in the Imagor container.)
</p>


EOF
        , [
            'simpleImageUrl' => $simpleImageUrl,
            'tempUploadUrl' => $tempUploadUrl,
            'specialCharUrl' => $specialCharUrl,
        ]);
});
