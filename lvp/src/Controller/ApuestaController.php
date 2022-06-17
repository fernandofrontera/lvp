<?php

namespace App\Controller;

use App\Exception\InvalidException;
use App\Repository\ApuestaRepository;
use App\Service\Apuesta;
use App\Service\Stats;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

/**
 * Class ApuestaController
 * @Route("/apuestas")
 */

class ApuestaController extends AbstractController {

    /**
     * @Route(methods={"POST"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */

    public function saveApuesta(Request $request, Apuesta $srvcApuesta) {

        $data = $request->toArray();
        try {
            $apuesta = $srvcApuesta->createApuesta($data);
            $srvcApuesta->save($apuesta);
            $content = $apuesta->getId();
            $status = Response::HTTP_OK;
        } catch(InvalidException $exception) {
            $content = $exception->getMessage();
            $status = $exception->getCode();
        } catch(\Exception $exception) {
            $content = "Hubo un error al intentar guardar la apuesta.";
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }
        return $this->json($content, $status);
    }

    /**
     * @Route()
     * @param Apuesta $srvcApuesta
     * @return mixed
     */

    public function getApuestas(ApuestaRepository $apuestaRepository) {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        try {
            $apuestas = $apuestaRepository->findAll();
            $content = $serializer->normalize($apuestas, "json", [
                "groups" => ["apuesta"],
                AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true
            ]);
            $status = Response::HTTP_OK;
        } catch(\Exception $e) {
            var_dump($e->getMessage());die;
        }
        return $this->json($content, $status);
    }

    /**
     * @Route("/stats")
     */

    public function stats(Stats $stats) {
        try {
            $content = [
                "total_apuestas" => $stats->getTotalApuestas(),
                "kills_maxima" => $stats->getMayorKills(),
                "kills_minima" => $stats->getMenorKills(),
                "kills_promedio" => $stats->getKillsPromedio()
            ];
            $status = Response::HTTP_OK;
        } catch(\Exception $e) {

        }

        return $this->json($content, $status);
    }

    /**
     * @Route("/ganador")
     */

    public function ganador(ApuestaRepository $apuestaRepository) {
        $ganador = $apuestaRepository->getApuestaMasCercana(6);
        $this->json($ganador->getApostador()->getId());
    }

}