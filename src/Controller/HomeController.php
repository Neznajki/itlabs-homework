<?php


namespace App\Controller;


use App\Repository\ChallengeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function indexAction(
        ChallengeRepository $challengeRepository
    ) {
        return $this->render('home.html.twig', [
            'title'=> 'home',
            'challenges' => $challengeRepository->findAll(),
        ]);
    }
}