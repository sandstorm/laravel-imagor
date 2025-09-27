<?php

namespace Sandstorm\LaravelImagor;

use RuntimeException;

/**
 * @api
 */
class Imagor
{
    private bool $trim = false;
    private ?string $crop = null;
    private bool $fitIn = false;
    private bool $stretch = false;
    private int $resizeWidth = 0;
    private int $resizeHeight = 0;
    private bool $flipHorizontally = false;
    private bool $flipVertically = false;
    private ?string $padding = null;
    private ?string $hAlign = null;
    private ?string $vAlign = null;
    private bool $smart = false;
    private array $filters = [];

    /**
     * @internal use ImagorFactory::new() instead.
     */
    public function __construct(
        private readonly string      $baseUrl,
        private readonly string|null $signerType,
        private readonly string|null $secret,
        private readonly int|null    $signerTruncate,
        private readonly array       $pathMap = [],
    )
    {
    }

    /**
     * trim removes surrounding space in images using top-left pixel color
     *
     * @return $this
     */
    public function trim(): self
    {
        $this->trim = true;
        return $this;
    }

    /**
     * AxB:CxD means manually crop the image at left-top point AxB and right-bottom point CxD.
     * Coordinates can also be provided as float values between 0 and 1 (percentage of image dimensions)
     *
     * @param int $a top left x coordinate
     * @param int $b top left y coordinate
     * @param int $c bottom right x coordinate
     * @param int $d bottom right y coordinate
     * @return $this
     */
    public function crop(int $a, int $b, int $c, int $d): self
    {
        $this->crop = sprintf('%dx%d:%dx%d', $a, $b, $c, $d);
        return $this;
    }

    /**
     * fit-in means that the generated image should not be auto-cropped and
     * otherwise just fit in an imaginary box specified by ExF
     *
     * @return $this
     */
    public function fitIn(): self
    {
        $this->fitIn = true;
        return $this;
    }

    /**
     * stretch means resize the image to ExF without keeping its aspect ratios
     *
     * @return $this
     */
    public function stretch(): self
    {
        $this->stretch = true;
        return $this;
    }

    /**
     * ExF means resize the image to be ExF of width per height size.
     *
     * @param int $width
     * @param int $height
     * @return self
     */
    public function resize(int $width = 0, int $height = 0): self
    {
        $this->resizeWidth = $width;
        $this->resizeHeight = $height;
        return $this;
    }

    public function getResizeWidth(): int
    {
        return $this->resizeWidth;
    }

    public function getResizeHeight(): int
    {
        return $this->resizeHeight;
    }

    public function flipHorizontally(): self
    {
        $this->flipHorizontally = !$this->flipHorizontally;
        return $this;
    }

    public function flipVertically(): self
    {
        $this->flipVertically = !$this->flipVertically;
        return $this;
    }

    /**
     * GxH:IxJ add left-top padding GxH and right-bottom padding IxJ
     *
     * @param int $left
     * @param int $top
     * @param int $right
     * @param int $bottom
     * @return self
     */
    public function padding(int $left, int $top, int $right, int $bottom): self
    {
        $this->padding = sprintf('%dx%d:%dx%d', $left, $top, $right, $bottom);
        return $this;
    }

    /**
     * HALIGN is horizontal alignment of crop. Accepts left, right or center, defaults to center
     * @param string $hAlign
     * @return self
     */
    public function hAlign(string $hAlign): self
    {
        if (!in_array($hAlign, ['left', 'right', 'center'])) {
            throw new RuntimeException('Unsupported hAlign: ' . $hAlign);
        }
        $this->hAlign = $hAlign;
        return $this;
    }

    /**
     * VALIGN is vertical alignment of crop. Accepts top, bottom or middle, defaults to middle
     * @param string $vAlign
     * @return self
     */
    public function vAlign(string $vAlign): self
    {
        if (!in_array($vAlign, ['top', 'bottom', 'middle'])) {
            throw new RuntimeException('Unsupported vAlign: ' . $vAlign);
        }
        $this->vAlign = $vAlign;
        return $this;
    }

    /**
     * smart means using smart detection of focal points
     *
     * @return $this
     */
    public function smart(): self
    {
        $this->smart = true;
        return $this;
    }

