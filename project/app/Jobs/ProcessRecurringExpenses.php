<?php

namespace App\Jobs;

use App\Models\RecurringExpense;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessRecurringExpenses implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $today = Carbon::today();

        $recurringExpenses = RecurringExpense::where('next_due_date', '<=', $today)->get();


        foreach ($recurringExpenses as $expense) {
            Expense::create([
                'user_id' => $expense->user_id,
                'name' => $expense->name,
                'amount' => $expense->amount,
                'category' => $expense->category,
                'date' => $today,
            ]);

            $expense->update([
                'next_due_date' => $today->addMonth(),
            ]);
        }
    }
}
