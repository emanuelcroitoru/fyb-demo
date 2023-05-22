<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\ProjectFactory;
use App\Factory\ProjectMilestonesFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createMany(7);
        ProjectFactory::createMany(20, function() {
            return [
                'user' => UserFactory::random()
            ];
        });
        ProjectMilestonesFactory::createMany(30, function() {
            return [
                'project' => ProjectFactory::random()
            ];
        });

        $manager->flush();
    }

}
