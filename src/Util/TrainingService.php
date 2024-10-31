<?php

namespace App\Util;

use App\Repository\CourseRepository;

class TrainingService
{

    public function __construct(private readonly CourseRepository $courseRepository)
    {
    }

    /**
     * Calcul du cout d'une formation pour un cours donné
     * @param int $id
     * @return int
     */
    public function getCost(int $id):int{
        $course = $this->courseRepository->find($id);
        if(!$course){
            throw new \Exception("Course not found");
        }
        if($course->getDuration() < 5){
            $cost = $course->getDuration()*1000;
        }else if($course->getDuration() < 10){
            $cost = $course->getDuration()*950;
        }else{
            $cost = $course->getDuration()*850;
        }
        return $cost;
    }
}