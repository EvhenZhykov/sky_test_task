<?php

namespace App\SkyBundle\Command;

use App\SkyBundle\Entity\Star;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FillStarTableCommand extends Command
{
    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('fill-star-table')
            ->setDescription('This command fills star table with data')
            ->setHelp('Run this command to fill star table with data.');
    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Start');

        $em = $this->container->get('doctrine.orm.entity_manager');

        $galaxies = [
            'Galaxy A',
            'Galaxy B',
            'Galaxy C',
            'Galaxy D',
            'Galaxy F'
        ];

        $galaxiesAtoms = [];
        foreach ($galaxies as $galaxy) {
            for ($i = 0; $i < 50; $i++) {
                if ($galaxy === 'Galaxy A') {
                    $galaxiesAtoms[$galaxy][] = random_int(1, 40);
                }
                if ($galaxy === 'Galaxy B') {
                    $galaxiesAtoms[$galaxy][] = random_int(40, 100);
                }
                if ($galaxy === 'Galaxy C') {
                    $galaxiesAtoms[$galaxy][] = random_int(100, 172);
                }
                if ($galaxy === 'Galaxy D') {
                    $galaxiesAtoms[$galaxy][] = random_int(1, 40);
                }
                if ($galaxy === 'Galaxy F') {
                    $galaxiesAtoms[$galaxy][] = random_int(40, 100);
                }
            }
        }

        $galaxiesStars = [];
        foreach ($galaxies as $galaxy) {
            for ($i = 1; $i <= 1000; $i++) {
                $galaxiesStars[$galaxy][] = 'Star ' . $i . ' of ' . $galaxy;
            }
        }

        foreach ($galaxiesStars as $galaxy => $stars) {
            foreach ($stars as $galaxyStar) {
                $star = new Star;
                $star->setName($galaxyStar);
                $star->setGalaxy($galaxy);
                $star->setRadius(random_int(10000, 100000000));
                $star->setTemperature(random_int(3000, 10000));
                $star->setRotationFrequency(random_int(1, 100));
                $randKeys = array_rand($galaxiesAtoms[$galaxy], 10);
                $atomsFound = [];
                foreach ($randKeys as $key) {
                    $atomsFound[] = $galaxiesAtoms[$galaxy][$key];
                }
                $star->setAtomsFound($atomsFound);

                $em->persist($star);
            }
        }

        $em->flush();

        $output->writeln('End');

        return Command::SUCCESS;
    }
}