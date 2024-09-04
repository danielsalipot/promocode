<?php

namespace App\Console\Commands;

use App\Http\Controllers\GenerateCodeController;
use App\Models\PepsiCodes;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class PepsiCodeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:pepsi-code-command {key : key}';
    protected $signature = 'app:pepsi-code-command {start : start}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    // public function handle()
    // {
    //     echo "\r\n";
    //     echo "Now on key: " . $this->argument('key');
    //     echo "\r\n";

    //     $generator = new GenerateCodeController();
    //     PepsiCodes::create([
    //         'column' => $generator->index($this->argument('key'))
    //     ]);

    //     echo "\r\n";
    //     echo "Finished key: " . $this->argument('key');
    //     echo "\r\n";

    //     $new_key = $this->argument('key') + 1;
    //     if ($new_key > 7) {
    //         return "Finished";
    //     }

    //     Artisan::call('app:pepsi-code-command', ['key' => $new_key]);
    // }

    public function handle()
    {
        $generator = new GenerateCodeController();
        $array = $generator->generateCode($this->argument('start'));
        // $array = $generator->generateCodePhaseTwo($this->argument('start'));

        $filePath = public_path('cdo-codes.txt');

        // Open the file for writing
        $file = fopen($filePath, 'a');

        foreach ($array as $key => $value) {
            fwrite($file, $value. PHP_EOL);
            echo "Written Line: " . $this->argument('start') + ($key + 1);
            echo "\r\n";
        }

        fclose($file);

        // $new_start = $this->argument('start') + 1000000;
        // if ($new_start >= 1000000) {
        //     echo 'Data written to code.txt successfully. Total Lines: ' . $new_start;
        //     return;
        // }

        // Artisan::call('app:pepsi-code-command', ['start' => $new_start]);
    }
}
