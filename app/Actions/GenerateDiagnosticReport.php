<?php

namespace App\Actions;

use Carbon\Carbon;

use function Termwind\render;

class GenerateDiagnosticReport
{
    public function __construct(public string $studentId) {}

    public function execute()
    {
        $response = \App\Models\Response::with(['student', 'assessment'])->get();
        $questions = \App\Models\Question::all();
        $studentResponse = $response->filter(fn($res) => isset($res['student_id']) && $res['student_id'] === $this->studentId)->sortByDesc('completed')->first();
        $responseResults = $studentResponse;
        $answers = $studentResponse->fetchResponses();
        $strandCount = array();
        $totalCorrect = 0;
        $totalQuestions = $questions->count();
        unset($responseResults['responses']);

        foreach ($answers as $value) {
            $q = $questions->where('id', '=', $value['questionId'])->first();
            if ($q['config_key'] === $value['response']) {
                $totalCorrect++;
                $strandCount[$q['strand']]['correct_count'] = isset($strandCount[$q['strand']]['correct_count']) ? $strandCount[$q['strand']]['correct_count'] + 1 : 1;
            } else {
                $strandCount[$q['strand']]['incorrect_count'] = isset($strandCount[$q['strand']]['incorrect_count']) ? $strandCount[$q['strand']]['incorrect_count'] + 1 : 1;
            }
            $strandCount[$q['strand']]['total_count'] = isset($strandCount[$q['strand']]['total_count']) ? $strandCount[$q['strand']]['total_count'] + 1 : 1;
        }

        $responseResults['strandCount'] = $strandCount;
        $studentName = $responseResults->student->firstName . ' ' . $responseResults->student->lastName;
        $completedDate = Carbon::createFromFormat('d/m/Y H:i:s', $responseResults['completed']);
        
        render("{$studentName} recently completed {$responseResults->assessment->name} assessment on {$completedDate->format('jS F Y g:i A')}");
        render("He got {$totalCorrect} questions right out of {$totalQuestions}. Details by strand given below: \r");
        foreach ($strandCount as $strand => $strandData) {
            render("{$strand}: {$strandData['correct_count']} out of {$strandData['total_count']} correct");
        }
    }
}
