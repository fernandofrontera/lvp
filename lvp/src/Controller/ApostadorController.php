<?php


namespace App\Controller;

use App\Repository\ApostadorRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ApuestaRepository;

/**
 * Class ApostadorController
 * @package App\Controller
 * @Route("/apostadores")
 */

class ApostadorController extends AbstractController    {

    /**
     * @Route("/ganador/{numeroGanador}")
     */

    public function getGanador(ApuestaRepository $apuestaRepository, int $numeroGanador) {
        $ganador = $apuestaRepository->getApuestaMasCercana($numeroGanador)->getApostador();
        return $this->json($ganador);
    }
}