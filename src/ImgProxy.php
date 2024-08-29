<?php

namespace Imsus\ImgProxy;

use Imsus\ImgProxy\Enums\OutputExtension;
use Imsus\ImgProxy\Enums\ResizeType;
use Imsus\ImgProxy\Enums\SourceUrlMode;

class ImgProxy
{
    private string $endpoint;

    private string $key;

    private string $salt;

    private string $source_url;

    private SourceUrlMode $source_url_mode;

    private OutputExtension $default_output_extension;

    private ?OutputExtension $overridden_extension = null;

    private array $options = [];

    private ?string $processing_options = null;

    public function __construct()
    {
        $this->endpoint = config('imgproxy.endpoint');
        $this->key = $this->validateHexString(config('imgproxy.key'), 'key');
        $this->salt = $this->validateHexString(config('imgproxy.salt'), 'salt');
        $this->source_url_mode = SourceUrlMode::fromString(config('imgproxy.default_source_url_mode')) ?? SourceUrlMode::getDefault();
        $this->default_output_extension = OutputExtension::fromExtension(config('imgproxy.default_output_extension')) ?? OutputExtension::getDefault();
    }

    /**
     * Set the source URL for the image.
     *
     * @param  string  $source_url  The URL of the source image
     */
    public function url(string $source_url): self
    {
        $this->source_url = $source_url;

        return $this;
    }

    /**
     * Set the height of the output image.
     *
     * @param  int  $height  The desired height in pixels
     */
    public function setHeight(int $height): self
    {
        $this->options['height'] = $height;

        return $this;
    }

    /**
     * Set the width of the output image.
     *
     * @param  int  $width  The desired width in pixels
     */
    public function setWidth(int $width): self
    {
        $this->options['width'] = $width;

        return $this;
    }

    /**
     * Set the resize mode for the image.
     *
     * @param  string  $mode  The resize mode ('fit', 'fill', 'crop', 'force')
     *
     * @see \Imsus\ImgProxy\Enums\ResizeType
     */
    public function setResizeType(ResizeType $mode): self
    {
        $this->options['resizing_type'] = $mode;

        return $this;
    }

    /**
     * Set the source URL mode (encoded or plain).
     *
     * @param  SourceUrlMode  $source_url_mode  The source URL mode
     *
     * @see \Imsus\ImgProxy\Enums\SourceUrlMode
     */
    public function setMode(SourceUrlMode $source_url_mode): self
    {
        $this->source_url_mode = $source_url_mode;

        return $this;
    }

    /**
     * Set the output file extension.
     *
     * @param  OutputExtension  $extension  The desired file extension
     *
     * @see \Imsus\ImgProxy\Enums\OutputExtension
     */
    public function setExtension(OutputExtension $extension): self
    {
        $this->overridden_extension = $extension;

        return $this;
    }

    /**
     * Set the processing string.
     *
     * @param  string  $processing_options  The processing string to be used
     *
     * @see https://docs.imgproxy.net/usage/processing#processing-options
     */
    public function setProcessing(string $processing_options): self
    {
        $this->processing_options = $processing_options;

        return $this;
    }

    /**
     * Build the final ImgProxy URL.
     *
     * @return string The generated ImgProxy URL
     *
     * @throws \InvalidArgumentException If the source URL is invalid
     */
    public function build(): string
    {
        $this->validateSourceUrl();
        $path = $this->buildPath();

        if ($this->key && $this->salt) {
            $signature = $this->generateSignature($path);
        } else {
            $signature = 'insecure';
        }

        return "{$this->endpoint}/{$signature}/{$path}";
    }

    private function validateSourceUrl(): void
    {
        if (empty($this->source_url) || ! filter_var($this->source_url, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('Invalid source URL');
        }
    }

    private function buildPath(): string
    {
        $processing_options = $this->processing_options ?? $this->buildProcessingOptions();
        $extension = $this->overridden_extension ?? OutputExtension::fromExtension(pathinfo($this->source_url, PATHINFO_EXTENSION)) ?? $this->default_output_extension;

        if ($this->source_url_mode === SourceUrlMode::PLAIN) {
            $path = "{$processing_options}/plain/{$this->source_url}";
            if ($extension) {
                $path .= "@{$extension->value}";
            }
        } else {
            $encoded_source_url = rtrim(strtr(base64_encode($this->source_url), '+/', '-_'), '=');
            $path = "{$processing_options}/{$encoded_source_url}";
            if ($extension) {
                $path .= ".{$extension->value}";
            }
        }

        return $path;
    }

    private function buildProcessingOptions(): string
    {
        return implode('/', array_map(
            fn ($key, $value) => "{$key}:{$value}",
            array_keys($this->options),
            $this->options
        ));
    }

    /**
     * Generates a signature for the given path.
     *
     * @param  string  $path  The path to generate a signature for
     * @return string The generated signature
     */
    public function generateSignature(string $path): string
    {
        $data = "{$this->salt}/{$path}";
        $hmac = hash_hmac('sha256', $data, $this->key, true);
        $signature = base64_encode($hmac);
        $signature = str_replace(['+', '/', '='], ['-', '_', ''], $signature);

        return $signature;
    }

    private function validateHexString(string $value, string $name): string
    {
        if ($value === '') {
            return $value;
        }

        if (! ctype_xdigit($value)) {
            throw new \InvalidArgumentException("The {$name} must be a hex-encoded string.");
        }

        return pack('H*', $value);
    }
}
