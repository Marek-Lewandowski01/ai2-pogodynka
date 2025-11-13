<?php

namespace App\Entity;

use App\Repository\ForecastRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ForecastRepository::class)]
class Forecast
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'forecasts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Location $location = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 0)]
    private ?string $temperatureC = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $humidity = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $uvIndex = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $windSpeed = null;

    #[ORM\Column(length: 2)]
    private ?string $windDirection = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getTemperatureC(): ?string
    {
        return $this->temperatureC;
    }

    public function setTemperatureC(string $temperatureC): static
    {
        $this->temperatureC = $temperatureC;

        return $this;
    }

    public function getHumidity(): ?int
    {
        return $this->humidity;
    }

    public function setHumidity(int $humidity): static
    {
        $this->humidity = $humidity;

        return $this;
    }

    public function getUvIndex(): ?int
    {
        return $this->uvIndex;
    }

    public function setUvIndex(int $uvIndex): static
    {
        $this->uvIndex = $uvIndex;

        return $this;
    }

    public function getWindSpeed(): ?int
    {
        return $this->windSpeed;
    }

    public function setWindSpeed(int $windSpeed): static
    {
        $this->windSpeed = $windSpeed;

        return $this;
    }

    public function getWindDirection(): ?string
    {
        return $this->windDirection;
    }

    public function setWindDirection(string $windDirection): static
    {
        $this->windDirection = $windDirection;

        return $this;
    }

    public function getFahrenheit(): float
    {
        return $this->getTemperatureC() * 9/5 + 32;
    }

}
