<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Service\Apuesta;

class ApuestaUnitTest extends WebTestCase
{
    /**
     * @var Apuesta
     */
    private $srvcApuesta;

    protected function setUp(): void {
        $this->srvcApuesta = static::getContainer()->get(Apuesta::class);
    }

    public function testFaltanCampos() {
        $jugador = [
            "campeones" => ["Anivia", "Ryze", "Brand", "Bardo", "Jhin"],
            "invocador" => "coolDevil",
            "nivel" => 30
        ];

        $servidor = [
            "region" => "LAS",
            "idioma" => "español",
            "jugadores" => [$jugador]
        ];

        $this->assertTrue(
            !$this->srvcApuesta->faltanCampos($jugador, ["nivel", "campeones", "invocador"])
        );

        $this->assertTrue(
            $this->srvcApuesta->faltanCampos($jugador, ["nivel", "campeones", "invocador", "historial"])
        );

        $this->assertTrue(
            !$this->srvcApuesta->faltanCampos($servidor, ["region", "idioma", "jugadores"])
        );

        $this->assertTrue(
            $this->srvcApuesta->faltanCampos($servidor, ["partidas"])
        );
    }
}