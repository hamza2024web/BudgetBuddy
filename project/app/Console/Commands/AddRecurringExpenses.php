<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ProcessRecurringExpenses;

class AddRecurringExpenses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recurring-expenses:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add all recurring expenses for this month';

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
        ProcessRecurringExpenses::dispatch();
        $this->info('Recurring expenses have been added successfully.');
    }
}
