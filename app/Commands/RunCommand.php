<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

use function Termwind\render;

class RunCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run report generator for a given student.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        render("Please enter the following");
        $studentId = $this->ask('Student ID: ');
        $student = \App\Models\Student::find($studentId);
        
        if (!$student) {
            render("Invalid student ID. Please try again.");
            return;
        }

        $reportId = $this->ask('Report to generate (1 for Diagnostic, 2 for Progress, 3 for Feedback): ');

        if (empty($reportId)) {
            render("You need to enter a Report ID. Please try again.");
            return;
        }

        switch ($reportId) {
            case '1':
                $diagnostic = new \App\Actions\GenerateDiagnosticReport($studentId);
                $diagnostic->execute();
                break;
            case '2':
                $progress = new \App\Actions\GenerateProgressReport($studentId);
                $progress->execute();
                break;
            case '3':
                $feedback = new \App\Actions\GenerateFeedbackReport($studentId);
                $feedback->execute();
                break;
            
            default:
                render("Invalid option. Please try again.");
                break;
        }
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
