<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\Attributes\DataProvider;
use App\Entity\Forecast;
use PHPUnit\Framework\TestCase;

class ForecastTest extends TestCase
{
    #[DataProvider('dataGetFahrenheit')]
    public function testGetFahrenheit(string $celsius, string $expectedFahrenheit): void
    {
        $forecast = new Forecast();
        $forecast->setTemperatureC($celsius);

        $this->assertSame(
            (float) $expectedFahrenheit,
            $forecast->getFahrenheit()
        );
    }

    public static function dataGetFahrenheit(): array
    {
        return [
            ['0', '32'],
            ['-100', '-148'],
            ['100', '212'],
            ['0.5', '32.9'],
            ['-40', '-40'],
            ['20', '68'],
            ['30', '86'],
            ['-12.3', '9.86'],
            ['37.5', '99.5'],
            ['15', '59'],
        ];
    }


}


