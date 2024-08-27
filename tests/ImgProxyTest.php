<?php

namespace Imsus\ImgProxy\Tests;

use Imsus\ImgProxy\ImgProxy;

it('can generate insecure url', function () {
    $imgProxy = new ImgProxy();
    $url = $imgProxy->url('https://example.com/image.jpg', 300, 200);

    expect($url)->toBe('http://localhost:8080/default/300/200/aHR0cHM6Ly9leGFtcGxlLmNvbS9pbWFnZS5qcGc');
});

it('can generate signed url', function () {
    config()->set('imgproxy.key', 'secret_key');
    config()->set('imgproxy.salt', 'secret_salt');

    $imgProxy = new ImgProxy();
    $url = $imgProxy->url('https://example.com/image.jpg', 300, 200);

    expect($url)->toMatch('/^http:\/\/localhost:8080\/[A-Za-z0-9_-]+\/default\/300\/200\/aHR0cHM6Ly9leGFtcGxlLmNvbS9pbWFnZS5qcGc$/');
});

it('can use helper function', function () {
    $url = imgproxy('https://example.com/image.jpg', 300, 200);

    expect($url)->toBe('http://localhost:8080/default/300/200/aHR0cHM6Ly9leGFtcGxlLmNvbS9pbWFnZS5qcGc');
});
