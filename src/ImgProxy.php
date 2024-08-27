<?php

namespace Imsus\ImgProxy;

class ImgProxy
{
    protected string $endpoint;

    protected string $key;

    protected string $salt;

    protected string $defaultPreset;

    public function __construct()
    {
        $this->endpoint = config('imgproxy.endpoint');
        $this->key = config('imgproxy.key');
        $this->salt = config('imgproxy.salt');
        $this->defaultPreset = config('imgproxy.default_preset');
    }

    public function url(string $url, int $width = 0, int $height = 0, ?string $preset = null): string
    {
        $preset = $preset ?? $this->defaultPreset;
        $encodedUrl = rtrim(strtr(base64_encode($url), '+/', '-_'), '=');

        $path = "/{$preset}/{$width}/{$height}/{$encodedUrl}";

        if (empty($this->key) || empty($this->salt)) {
            return $this->endpoint.$path;
        }

        $signature = $this->sign($path);

        return $this->endpoint.'/'.$signature.$path;
    }

    protected function sign(string $path): string
    {
        $hmac = hash_hmac('sha256', $this->salt.$path, $this->key, true);

        return rtrim(strtr(base64_encode($hmac), '+/', '-_'), '=');
    }
}
