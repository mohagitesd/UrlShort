<?php

namespace App\Service;

use Faker;
use App\DTO\ShortLinkDTO;
use App\Entity\ShortLink;
use App\Repository\ShortLinkRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ShortLinkService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ShortLinkRepository $shortLinkRepository,
        private TagRepository $tagRepository,
    ) {}

    public function createShortLink(ShortLinkDTO $dto): ShortLink {
        $shortLink = new ShortLink();
        $shortLink->setUrl($dto->url);
        if ($dto->shortCode === null) {
            $shortLink->setShortCode($this->generateShortLink());
        } else {
            // Make sure it doesn't already exist, otherwise throw 409
            // throw new HttpException(Response::HTTP_CONFLICT, 'Short code already exists');
            $shortLink->setShortCode($dto->shortCode);
        }

        $shortLink
            ->setMaxVisits($dto->maxVisits)
            ->setValidOn($dto->validOn)
            ->setExpiresAt($dto->expiresAt);

        $this->em->persist($shortLink);
        $this->em->flush();

        return $shortLink;
    }

    public function updateShortLink(ShortLink $shortLink, ShortLinkDTO $dto): ShortLink {
        $shortLink
            ->setShortCode($dto->shortCode)
            ->setUrl($dto->url)
            ->setMaxVisits($dto->maxVisits)
            ->setValidOn($dto->validOn)
            ->setExpiresAt($dto->expiresAt);
        
            // Gérer les tags
        if (!empty($dto->tags)) {
            $shortLink->getTags()->clear(); // Supprime les anciens tags
            $tags = $this->tagRepository->findBy(['id' => $dto->tags]);

            if (count($dto->tags) !== count($tags)) {
                throw new HttpException(Response::HTTP_BAD_REQUEST, 'Tags count does not match');
            }

            foreach ($tags as $tag) {
                $shortLink->addTag($tag);
            } 
        
        }
        $this->em->flush();
        return $shortLink;
    }

    public function deleteShortLink(ShortLink $shortLink): void {
        $this->em->remove($shortLink);
        $this->em->flush();
    }

    public function getShortLink(int $id): ShortLink {
        return $this->shortLinkRepository->find($id);
        
    }

    public function getShortLinks(): array {
        return $this->shortLinkRepository->findAll();
    }

    public function generateShortLink(): string {
        $faker = Faker\Factory::create();
        $code = $faker->regexify('[A-Za-z0-9]{8}');
        // Check that code doesn't already exist
        // If it does, generate a new one
        return $code;
    }
}