    /**
     * @param string $filterName
     * @param mixed ...$args
     * @return $this
     */
    public function addFilter(string $filterName, ...$args): self
    {
        $this->filters[] = $filterName . '(' . implode(',', $args) . ')';
        return $this;
    }

    /**
     * changes the overall quality of the image, does nothing for png
     *
     * amount 0 to 100, the quality level in %
     *
     * @param int $quality
     * @return self
     */
    public function quality(int $quality): self
    {
        return $this->addFilter('quality', $quality);
    }

    public function format(string $format): self
    {
        assert(in_array($format, ['jpeg', 'png', 'gif', 'webp', 'avif', 'jxl', 'tiff', 'jp2']));
        return $this->addFilter('format', $format);
    }

    public function blur(float $sigmaX, ?float $sigmaY = null): self
    {
        if ($sigmaY === null) {
            return $this->addFilter('blur', $sigmaX);
        }
        return $this->addFilter('blur', $sigmaX, $sigmaY);
    }

    public function sharpen(float $sigma): self
    {
        return $this->addFilter('sharpen', $sigma);
    }

    public function saturation(int $amount): self
    {
        return $this->addFilter('saturation', $amount);
    }

    public function brightness(int $amount): self
    {
        return $this->addFilter('brightness', $amount);
    }

    public function contrast(int $amount): self
    {
        return $this->addFilter('contrast', $amount);
    }

    public function uriFor(string $sourceImage): string
    {
        $sourceImage = $this->resolveTargetPath($sourceImage);
        $decodedPathSegments = [];

        if ($this->trim) {
            $decodedPathSegments[] = 'trim';
        }
        if ($this->crop !== null) {
            $decodedPathSegments[] = $this->crop;
        }
        if ($this->fitIn) {
            $decodedPathSegments[] = 'fit-in';
        }
        if ($this->stretch) {
            $decodedPathSegments[] = 'stretch';
        }
        if ($this->resizeWidth !== 0 || $this->resizeHeight !== 0 || $this->flipVertically || $this->flipHorizontally) {
            $decodedPathSegments[] = sprintf(
                '%s%dx%s%d',
                $this->flipVertically ? '-' : '',
                $this->resizeWidth,
                $this->flipHorizontally ? '-' : '',
                $this->resizeHeight
            );
        }
        if ($this->padding !== null) {
            $decodedPathSegments[] = $this->padding;
        }
        if ($this->hAlign !== null) {
            $decodedPathSegments[] = $this->hAlign;
        }
        if ($this->vAlign !== null) {
            $decodedPathSegments[] = $this->vAlign;
        }
        if ($this->smart) {
            $decodedPathSegments[] = 'smart';
        }

        if (!empty($this->filters)) {
            $decodedPathSegments[] = 'filters:' . implode(':', $this->filters);
        }

        // eg example.net/kisten-trippel_3_kw%282%29.jpg
        $sourcePath = ltrim($sourceImage, '/');

        $decodedPathSegments[] = $sourcePath;
        $encodedPathSegments = array_map(function ($segment) {
            return self::encodeURIComponent($segment);
        }, $decodedPathSegments);


        $encodedPath = implode('/', $encodedPathSegments);

        // eg N…mVw/30x40%3A100x150%2Ffilters%3Afill%28cyan%29/example.net/kisten-trippel_3_kw%282%29.jpg
        return rtrim($this->baseUrl, '/') . '/' . $this->hmac($encodedPath) . "/" . $encodedPath;
    }

    // Equivalent of JavaScript encodeURIComponent in PHP
    private static function encodeURIComponent($str) {
        $revert = ['%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')'];
        return strtr(rawurlencode($str), $revert);
    }


    private function hmac(string $path): string
    {
        if ($this->signerType === null || $this->secret === null) {
            return 'unsafe';
        } else {
            $hash = strtr(
                base64_encode(
                    hash_hmac(
                        $this->signerType,
                        $path,
                        $this->secret,
                        true
                    )
                ),
                '/+',
                '_-'
            );
            if ($this->signerTruncate === null) {
                return $hash;
            } else {
                return substr($hash, 0, $this->signerTruncate);
            }
        }
    }

    private function resolveTargetPath(string $sourceImage)
    {
        foreach ($this->pathMap as $source => $target) {
            if (str_starts_with($sourceImage, $source)) {
                return $target . substr($sourceImage, strlen($source));
            }
        }

        return $sourceImage;
    }
}
