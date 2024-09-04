<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CheckCodesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'code:check';

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
        $filePath = public_path('cdo-codes.txt');
        $contents = file_get_contents($filePath);

        $rows = explode("\n", $contents);

        // Count occurrences of each string
        $counts = array_count_values($rows);

        // Filter out duplicates (strings with more than one occurrence)
        $duplicates = array_filter($counts, function($count) {
            return $count > 1;
        });

        // Print the duplicate strings
        echo "Duplicate strings:\n";
        foreach ($duplicates as $string => $count) {
            echo "$string appears $count times\n";
        }
    }
}
