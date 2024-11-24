<?php

namespace App\Actions;

use Carbon\Carbon;

use function Termwind\render;

class GenerateFeedbackReport
{
    public function __construct(public string $studentId) {}

    public function execute()
    {
        $response = \App\Models\Response::with(['student', 'assessment'])->get();
        $questions = \App\Models\Question::all();
        $studentResponse = $response->filter(fn($res) => isset($res['student_id']) && $res['student_id'] === $this->studentId)->sortByDesc('completed')->first();
        $responseResults = $studentResponse;
        $answers = $studentResponse->fetchResponses();
        $totalCorrect = 0;
        $totalQuestions = $questions->count();
        unset($responseResults['responses']);
        $studentName = $responseResults->student->firstName . ' ' . $responseResults->student->lastName;
        $completedDate = Carbon::createFromFormat('d/m/Y H:i:s', $responseResults['completed']);
        $answersStr = "";

        foreach ($answers as $value) {
            $q = $questions->where('id', '=', $value['questionId'])->first();
            if ($q['config_key'] != $value['response']) {
                $ans = $q->getQuestionOption($value['response']);
                $correctAns = $q->getQuestionOption($q['config_key']);
                $answersStr .= "Question: {$q['stem']}<br />";
                $answersStr .= "Your Answer: {$ans['label']} with value {$ans['value']} <br />";
                $answersStr .= "Right Answer: {$correctAns['label']} with value {$correctAns['value']} <br />";
                $answersStr .= "Hint: {$q['config_hint']} <br />";
                $answersStr .= "<br />";
            } else {
                $totalCorrect++;
            }
        }

        render("{$studentName} recently completed {$responseResults->assessment->name} assessment on {$completedDate->format('jS F Y g:i A')}");
        render("He got {$totalCorrect} questions right out of {$totalQuestions}. Deteails by strand given below:");
        render($answersStr);
    }
}
