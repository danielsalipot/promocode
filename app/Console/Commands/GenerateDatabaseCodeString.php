<?php

namespace App\Console\Commands;

use App\Models\PepsiCodes;
use Illuminate\Console\Command;

class GenerateDatabaseCodeString extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:database';

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
        $limit = 1000000;
        $starting_values = str_split('JYHMRPC');
        $values = [
            'J9PCLAV',
            'YERTM7H3W',
            'H3W4J9PCLAVDFYE',
            'M7H3W4J9PCLAVDFYERT',
            'RTM7H3W4J9PCLAVDF',
            'PCLAVDFYERTM7H3',
            'CLAVDFYERTM7H3W4J9',
        ];

        $column_weights = [1, 3, 2, 6, 5, 4, 1];
        $weight_frequencies = [1, 18, 270, 2430, 36450, 619650];

        foreach ($values as $key => $v) {
            echo "Generating For: {$v}";
            echo "\n";

            $count = $weight_frequencies[$column_weights[$key] - 1];
            $result = $this->stretchAndRepeatString($v, $count, $limit);

            PepsiCodes::create([
                'column' => $result
            ]);

            echo "Generated: {$v}";
            echo "\n";
        }
    }

    public function stretchAndRepeatString($string, $count, $limit) {
        // Define the stretched string
        $stretchedString = '';

        // Loop through each character in the input string
        foreach (str_split($string) as $char) {
            // Append the character to the stretched string 'count' times
            $stretchedString .= str_repeat($char, $count);
        }

        // Repeat the stretched string until it reaches or exceeds the limit
        $repeatedString = str_repeat($stretchedString, ceil($limit / strlen($stretchedString)));

        // Truncate the repeated string to the exact limit length
        return substr($repeatedString, 0, $limit);
    }
}
