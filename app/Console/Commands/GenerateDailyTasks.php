<?php

namespace App\Console\Commands;

use App\Models\DailyAttendance;
use App\Models\RecurringAssignment;
use Illuminate\Console\Command;

class GenerateDailyTasks extends Command
{
    protected $signature = 'tasks:generate';
    protected $description = 'Generate daily tasks for recurring assignments';

    public function handle()
    {
        $assignments = RecurringAssignment::where('frequency', 'daily')->get();

        foreach ($assignments as $assignment) {
            DailyAttendance::firstOrCreate([
                'assignment_id' => $assignment->id,
                'date' => now()->format('Y-m-d')
            ], [
                'status' => 'pending'
            ]);
        }

        $this->info('Daily tasks generated successfully');
    }
}
