<?php

namespace App\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\ApuestaRepository;
use App\Exception\InvalidException;
use App\Service\Apuesta;

class ApuestaFunctionalTest extends WebTestCase
{
    private ApuestaRepository $apuestaRepository;
    private static Apuesta $srvcApuesta;

    protected function setUp(): void {
        $this->apuestaRepository = static::getContainer()->get(ApuestaRepository::class);
        self::$srvcApuesta = static::getContainer()->get(Apuesta::class);
    }

    public static function apuestasValidasProvider(): array {
        return [
            [
                [
                    "email" => "fernandodf91@gmail.com",
                    "nombre" => "Fernando Frontera",
                    "kills" => 27
                ]
            ],
            [
                [
                    "email" => "usuario@lvp.global",
                    "nombre" => "Nombre Apellido",
                    "kills" => 0
                ]
            ],
            [
                [
                    "email" => "email@valido.com",
                    "nombre" => "Nombre válido",
                    "kills" => 42
                ]
            ]
        ];
    }

    public static function apuestasProvider(): array {
        $data = array_merge(...self::apuestasValidasProvider());

        return array_map(function($dataApuesta) {
            self::$srvcApuesta->guardar(
                self::$srvcApuesta->crearApuesta($dataApuesta)
            );
        }, $data);
    }

    public function apuestasInvalidasProvider(): array {
        return [
            [
                [
                    "email" => "email_invalido",
                    "nombre" => "Nombre válido",
                    "kills" => 27
                ],
                InvalidException::class
            ],
            [
                [
                    "email" => "email@valido.com",
                    "kills" => 27
                ],
                InvalidException::class
            ],
            [
                [
                    "email" => "email@valido.com",
                    "nombre" => "Nombre válido",
                    "kills" => -1
                ],
                InvalidException::class
            ],
            [
                [
                    "email" => "email@valido.com",
                    "nombre" => "Nombre válido",
                    "kills" => "boom"
                ],
                \TypeError::class
            ]
        ];
    }

    /**
     * @dataProvider apuestasValidasProvider
     * @param $data
     * @throws InvalidException
     */

    public function testCrearApuesta($data) {
        $apuesta = self::$srvcApuesta->crearApuesta($data);

        $this->assertSame($data["email"], $apuesta->getApostador()->getEmail());
        $this->assertSame($data["nombre"], $apuesta->getApostador()->getNombre());
        $this->assertSame($data["kills"], $apuesta->getKills());

        return 3;
    }

    /**
     * @dataProvider apuestasInvalidasProvider
     * @param $data
     * @param $exceptionEsperada
     * @throws InvalidException
     */

    public function testCrearApuestaInvalida($data, $exceptionEsperada) {
        $this->expectException($exceptionEsperada);
        self::$srvcApuesta->crearApuesta($data);
    }

    /**
     * @dataProvider apuestasValidasProvider
     */

    public function testSaveApuesta($data) {
        $apuesta = self::$srvcApuesta->crearApuesta($data);
        self::$srvcApuesta->guardar($apuesta);

        $apuestaEncontrada = $this->apuestaRepository->find($apuesta->getId());

        $this->assertEquals($apuesta, $apuestaEncontrada);
    }

    public function testGanador() {
        self::apuestasProvider();

        $this->assertEquals(
            self::$srvcApuesta->getGanador(10)->getEmail(),
            "usuario@lvp.global"
        );
    }
}