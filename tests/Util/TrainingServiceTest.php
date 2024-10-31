<?php

namespace App\Tests\Util;

use App\Entity\Course;
use App\Repository\CourseRepository;
use App\Util\TrainingService;
use PHPUnit\Framework\TestCase;

class TrainingServiceTest extends TestCase
{
    public function testCostForDifferentsValues(): void
    {
        //Création de 3 cours différents
        //pour correspondre aux 3 tranches de calcul
        $course1 = new Course();
        $course1->setDuration(4);

        $course2 = new Course();
        $course2->setDuration(5);

        $course3 = new Course();
        $course3->setDuration(12);

        //Création d'un mock  sur la dépendance CourseRepository
        $mock=$this->createMock(CourseRepository::class);
        //On attend exactement 4 appels à la méthode find()
        //    Chaque appel retournera successivement
        //$course1,$course2,$course3 puis null
        $mock->expects($this->exactly(4))
        ->method('find')
        ->willReturnOnConsecutiveCalls($course1, $course2, $course3,null);

        //On instance le service TrainingService avec le mock
        $trainingService = new TrainingService($mock);
        //On réalise les asserts
        $this->assertEquals(4*1000,$trainingService->getCost(1));
        $this->assertEquals(5*950,$trainingService->getCost(2));
        $this->assertEquals(12*850,$trainingService->getCost(3));
        //Le dernier test permet de vérifier l'exception si le cours n'est pas trouvé
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Course not found');
        $trainingService->getCost(4);

    }

    public function getDurationAndExpectedCost():array{
        return [
            [4,1000*4],
            [5,950*5],
            [12,850*12],
        ];
    }

    /**
     * @param int $duration
     * @param int $expectedCost
     * @return void
     * @throws \Exception
     * @dataProvider getDurationAndExpectedCost
     */
    public function testCost(int $duration,int $expectedCost): void
    {
        $course = new Course();
        $course->setDuration($duration);

        $mock=$this->createMock(CourseRepository::class);
        $mock->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($course);
        $trainingService = new TrainingService($mock);
        $this->assertEquals($expectedCost,$trainingService->getCost(1));
    }

    public function testCostInvalid(): void
    {
        $mock=$this->createMock(CourseRepository::class);
        $mock->expects($this->any())
            ->method('find')
            ->with(1)
            ->willReturn(null);
        $trainingService = new TrainingService($mock);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Course not found');
        $trainingService->getCost(1);
    }


}

