<?php

namespace App\Console\Commands;

use App\Http\Controllers\GenerateCodeController;
use Illuminate\Console\Command;

class concatValueForPhaseTwo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:concat-value-for-phase-two';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $generator = new GenerateCodeController();
        $generator->concatForSecondPhase();
        echo "string concat";
    }
}
