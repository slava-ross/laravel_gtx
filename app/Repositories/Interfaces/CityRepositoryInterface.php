<?php

namespace App\Repositories\Interfaces;

interface CityRepositoryInterface
{
    public function getCityNameByIP(string $clientIPAddress);
    public function getCityByName(string $cityName);
    public function getCityById(int $cityId);
    public function allToArray(string $field);
    public function getCitesOfComments();
    public function getMostCommentedCitiesPrior(int $limit);
    public function create(array $attributes);
}
