<?php

namespace App\Tests\Entity;

use App\Entity\Forecast;
use PHPUnit\Framework\TestCase;

class ForecastTest extends TestCase
{
    public function testGetFahrenheit(): void
    {
        $forecast = new Forecast();

        // 0°C = 32°F
        $forecast->setTemperatureC('0');
        $this->assertSame(32.0, $forecast->getFahrenheit());

        // -100°C = -148°F
        $forecast->setTemperatureC('-100');
        $this->assertSame(-148.0, $forecast->getFahrenheit());

        // 100°C = 212°F
        $forecast->setTemperatureC('100');
        $this->assertSame(212.0, $forecast->getFahrenheit());
    }

}

