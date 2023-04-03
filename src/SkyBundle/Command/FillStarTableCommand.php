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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $output->writeln('Start');

        $starsJsonData = '[
            {
              "name": "Sun",
              "galaxy": "Milky Way",
              "radius": 695700,
              "temperature": 5500,
              "rotation_frequency": 0.0000027,
              "atoms_found": ["Hydrogen", "Helium", "Oxygen"]
            },
            {
              "name": "Betelgeuse",
              "galaxy": "Milky Way",
              "radius": 887000000,
              "temperature": 3500,
              "rotation_frequency": 0.000000015,
              "atoms_found": ["Hydrogen", "Helium", "Carbon"]
            },
            {
              "name": "Antares",
              "galaxy": "Milky Way",
              "radius": 888000000,
              "temperature": 3500,
              "rotation_frequency": 0.000000014,
              "atoms_found": ["Hydrogen", "Helium", "Carbon"]
            },
            {
              "name": "Vega",
              "galaxy": "Milky Way",
              "radius": 2362400,
              "temperature": 9520,
              "rotation_frequency": 0.00000028,
              "atoms_found": ["Hydrogen", "Helium", "Carbon"]
            },
            {
              "name": "Sirius",
              "galaxy": "Milky Way",
              "radius": 1713600,
              "temperature": 9940,
              "rotation_frequency": 0.0000012,
              "atoms_found": ["Hydrogen", "Helium", "Oxygen"]
            },
            {
              "name": "Alpha Centauri A",
              "galaxy": "Milky Way",
              "radius": 1227000,
              "temperature": 5500,
              "rotation_frequency": 0.0000013,
              "atoms_found": ["Hydrogen", "Helium", "Oxygen"]
            },
            {
              "name": "Alpha Centauri B",
              "galaxy": "Milky Way",
              "radius": 865400,
              "temperature": 5300,
              "rotation_frequency": 0.0000019,
              "atoms_found": ["Hydrogen", "Helium", "Oxygen"]
            },
            {
              "name": "Andromeda",
              "galaxy": "Andromeda Galaxy",
              "radius": 282000000,
              "temperature": 3900,
              "rotation_frequency": 0.000000071,
              "atoms_found": ["Hydrogen", "Helium", "Carbon"]
            },
            {
              "name": "Triangulum",
              "galaxy": "Triangulum Galaxy",
              "radius": 33000,
              "temperature": 7700,
              "rotation_frequency": 0.0000033,
              "atoms_found": ["Hydrogen", "Helium", "Nitrogen"]
            },
            {
              "name": "Large Magellanic Cloud",
              "galaxy": "Large Magellanic Cloud",
              "radius": 17000,
              "temperature": 6500,
              "rotation_frequency": 0.0000018,
              "atoms_found": ["Hydrogen", "Helium", "Oxygen"]
            }
        ]';

        $em = $this->container->get('doctrine.orm.entity_manager');

        $starsData = json_decode($starsJsonData, true);

        foreach ($starsData as $starData) {
            $star = new Star;
            $star->setName($starData['name']);
            $star->setGalaxy($starData['galaxy']);
            $star->setRadius($starData['radius']);
            $star->setTemperature($starData['temperature']);
            $star->setRotationFrequency($starData['rotation_frequency']);
            $star->setAtomsFound($starData['atoms_found']);

            $em->persist($star);
        }

        $em->flush();

        $output->writeln('End');

        return Command::SUCCESS;
    }
}