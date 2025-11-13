<?php

namespace App\Controller;

use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api', methods: ['GET'])]
    public function index(
        #[MapQueryParameter] string $country,
        #[MapQueryParameter] string $city,
        WeatherUtil $weatherUtil,
        #[MapQueryParameter] string $format = 'json',
        #[MapQueryParameter] bool $twig = false,
    ): Response
    {
        // Walidacja formatu
        $format = strtolower($format);
        if (!in_array($format, ['json', 'csv'], true)) {
            return $this->json(
                ['error' => 'Unsupported format. Allowed: json, csv'],
                Response::HTTP_BAD_REQUEST
            );
        }

        // Pobranie prognozy pogody dla podanego kraju i miasta
        $forecasts = $weatherUtil->getWeatherForCountryAndCity($country, $city);

        // Sprawdzenie, czy są prognozy
        if (!$forecasts) {
            return $this->json(
                ['error' => 'No forecasts found for given location'],
                Response::HTTP_NOT_FOUND
            );
        }

        // Mapowanie encji na prostą tablicę (static jako optymalizacja pamięci, bo kontekst obiektu nie jest potrzebny)
        $forecastData = array_map(fn($f) => [
            'date' => $f->getDate()->format('Y-m-d'),
            'temperatureC' => $f->getTemperatureC(),
            'fahrenheit' => $f->getFahrenheit(),
        ], $forecasts);


        if ($twig) {
            if ($format === 'csv') {
                return $this->render('weather_api/index.csv.twig', [
                    'city' => $city,
                    'country' => $country,
                    'forecasts' => $forecastData,
                ]);
            }

            return $this->render('weather_api/index.json.twig', [
                'city' => $city,
                'country' => $country,
                'forecasts' => $forecastData,
            ]);
        }

        if ($format === 'csv') {
            $csv = "city,country,date,celsius,fahrenheit\n";

            foreach ($forecastData as $row) {
                $csv .= sprintf(
                    "%s,%s,%s,%s,%s\n",
                    $city,
                    $country,
                    $row['date'],
                    $row['temperatureC'],
                    $row['fahrenheit']
                );
            }

            return new Response(
                $csv,
                Response::HTTP_OK,
                [
                    'Content-Type' => 'text/csv; charset=UTF-8',
                    'Content-Disposition' => sprintf(
                        'attachment; filename="weather_%s_%s.csv"',
                        strtolower($city),
                        strtolower($country)
                    ),
                ]
            );
        }

        // Zwracany JSON
        return $this->json([
            'country' => $country,
            'city' => $city,
            'forecasts' => $forecastData,
        ], Response::HTTP_OK);
    }
}


