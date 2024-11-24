<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Sushi\Sushi;

class Question extends Model
{
    use Sushi;

    public $incrementing = false;
    protected $keyType = 'string';

    public function getRows()
    {
        $filePath = base_path('/data/questions.json');

        if (!File::exists($filePath)) {
            return [];
        }

        $json = File::get($filePath);
        $questionData = json_decode($json, true);
        $questionArr = [];

        foreach ($questionData as $question) {
            $tmp = $question;
            unset($tmp['config']);
            $tmp['config_key'] = $question['config']['key'];
            $tmp['config_hint'] = $question['config']['hint'];
            $tmp['config_options'] = json_encode($question['config']['options']);
            $questionArr[] = $tmp;
        }
        return $questionArr;
    }

    public function fetchQuestions()
    {
        return json_decode($this->config_options, true);
    }
}
