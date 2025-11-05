<?php
namespace App\Command;

use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:city',
    description: 'Wyświetla prognozę pogody na podstawie kodu kraju i nazwy miasta',
)]
class WeatherCityCommand extends Command
{
    private WeatherUtil $weatherUtil;
    public function __construct(WeatherUtil $weatherUtil)
    {
        parent::__construct();
        $this->weatherUtil = $weatherUtil;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('countryCode', InputArgument::REQUIRED, 'Kod kraju (np. PL)')
            ->addArgument('city', InputArgument::REQUIRED, 'Nazwa miasta (np. Szczecin)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $countryCode = $input->getArgument('countryCode');
        $city = $input->getArgument('city');

        $io->writeln(sprintf('Pobieranie prognozy dla: %s, %s...', $city, $countryCode));
        $forecasts = $this->weatherUtil->getWeatherForCountryAndCity($countryCode, $city);

        if (empty($forecasts)) {
            $io->warning(sprintf('Nie znaleziono prognoz pogody dla: %s, %s', $city, $countryCode));
            return Command::SUCCESS;
        }

        $locationName = $forecasts[0]->getLocation()->getName();

        $io->writeln(sprintf('Location: %s', $locationName));

        $io->table(['Date', 'Temperature'], array_map(fn($f) => [
            $f->getDate()->format('Y-m-d'),
            $f->getTemperatureC(),
        ], $forecasts));

        return Command::SUCCESS;
    }
}
