<?php

namespace App\Modules\Coaches\Services\Interfaces;

interface IAhpCalculationService
{
    /**
     * Normalisasi Reciprocal
     */
    public function normalizeReciprocal(
        int $firstId,
        int $secondId,
        float $value
    ): array;

    /**
     * Min-Max Scaling ke Skala Saaty (1-9)
     */
    public function minMaxToSaaty(
        float $value,
        float $min,
        float $max
    ): float;

    /**
     * Hitung Eigen Vector
     * Menggunakan Geometric Mean
     */
    public function calculateEigenVector(
        array $matrix
    ): array;

    /**
     * Hitung Consistency Ratio
     */
    public function calculateConsistency(
        array $matrix,
        array $weights
    ): array;
}