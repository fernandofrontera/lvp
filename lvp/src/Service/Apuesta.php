<?php


namespace App\Service;


use App\Entity\Apostador;
use App\Entity\Apuesta as ApuestaEntity;
use App\Exception\InvalidException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Apuesta {

    private $em, $validator;

    public function __construct(ValidatorInterface $validator, EntityManagerInterface $em) {
        $this->em = $em;
        $this->validator = $validator;
    }

    /**
     * @param array $data
     * @return ApuestaEntity
     * @throws InvalidException
     */
    public function createApuesta($data) {
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

    private function faltanCampos(array $data, array $camposEsperados) {
        return $camposEsperados !== array_keys($data);
    }

    /**
     * @param ApuestaEntity $apuesta
     * @return ApuestaEntity
     * @throws \Exception
     */

    public function save(ApuestaEntity $apuesta) {
        $this->em->beginTransaction();
        try {
            $this->em->persist($apuesta);
            $this->em->flush();
            $this->em->commit();
        } catch(\Exception $e) {
            $this->em->rollback();
            throw $e;
        }
        return $apuesta;
    }

}