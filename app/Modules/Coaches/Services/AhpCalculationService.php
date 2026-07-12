<?php

namespace App\Modules\Coaches\Services;

use App\Modules\Coaches\Services\Interfaces\IAhpCalculationService;

class AhpCalculationService implements IAhpCalculationService
{
    /**
     * Reciprocal Normalization
     */
    public function normalizeReciprocal(
        int $firstId,
        int $secondId,
        float $value
    ): array {

        if ($firstId > $secondId) {

            return [
                'first_id' => $secondId,
                'second_id' => $firstId,
                'value' => round(
                    1 / $value,
                    8
                ),
            ];
        }

        return [
            'first_id' => $firstId,
            'second_id' => $secondId,
            'value' => $value,
        ];
    }

    /**
     * Min-Max ke Saaty 1-9
     */
    public function minMaxToSaaty(
        float $value,
        float $min,
        float $max
    ): float {

        if ($max == $min) {
            return 1;
        }

        return round(
            1 +
            (
                (
                    ($value - $min)
                    /
                    ($max - $min)
                ) * 8
            ),
            8
        );
    }

    /**
     * Geometric Mean Eigen Vector
     */
    public function calculateEigenVector(
        array $matrix
    ): array {
        $size = count($matrix);
        $gm = [];
        for ($i = 0; $i < $size; $i++) {
            $product = 1;
            for ($j = 0; $j < $size; $j++) {

                $product *= $matrix[$i][$j];
            }
            $gm[$i] =
                pow(
                    $product,
                    1 / $size
                );
        }
        $total =
            array_sum($gm);
        $weights = [];
        foreach ($gm as $value) {
            $weights[] =
                round(
                    $value / $total,
                    8
                );
        }
        return $weights;
    }

    /**
     * Consistency Ratio
     */
    public function calculateConsistency(
        array $matrix,
        array $weights
    ): array {

        $size = count($matrix);

        if ($size <= 1) {
            return [
                'lambda_max' => 1.0,
                'ci' => 0.0,
                'cr' => 0.0,
                'is_consistent' => true,
            ];
        }

        $weightedSum = [];

        for ($i = 0; $i < $size; $i++) {

            $sum = 0;

            for ($j = 0; $j < $size; $j++) {

                $sum +=
                    $matrix[$i][$j]
                    *
                    $weights[$j];
            }

            $weightedSum[$i] = $sum;
        }

        $lambda = [];

        for ($i = 0; $i < $size; $i++) {

            $lambda[] =
                $weightedSum[$i]
                /
                $weights[$i];
        }

        $lambdaMax =
            array_sum($lambda)
            /
            $size;

        $ci =
            ($lambdaMax - $size)
            /
            ($size - 1);

        $riTable = [
            1 => 0,
            2 => 0,
            3 => 0.58,
            4 => 0.90,
            5 => 1.12,
            6 => 1.24,
            7 => 1.32,
            8 => 1.41,
            9 => 1.45,
            10 => 1.49,
        ];

        $ri =
            $riTable[$size]
            ?? 1.49;

        $cr =
            $ri == 0
            ? 0
            : $ci / $ri;

        return [

            'lambda_max' => round($lambdaMax, 6),

            'ci' => round($ci, 6),

            'cr' => round($cr, 6),

            'is_consistent' => $cr < 0.1,
        ];
    }
}
