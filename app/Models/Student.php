<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\File;
    use Sushi\Sushi;

    class Student extends Model
    {
        use Sushi;

        public $incrementing = false;
        protected $keyType = 'string';

        public function response()
        {
            return $this->hasMany(Response::class);
        }

        public function getRows() {
            $filePath = base_path('/data/students.json');

            if (!File::exists($filePath)) {
                return [];
            }

            $json = File::get($filePath);
            
            return json_decode($json, true);
        }
    }