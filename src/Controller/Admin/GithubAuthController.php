<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Annotation\Route;


class GithubAuthController extends AbstractController
{
    #[Route('/auth/github', name: 'github_authorize')]
    public function authorize(
        HttpClientInterface $httpClient,
        #[MapQueryParameter('code')]
        ?string $authorizationCode    
    ): Response
    {
        if (!$authorizationCode) {
            $query = http_build_query( [
                'client_id' => $this->getParameter('github.client_id'),
                'redirect_uri' => $this->getParameter('github.redirect_uri'),
            ]);
            $authorizeUrl ='https://github.com/login/oauth/authorize?'.$query;
            
            return $this->redirect($authorizeUrl);
        }

        $token = $httpClient->request('POST', 'https://github.com/login/oauth/access_token', [
            'body' => [
                'client_id' => $this->getParameter('github.client_id'),
                'client_secret' => $this->getParameter('github.client_secret'),
                'code' => $authorizationCode,
                'redirect_uri' => $this->getParameter('github.redirect_uri'),
            ],
            'headers' => [
                'Accept' => 'application/json',
            ],
        ])->toArray()['access_token'];

       $userData = $httpClient->request('GET', 'https://api.github.com/user', [
            'headers' => [ 'Authorization' => 'Bearer '.$token],
        ])->toArray();

        dd($userData);

        // Make sure that the user exists in the database.
        $user = $userRepository->findOneBy(['email' => $userData['email']]);
        if (!$user) {
            throw new UnauthorizedHttpException('User does not exist.');
            // Maybe we could create a user with no password if it doesn't exist ?
        }
        // Create a custom JWT with the user's data
        $jwt = $jwtManager->createFromPayload($user, [
            'avatar_url' => $userData['avatar_url'],
 

            'company' => $userData['company'],
 

            'location' => $userData['location'],
 

        ]);
 


 

        return $this->json(['token' => $jwt]);
 

    }
 

}
  