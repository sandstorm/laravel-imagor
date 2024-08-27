<?php

namespace Imsus\ImgProxy\Commands;

use Illuminate\Console\Command;

class ImgProxyCommand extends Command
{
    public $signature = 'laravel-imgproxy';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
