<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportSepomex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:sepomex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for import states and municipalities';

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
        $this->info('This process can take a several minutes. You can go coffee');
        DB::unprepared(file_get_contents('database/seeds/estados.sql'));
        DB::unprepared(file_get_contents('database/seeds/municipios.sql'));
        DB::unprepared(file_get_contents('database/seeds/zipcodes.sql'));
        $this->info('Process finished!!!');
    }
}
