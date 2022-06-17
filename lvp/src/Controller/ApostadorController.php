<?php


namespace App\Controller;

use App\Service\Apuesta;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ApostadorController
 * @package App\Controller
 * @Route("/apostadores")
 */

class ApostadorController extends AbstractController    {

    /**
     * @Route("/ganador/{numeroGanador}")
     */

    public function getGanador(Apuesta $apuesta, int $numeroGanador) {
        $ganador = $apuesta->getGanador($numeroGanador);
        return $this->json($ganador);
    }
}