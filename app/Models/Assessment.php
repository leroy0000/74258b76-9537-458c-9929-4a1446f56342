<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Sushi\Sushi;

class Assessment extends Model
{
    use Sushi;

    public $incrementing = false;
    protected $keyType = 'string';

    public function question()
    {
        return $this->hasMany(Question::class);
    }

    public function getRows()
    {
        $filePath = base_path('/data/assessments.json');
        
        if (!File::exists($filePath)) {
            return [];
        }

        $json = File::get($filePath);
        $assessmentData = json_decode($json, true);
        $assessmentArr = [];

        foreach ($assessmentData as $assessment) {
            $tmp = $assessment;
            unset($tmp['questions']);
            $tmp['questions'] = json_encode($assessment['questions']);
            $assessmentArr[] = $tmp;
        }
        
        return $assessmentArr;
    }
}
