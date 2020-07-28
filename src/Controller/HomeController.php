<?php


namespace App\Controller;


use App\Repository\ChallengeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param ChallengeRepository $challengeRepository
     * @return Response
     */
    public function indexAction(
        ChallengeRepository $challengeRepository
    ) {
        return $this->render('home.html.twig', [
            'title'=> 'home',
            'challenges' => $challengeRepository->findAll(),
        ]);
    }
}