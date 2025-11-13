<?php

namespace App\Controller;

use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api', methods: ['GET'])]
    public function index(
        #[MapQueryParameter] string $country,
        #[MapQueryParameter] string $city,
        WeatherUtil $weatherUtil,
    ): JsonResponse
    {
        // Pobranie prognozy pogody dla podanego kraju i miasta
        $forecasts = $weatherUtil->getWeatherForCountryAndCity($country, $city);

        // Mapowanie encji na prostą tablicę
        $forecastData = array_map(fn($f) => [
            'date' => $f->getDate()->format('Y-m-d'),
            'temperatureC' => $f->getTemperatureC(),
        ], $forecasts);

        // Zwracany JSON
        return $this->json([
            'country' => $country,
            'city' => $city,
            'forecasts' => $forecastData,
        ]);
    }
}


