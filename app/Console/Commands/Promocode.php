<?php

namespace App\Console\Commands;

use function Laravel\Prompts\text;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Promocode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promocode';

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
        $name = text('Promocode Name: ');
        $code_count = text('How many codes you want to generate? ');
        $character_count = text('How many characters a promo code will have?');

        $files = $this->collectCiphersPhase($name, $character_count, $code_count);
        $this->writePromocodes($files, $name, $code_count);

        echo "Promocode Generation Complete";
        echo "\n";
    }

    public function collectCiphersPhase($name, $character_count, $code_count)
    {
        $files = [];
        for ($i = 0; $i < $character_count; $i++) {
            $code = text($i + 1 . ". give cipher code");
            $weight = text($i + 1 . ". give weight for " . $code);
            $str = $this->generateWeightedString($code, $weight, $code_count);

            // Corrected path
            $path = public_path("$name/ciphers/$code.txt");

            // Ensure the directory exists
            if (!File::exists(public_path("$name/ciphers"))) {
                File::makeDirectory(public_path("$name/ciphers"), 0755, true); // Recursively create directories
            }

            // Write the promo code to the file
            File::put($path, $str);
            $files[] = $path;

            echo "loading...";
            echo "\n";
        }

        return $files;
    }

    public function writePromocodes($files, $name, $code_count)
    {
        $ciphers = [];
        foreach ($files as $key => $file) {
            $cipher = File::get($file);
            $ciphers[] = $cipher;
        }

        $promocode_path = public_path($name);
        if (!File::exists($promocode_path)) {
            File::makeDirectory($promocode_path, 0755, true); // Recursively create directories
        }

        File::put($promocode_path . '/promocode.txt', "");
        $file = fopen($promocode_path . '/promocode.txt', 'a');

        for ($i = 0; $i < $code_count; $i++) {
            $code = "";
            foreach ($ciphers as $key => $cipher) {
                $code .= $cipher[$i];
            }

            fwrite($file, $code. PHP_EOL);
            echo "CODE #" . ($i + 1) . " - " . $code;
            echo "\r\n";
        }

        fclose($file);
    }

    public function generateWeightedString($inputString, $weight, $limit)
    {
        $result = '';  // This will store the final string
        $index = 0;    // To track the position in the input string
        $inputLength = strlen($inputString);

        while (strlen($result) < $limit) {
            // Get the current character
            $currentChar = $inputString[$index % $inputLength];

            // Add the current character repeated $weight times to the result
            $result .= str_repeat($currentChar, $weight);

            // Move to the next character
            $index++;
        }

        // Trim the result to the limit, in case it exceeds it
        return substr($result, 0, $limit);
    }
}
