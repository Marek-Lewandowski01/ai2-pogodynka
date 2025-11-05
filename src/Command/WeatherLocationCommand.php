<?php

namespace App\Command;

use App\Repository\LocationRepository;
use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:location',
    description: 'Wyświetla prognozę pogody dla danej lokalizacji',
)]
class WeatherLocationCommand extends Command
{
    private WeatherUtil $weatherUtil;
    private LocationRepository $locationRepository;

    public function __construct(WeatherUtil $weatherUtil, LocationRepository $locationRepository)
    {
        parent::__construct();
        $this->weatherUtil = $weatherUtil;
        $this->locationRepository = $locationRepository;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('location-id', InputArgument::REQUIRED, 'ID lokalizacji')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $locationId = $input->getArgument('location-id');
        $location = $this->locationRepository->findById($locationId);

        if (!$location) {
            $io->error(sprintf('Lokalizacja o ID %d nie została znaleziona.', $locationId));
            return Command::FAILURE;
        }

        $forecasts = $this->weatherUtil->getWeatherForLocation($location);
        if (empty($forecasts)) {
            $io->warning('Brak prognoz pogody dla tej lokalizacji.');
            return Command::SUCCESS;
        }
        $io->writeln(sprintf('Location: %s', $location->getName()));
        $io->table(['Date', 'Temperature'], array_map(fn($f) => [
            $f->getDate()->format('Y-m-d'),
            $f->getTemperatureC(),
        ], $forecasts));

        return Command::SUCCESS;

    }
}
