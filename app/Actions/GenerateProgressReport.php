<?php

namespace App\Actions;

use Carbon\Carbon;

use function Termwind\render;

class GenerateProgressReport
{
    public function __construct(public string $studentId) {}

    public function execute()
    {
        $responseCollection = \App\Models\Response::with(['student', 'assessment'])->get();
        $questionCount = \App\Models\Question::count();
        $student = new \App\Actions\GetStudent($this->studentId);
        $studentData = $student->execute();
        $studentResponse = $responseCollection->filter(fn($res) => isset($res['student_id']) && $res['student_id'] === $this->studentId)->sortBy('completed')->all();
        $studentName = $studentData->firstName . ' ' . $studentData->lastName;

        $assessmentResponseData = array();
        foreach ($studentResponse as $response) {
            $assessmentResponseData[$response->assessment->name][] = $response;
        }

        foreach ($assessmentResponseData as $assessmentName => $data) {
            $assessmentTimesTaken = count($assessmentResponseData[$assessmentName]);
            $scoreDiff = $assessmentResponseData[$assessmentName][$assessmentTimesTaken - 1]['raw_score'] - $assessmentResponseData[$assessmentName][0]['raw_score'];
            render("{$studentName} has completed {$assessmentName} assessment {$assessmentTimesTaken} times in total. Date and raw score given below: \r");
            foreach ($data as $response) {
                $completedDate = Carbon::createFromFormat('d/m/Y H:i:s', $response['completed']);
                render("Date: {$completedDate->format('jS F Y g:i A')}, Raw Score: {$response['raw_score']} out of {$questionCount}");
            }

            render("<br />");

            if($scoreDiff < 0) {
                $scoreDiffLess = abs($scoreDiff);
                render("{$studentName} got {$scoreDiffLess} less correct answers in the recent completed assessment than the oldest.");
            } else {
                render("{$studentName} got {$scoreDiff} more correct answers in the recent completed assessment than the oldest.");
            }
        }
    }
}
