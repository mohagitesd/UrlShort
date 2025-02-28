<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ShortlinkController extends AbstractController
{
    #[Route('/shortlink', name: 'app_shortlink')]
    public function index(): Response
    {
        return $this->render('shortlink/index.html.twig', [
            'controller_name' => 'ShortlinkController',
        ]);
    }
}
