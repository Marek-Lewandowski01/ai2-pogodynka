<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\ForecastRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherController extends AbstractController
{
    #[Route('/weather/{country}/{name}', name: 'app_weather_city', requirements: ['id' => '\d+'])]
    public function name(
        #[MapEntity(mapping: ['name' => 'name', 'country' => 'country'])]
        Location $location,
        ForecastRepository $repository): Response
    {
        $forecasts = $repository->findByLocation($location);

        return $this->render('weather/name.html.twig', [
            'location' => $location,
            'forecasts' => $forecasts,
        ]);

    }
}
