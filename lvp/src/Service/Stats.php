<?php


namespace App\Service;


use App\Repository\ApuestaRepository;

class Stats
{
    private $apuestaRepository;

    public function __construct(ApuestaRepository $apuestaRepository) {
        $this->apuestaRepository = $apuestaRepository;
    }

    public function getTotalApuestas() {
        return count($this->apuestaRepository->findAll());
    }

    public function getMayorKills() {
        return $this->getKillsFirstApuestaOrderBy("DESC");
    }

    public function getMenorKills() {
        return $this->getKillsFirstApuestaOrderBy("ASC");
    }

    private function getKillsFirstApuestaOrderBy($order) {
        return $this->apuestaRepository->findOneBy([], ["kills" => $order])->getKills();
    }

    public function getKillsPromedio() {
        return $this->apuestaRepository->getPromedioKills();
    }

}