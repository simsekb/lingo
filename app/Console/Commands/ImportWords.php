<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\word;

class ImportWords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importwords (CSV file path & name)';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import words to database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //logic to open the file & write to the db
        $file = $this->ask('Enter CSV file path');

        echo 'Attempting to fill database..' . "\r\n";
        $handle = fopen($file, 'r');
        if ($handle) {
            while ($line = fgets($handle)) {
                // Process this line and save to database
                Word::create(array('word' => $line)); //add word to the db
            }
        }
        fclose($handle);
        echo 'Task has been finished.' . "\r\n";
        return 0;
    }
}
