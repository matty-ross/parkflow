<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\User;
use App\Entity\Vehicle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private const MAX_USERS = 100;
    private const MAX_VEHICLES_PER_USER = 3;
    private const MAX_EVENTS_PER_USER = 10;

    private const LICENSE_PLATE_REGEX = '[A-Z]{2}-[0-9]{3}-[A-Z]{2}';

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function load(ObjectManager $manager): void
    {
        $fakerEn = Factory::create('en_US');
        $fakerSk = Factory::create('sk_SK');

        for ($userIndex = 0; $userIndex < self::MAX_USERS; ++$userIndex) {
            $faker = $userIndex % 2 === 0 ? $fakerEn : $fakerSk;
            $gender = $faker->boolean(50) ? 'female' : 'male';

            $user = new User()
                ->setEmail($faker->unique()->email())
                ->setFirstName($faker->firstName($gender))
                ->setLastName($faker->lastName($gender))
                ->setRoles([User::ROLE_USER])
                ->setIsActive($faker->boolean(80))
            ;
            $user->setPassword($this->passwordHasher->hashPassword($user, $faker->password()));

            $manager->persist($user);

            $licensePlates = [];
            $vehiclesCount = random_int(min: 0, max: self::MAX_VEHICLES_PER_USER);
            for ($vehicleIndex = 0; $vehicleIndex < $vehiclesCount; ++$vehicleIndex) {
                $vehicle = new Vehicle()
                    ->setLicensePlate($faker->unique()->regexify(self::LICENSE_PLATE_REGEX))
                    ->setDescription($faker->boolean(30) ? $faker->realText(300) : null)
                ;
                $user->addVehicle($vehicle);

                $licensePlates[] = $vehicle->getLicensePlate();

                $manager->persist($vehicle);
            }

            $eventsCount = random_int(min: 0, max: self::MAX_EVENTS_PER_USER);
            for ($eventIndex = 0; $eventIndex < $eventsCount; ++$eventIndex) {
                $recognizedLicensePlate = $licensePlates && $faker->boolean(70);

                $event = new Event()
                    ->setAction($faker->boolean(50) ? Event::ACTION_ENTRY : Event::ACTION_EXIT)
                    ->setLicensePlate($recognizedLicensePlate ? $faker->randomElement($licensePlates) : $faker->regexify(self::LICENSE_PLATE_REGEX))
                    ->setSnapshotUuid($faker->boolean(60) ? $faker->unique()->uuid() : null)
                ;
                if ($recognizedLicensePlate) {
                    $user->addEvent($event);
                }

                $manager->persist($event);
            }
        }

        $manager->flush();
    }
}
