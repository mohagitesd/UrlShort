<?php

namespace App\Controller;

use App\DTO\CreateShortLinkDTO;
use App\DTO\EditShortLinkDTO;
use App\DTO\GetShortLinksDTO;
use App\Service\ShortLinkService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;


final class MainController extends AbstractController{
    #[Route('/{shortCode}', methods: ['GET'], name: 'redirect_short_link')]
    public function redirectToUrl(
        string $shortCode,
        Request $request,
        ShortLinkService $shortLinkService,
    ): Response {
        $shortLink = $shortLinkService->processVisit(
            shortCode: $shortCode,
            ip: $request->getClientIp(),
            userAgent: $request->headers->get('User-Agent', '')
        );

        $this->redirect($shortLink->getUrl());

        return new RedirectResponse(
            url: $shortLink->getUrl(),
            status: Response::HTTP_MOVED_PERMANENTLY,
            
        );
    }
}