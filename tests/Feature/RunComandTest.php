<?php

it('shows error message for invalid student ID', function () {
    $this->artisan('run')
        ->expectsQuestion('Student ID: ', '9999')
        ->expectsOutput("Invalid student ID. Please try again.")
        ->assertExitCode(0);
});

it('shows error message for empty report ID', function () {
    $this->artisan('run')
        ->expectsQuestion('Student ID: ', 'student1')
        ->expectsQuestion('Report to generate (1 for Diagnostic, 2 for Progress, 3 for Feedback): ', '')
        ->expectsOutput("You need to enter a Report ID. Please try again.")
        ->assertExitCode(0);
});

it('shows error message for invalid report ID', function () {
    $this->artisan('run')
        ->expectsQuestion('Student ID: ', 'student1')
        ->expectsQuestion('Report to generate (1 for Diagnostic, 2 for Progress, 3 for Feedback): ', '999')
        ->expectsOutput("Invalid option. Please try again.")
        ->assertExitCode(0);
});
