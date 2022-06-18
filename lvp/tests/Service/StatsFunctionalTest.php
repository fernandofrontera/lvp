<?php

namespace App\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Service\Stats;

class StatsFunctionalTest extends WebTestCase
{
    private Stats $stats;

    protected function setUp(): void {
        $this->stats = static::getContainer()->get(Stats::class);
        ApuestaFunctionalTest::apuestasProvider();
    }

    public function testMaxKills() {
        $this->assertEquals($this->stats->getMayorKills(), 42);
    }

    public function testMinKills() {
        $this->assertEquals($this->stats->getMenorKills(), 0);
    }

    public function testPromedioKills() {
        $this->assertEquals($this->stats->getKillsPromedio(), 23);
    }

    public function testTotalApuestas() {
        $this->assertEquals($this->stats->getTotalApuestas(), 3);
    }

}