<?php

    namespace App\Actions;

    class GetStudent
    {
        public function __construct(public string $studentId){
        }

        public function execute(){
            $students = \App\Models\Student::all();
            $student = $students->where('id', '=', $this->studentId)->first();
            if ($student) {
                return $student;
            } else {
                return null;
            }
        }

    }