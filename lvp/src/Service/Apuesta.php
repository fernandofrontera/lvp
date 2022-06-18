<?php


namespace App\Service;


use App\Entity\Apostador;
use App\Entity\Apuesta as ApuestaEntity;
use App\Exception\InvalidException;
use App\Repository\ApuestaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Apuesta {

    private $validator;
    private ApuestaRepository $apuestaRepository;

    public function __construct(ValidatorInterface $validator, ApuestaRepository $apuestaRepository) {
        $this->apuestaRepository = $apuestaRepository;
        $this->validator = $validator;
    }

    /**
     * @param array $data
     * @return ApuestaEntity
     * @throws InvalidException
     */
    public function crearApuesta($data) {
        $camposEsperados = ["email", "nombre", "kills"];

        if($this->faltanCampos($data, $camposEsperados)) {
            throw new InvalidException("Campos faltantes.");
        } else
            $apuesta = (new ApuestaEntity())
                ->setKills($data["kills"])
                ->setApostador(new Apostador($data))
            ;

        $errores = $this->validator->validate($apuesta);

        if(count($errores))
            throw new InvalidException($errores[0]->getMessage());

        else return $apuesta;
    }

    /**
     * @param array $data
     * @param string[] $camposEsperados
     * @return bool
     */

    public function faltanCampos(array $data, array $camposEsperados) {
        return !empty(
            array_diff($camposEsperados, array_keys($data))
        );
    }

    /**
     * @param ApuestaEntity $apuesta
     * @return ApuestaEntity
     * @throws \Exception
     */

    public function guardar(ApuestaEntity $apuesta) {
        try {
            $this->apuestaRepository->add($apuesta, true);
            return $apuesta;
        } catch(\Exception $e) {
            throw $e;
            return null;
        }
    }

    public function getGanador(int $killsFinales) {
        $apuestaMasCercana = $this->apuestaRepository->getApuestaMasCercana($killsFinales);
        return $apuestaMasCercana->getApostador();
    }

}