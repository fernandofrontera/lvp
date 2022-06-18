<?php


namespace App\Service;


use App\Repository\ApuestaRepository;

class Stats
{
    private ApuestaRepository $apuestaRepository;

    public function __construct(ApuestaRepository $apuestaRepository) {
        $this->apuestaRepository = $apuestaRepository;
    }

    public function getTotalApuestas() {
        // esta solución no es tan óptima como una query que sólo devuelva la cantidad de registros
        // pero se optó por esta solución en aras de la simplicidad y entendimiento del código
        return count($this->apuestaRepository->findAll());
    }

    public function getMayorKills() {
        return $this->getFirstApuestaKillsOrderedBy("DESC");
    }

    public function getMenorKills() {
        return $this->getFirstApuestaKillsOrderedBy("ASC");
    }

    private function getFirstApuestaKillsOrderedBy($order) {
        return $this->apuestaRepository->findOneBy([], ["kills" => $order])->getKills();
    }

    public function getKillsPromedio() {
        return $this->apuestaRepository->getPromedioKills();
    }

}