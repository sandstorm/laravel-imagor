<?php
declare(strict_types=1);

namespace Sandstorm\LaravelImagor\Filament\Components;

use Closure;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Storage;
use Sandstorm\LaravelImagor\ImagorFactory;
use Sandstorm\LaravelImagor\Imagor;

class ImagorFileUpload extends FileUpload
{
    protected Closure|Imagor|null $imageProcessor = null;

    protected function setUp(): void
    {
        parent::setUp();

        $originalGetUploadedFileUsing = $this->getUploadedFileUsing;
        $this->getUploadedFileUsing(function (BaseFileUpload $component, string $file, string|array|null $storedFileNames, ImagorFactory $imagorFactory) use ($originalGetUploadedFileUsing): ?array {
            $result = $originalGetUploadedFileUsing($component, $file, $storedFileNames);

            $imageProcessor = $imagorFactory->new();

            if ($this->imageProcessor !== null) {
                $imageProcessor = $this->evaluate($this->imageProcessor, [
                    'imagor' => $imageProcessor
                ]);
            }

            $result['url'] = $imageProcessor
                ->uriFor(
                    Storage::disk('public')->path($file)
                );
            return $result;
        });
    }


    public function imageProcessor(Closure|Imagor|null $imageProcessor): static
    {
        $this->imageProcessor = $imageProcessor;

        return $this;
    }
}
