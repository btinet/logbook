<?php

namespace App\Controller;

use App\AbstractController;
use App\Entity\Semester;
use ReflectionException;

class SemesterController extends AbstractController
{

    /**
     * @throws ReflectionException
     */
    public function index(): string
    {
        //$user = $this->getUser();

        $semesterRepository = $this->getRepository(Semester::class);
        $semesters = $semesterRepository->findBy([
            'parent' => false,
        ],[
            'title' => 'ASC'
        ]);

        return $this->render('semester/index.html.twig',[
            'flash' => $this->getFlash(),
            'semesters' => $semesters,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws ReflectionException
     */
    public function view(int $id): string
    {
        $semesterRepository = $this->getRepository(Semester::class);
        $currentSemester = $semesterRepository->findOneBy([
            'id' => $id
        ]);
        $semesters = $semesterRepository->findBy([
            'parent' => $id,
        ],[
                'title' => 'ASC'
            ]
        );
        if (!$semesters) $this->redirect(302,'semester');

        return $this->render('semester/view.html.twig',[
            'flash' => $this->getFlash(),
            'current_semester' => $currentSemester,
            'semesters' => $semesters,
        ]);
    }

}
