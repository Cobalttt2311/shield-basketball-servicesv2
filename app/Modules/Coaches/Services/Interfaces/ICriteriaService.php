<?php

namespace App\Modules\Coaches\Services\Interfaces;

interface ICriteriaService
{
    public function getMyCriteria();
    public function createCriteria(array $data);
    public function createSubCriteria(array $data);
}