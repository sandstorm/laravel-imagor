<?php

namespace Workbench\App\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Number;
use Sandstorm\LaravelImagor\Imagor;

class BenchmarkImageSizes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'imagor:benchmark-image-sizes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Benchmark image sizes';

    private static function measure(string $photoUrl): string
    {
        return Number::fileSize(strlen(file_get_contents($photoUrl)), 1);
    }


    /**
     * Execute the console command.
     */
    public function handle(Imagor $imagor)
    {
        $benchmarkPhoto = 'https://images.unsplash.com/photo-1758609179675-a284dc57fd7f?ixlib=rb-4.1.0&q=85&fm=jpg&crop=entropy&cs=srgb&dl=zetong-li-H5Ku7I-SFR0-unsplash.jpg';

        $imagorJpg = $imagor->format('jpeg');
        $imagorWebp = $imagor->format('webp');
        $this->table(['version', 'format', 'dimensions', 'size'], [
            ['original', 'jpeg', 'orig', self::measure($benchmarkPhoto)],
            ['optimized_no_change', 'jpeg', 'orig', self::measure($imagorJpg->uriFor($benchmarkPhoto))],
            ['optimized_quality95', 'jpeg', 'orig', self::measure($imagorJpg->quality(95)->uriFor($benchmarkPhoto))],
            ['optimized_quality80', 'jpeg', 'orig', self::measure($imagorJpg->quality(80)->uriFor($benchmarkPhoto))],
            ['optimized_quality50', 'jpeg', 'orig', self::measure($imagorJpg->quality(50)->uriFor($benchmarkPhoto))],

            ['optimized_no_change', 'jpeg', '600x600', self::measure($imagorJpg->resize(600, 600)->uriFor($benchmarkPhoto))],
            ['optimized_quality95', 'jpeg', '600x600', self::measure($imagorJpg->resize(600, 600)->quality(95)->uriFor($benchmarkPhoto))],
            ['optimized_quality80', 'jpeg', '600x600', self::measure($imagorJpg->resize(600, 600)->quality(80)->uriFor($benchmarkPhoto))],
            ['optimized_quality50', 'jpeg', '600x600', self::measure($imagorJpg->resize(600, 600)->quality(50)->uriFor($benchmarkPhoto))],

            ['optimized_no_change', 'webp', 'orig', self::measure($imagorWebp->uriFor($benchmarkPhoto))],
            ['optimized_quality95', 'webp', 'orig', self::measure($imagorWebp->quality(95)->uriFor($benchmarkPhoto))],
            ['optimized_quality80', 'webp', 'orig', self::measure($imagorWebp->quality(80)->uriFor($benchmarkPhoto))],
            ['optimized_quality50', 'webp', 'orig', self::measure($imagorWebp->quality(50)->uriFor($benchmarkPhoto))],

            ['optimized_no_change', 'webp', '600x600', self::measure($imagorWebp->resize(600, 600)->uriFor($benchmarkPhoto))],
            ['optimized_quality95', 'webp', '600x600', self::measure($imagorWebp->resize(600, 600)->quality(95)->uriFor($benchmarkPhoto))],
            ['optimized_quality80', 'webp', '600x600', self::measure($imagorWebp->resize(600, 600)->quality(80)->uriFor($benchmarkPhoto))],
            ['optimized_quality50', 'webp', '600x600', self::measure($imagorWebp->resize(600, 600)->quality(50)->uriFor($benchmarkPhoto))],
        ]);
    }
}
