<?php

namespace App\DataFixtures;

use App\Entity\ShortLink;
use Doctrine\Persistence\ObjectManager;

class ShortLinkFixtures extends BaseFixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 60; $i += 1) {
            $shortLink = new ShortLink();
            $shortLink
                ->setUrl($this->faker->url())
                ->setShortCode(
                    $this->faker->bothify(
                        str_repeat('*', $this->faker->numberBetween(5, 10))
                    )
                )
                ->setMaxVisits(
                    $this->faker->randomElement([null, $this->faker->numberBetween(1, 50)])
                )
                ->setValidOn(
                    $this->faker->randomElement([
                        null,
                        \DateTimeImmutable::createFromMutable($this->faker->dateTimeInInterval('-1 day', '+1 day')),
                    ])
                )
                ->setCreatedAt(
                    \DateTimeImmutable::createFromMutable($this->faker->dateTimeInInterval('-1 year')),
                );
            $manager->persist($shortLink);
        }

        $manager->flush();
    }
}
