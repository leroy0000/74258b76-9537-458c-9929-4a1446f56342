<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\File;
    use Sushi\Sushi;

    class Response extends Model
    {
        use Sushi;

        public $incrementing = false;
        protected $keyType = 'string';

        public function student()
        {
            return $this->belongsTo(Student::class);
        }

        public function assessment()
        {
            return $this->belongsTo(Assessment::class);
        }

        public function getRows()
        {
            $filePath = base_path('/data/student-responses.json');

            if (!File::exists($filePath)) {
                return [];
            }

            $json = File::get($filePath);
            $responseData = json_decode($json, true);
            $responseArr = [];

            foreach ($responseData as $response) {
                if (!isset($response['completed'])) {
                    continue;
                }
                $tmp = $response;
                $tmp['student_id'] = $tmp['student']['id'];
                $tmp['assessment_id'] = $tmp['assessmentId'];
                $tmp['student_yearlevel'] = $tmp['student']['yearLevel'];
                unset($tmp['student']);

                $tmp['raw_score'] = $tmp['results']['rawScore'];
                unset($tmp['results']);

                $tmp['responses'] = json_encode($response['responses']);

                $responseArr[] = $tmp;
            }
            
            return $responseArr;
        }

        public function fetchResponses()
        {
            return json_decode($this->responses, true);
        }
    }
