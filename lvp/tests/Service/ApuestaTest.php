<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Service\Apuesta;
use App\Repository\ApuestaRepository;
use App\Entity\Apostador;
use App\Entity\Apuesta as ApuestaEntity;
use App\Exception\InvalidException;

class ApuestaTest extends WebTestCase
{
    /**
     * @var Apuesta $srvcApuesta
     * @var ApuestaRepository $apuestaRepository
     */
    private $srvcApuesta, $apuestaRepository;

    protected function setUp(): void {
        $this->srvcApuesta = static::getContainer()->get(Apuesta::class);
        $this->apuestaRepository = static::getContainer()->get(ApuestaRepository::class);
    }

    /**
     * @dataProvider apuestasValidasProvider
     * @param $data
     * @throws InvalidException
     */

    public function testCrearApuesta($data): ApuestaEntity {
        $apuesta = $this->srvcApuesta->createApuesta($data);

        $this->assertSame($data["email"], $apuesta->getApostador()->getEmail());
        $this->assertSame($data["nombre"], $apuesta->getApostador()->getNombre());
        $this->assertSame($data["kills"], $apuesta->getKills());

        return $apuesta;
    }

    /**
     * @dataProvider apuestasInvalidasProvider
     * @param $data
     * @param $exceptionEsperada
     * @throws InvalidException
     */

    public function testCrearApuestaInvalida($data, $exceptionEsperada) {
        $this->expectException($exceptionEsperada);
        $this->srvcApuesta->createApuesta($data);
    }

    public function apuestasValidasProvider(): array {
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
                TypeError::class
            ]
        ];
    }

    /**
     * @dataProvider apuestasValidasProvider
     */

    public function testSaveApuesta($data) {
        $apuesta = $this->srvcApuesta->createApuesta($data);
        $this->srvcApuesta->save($apuesta);

        $apuestaEncontrada = $this->apuestaRepository->findOneBy(["email" => $apuesta->getApostador()->getEmail()]);

        $this->assertEquals($apuesta, $apuestaEncontrada);
    }
}