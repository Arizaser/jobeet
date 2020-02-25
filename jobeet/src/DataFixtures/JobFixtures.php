<?php

namespace App\DataFixtures;

use App\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class JobFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i <= 20; $i++) {
            $jobSensioLabs = new Job();
            $jobSensioLabs->setCategory($manager->merge($this->getReference('category-programming')));
            $jobSensioLabs->setType('full-time');
            $jobSensioLabs->setCompany('Sensio Labs' . $i);
            $jobSensioLabs->setLogo('sensio-labs.gif');
            $jobSensioLabs->setUrl('http://www.sensiolabs.com/');
            $jobSensioLabs->setPosition('Web Developer');
            $jobSensioLabs->setLocation('Paris, France');
            $jobSensioLabs->setDescription('You\'ve already developed websites with symfony and you want to work with Open-Source technologies. You have a minimum of 3 years experience in web development with PHP or Java and you wish to participate to development of Web 2.0 sites using the best frameworks available.');
            $jobSensioLabs->setHowToApply('Send your resume to fabien.potencier [at] sensio.com');
            $jobSensioLabs->setPublic(true);
            $jobSensioLabs->setActivated(true);
            $jobSensioLabs->setToken('job_sensio_labs_' . $i);
            $jobSensioLabs->setEmail('job' . $i . '@example.com');
            $jobSensioLabs->setExpiresAt(new \DateTime('+30 days'));
            $manager->persist($jobSensioLabs);
        }

        for ($i = 0; $i <= 20; $i++) {
            $jobExtremeSensio = new Job();
            $jobExtremeSensio->setCategory($manager->merge($this->getReference('category-design')));
            $jobExtremeSensio->setType('part-time');
            $jobExtremeSensio->setCompany('Extreme Sensio' . $i);
            $jobExtremeSensio->setLogo('extreme-sensio.gif');
            $jobExtremeSensio->setUrl('http://www.extreme-sensio.com/');
            $jobExtremeSensio->setPosition('Web Designer');
            $jobExtremeSensio->setLocation('Paris, France');
            $jobExtremeSensio->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in.');
            $jobExtremeSensio->setHowToApply('Send your resume to fabien.potencier [at] sensio.com');
            $jobExtremeSensio->setPublic(true);
            $jobExtremeSensio->setActivated(true);
            $jobExtremeSensio->setToken('job_extreme_sensio_'.$i);
            $jobExtremeSensio->setEmail('job' . $i . '@example.com');
            $jobExtremeSensio->setExpiresAt(new \DateTime('+30 days'));
            $manager->persist($jobExtremeSensio);
        }
        for ($i = 0; $i <= 20; $i++) {
            $jobExtremeSensio1 = new Job();
            $jobExtremeSensio1->setCategory($manager->merge($this->getReference('category-design')));
            $jobExtremeSensio1->setType('part-time');
            $jobExtremeSensio1->setCompany('Trabajo dia hoy');
            $jobExtremeSensio1->setLogo('extreme-sensio.gif');
            $jobExtremeSensio1->setUrl('http://www.extreme-sensio.com/');
            $jobExtremeSensio1->setPosition('Diseñador Web');
            $jobExtremeSensio1->setLocation('Madrid, España');
            $jobExtremeSensio1->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in.');
            $jobExtremeSensio1->setHowToApply('Send your resume to fabien.potencier [at] sensio.com');
            $jobExtremeSensio1->setPublic(true);
            $jobExtremeSensio1->setActivated(true);
            $jobExtremeSensio1->setToken('job_extreme_sensio1_'.$i);
            $jobExtremeSensio1->setEmail('job'.$i.'@example.com');
            $jobExtremeSensio1->setExpiresAt(new \DateTime('-30 days'));
            $manager->persist($jobExtremeSensio1);
        }
        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
