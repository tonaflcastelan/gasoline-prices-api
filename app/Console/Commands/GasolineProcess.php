<?php

namespace App\Console\Commands;

use App\Services\GasolineService;
use Illuminate\Console\Command;

class GasolineProcess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'produce:gasoline.service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This process obtains the gasoline prices from an external API and saves them in the local database';

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
        $this->info("Building process!");
        $this->info(" >>> This could take a several minutes");
        $new = new GasolineService;
        $new->compute();
        $this->info("Process finished!!!");
    }
}
