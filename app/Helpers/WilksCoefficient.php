<?php


namespace App\Helpers;


use Exception;
use phpDocumentor\Reflection\Types\Integer;

class WilksCoefficient
{

    private const MALE_VALUES = [
        -216.0475144,
        16.2606339,
        -0.002388645,
        -0.00113732,
        7.01863E-06,
        -1.291E-08
    ];

    private const FEMALE_VALUES = [
        594.31747775582,
        -27.23842536447,
        0.82112226871,
        -0.00930733913,
        4.731582E-05,
        -9.054E-08
    ];

    const IMPERIAL = 2.20462262185;

    /**
     * Returns a Wilks score based on the body weight of the lifter and the weight they have lifted.
     *
     * @param $gender {string} The $gender of the lifter the wilks score is calculated for ('m' for male, 'f' for female).
     * @param $bodyWeight {number} The body weight of the lifter the wilks score is calculated for.
     * @param $liftedWeight {number} The weight the lifter has lifted.
     * @param string $unitType {string} Optional parameter for lifters using the imperial unit system ('kg' is default, 'imperial' for the imperial system).
     *
     * @returns {number} The Wilks score.
     * @throws Exception
     */
    public function calculateWilksScore($gender, int $bodyWeight, int $liftedWeight, string $unitType = 'metric')
    {
        if (!$gender || !$bodyWeight || !$liftedWeight) {
            throw new Exception('Missing parameters, please fill in $gender, body weight and weight.');
        }

        if ($unitType === 'imperial') {
            $liftedWeight /= self::IMPERIAL;
            $bodyWeight /= self::IMPERIAL;
        }

        $this->validateInput($gender, $unitType, $bodyWeight, $liftedWeight, null);
        error_log($gender . $bodyWeight);

	$coeff = 500 / $this->calculateCoefficient($gender, $bodyWeight);

    return $liftedWeight * $coeff;
}

    /**
     * Returns a total amount of weight to lift based on the body weight of the lifter and the preferred Wilks score.
     *
     * @param $gender {string} The $gender of the lifter the wilks score is calculated for ('m' for male, 'f' for female).
     * @param $bodyWeight {number} The body weight of the lifter the wilks score is calculated for.
     * @param $wilksScore {number} The preferred Wilks score.
     * @param string $unitType {string} Optional parameter for lifters using the imperial unit system ('kg' is default, 'imperial' for the imperial system).
     *
     * @returns {number} The total amount of weight to lift.
     * @throws Exception
     */
    public function calculateWeightToLift($gender, $bodyWeight, $wilksScore, string $unitType = 'metric')
    {
        if (!$gender || !$bodyWeight || !$wilksScore) {
            throw new Exception('Missing parameters, please fill in $gender, body weight and Wilks score.');
        }

        $this->validateInput($gender, $unitType, $bodyWeight, $wilksScore, null);

	if ($unitType === 'imperial') {
        $bodyWeight /= self::IMPERIAL;
    }

	$coeff = 500 / $this->calculateCoefficient($gender, $bodyWeight);

	return $unitType === 'imperial' ? self::IMPERIAL * ($wilksScore / $coeff) : $wilksScore / $coeff;
}

    /**
     * Returns the needed body weight based on the total amount of weight to lift and the preferred Wilks score.
     *
     * @param $gender {string} The $gender of the lifter the wilks score is calculated for ('m' for male, 'f' for female).
     * @param $liftedWeight {number} $liftedWeight {number} The weight the lifter has lifted.
     * @param $wilksScore {number} The preferred Wilks score.
     * @param string $unitType {string} Optional parameter for lifters using the imperial unit system ('kg' is default, 'imperial' for the imperial system).
     *
     * @returns {number} The total amount of weight to lift.
     * @throws Exception
     */
    public function calculateNeeded($gender, Integer  $liftedWeight, Integer $wilksScore, string $unitType = 'metric')
    {
        if (!$gender || !$liftedWeight || !$wilksScore) {
            throw new Exception('Missing parameters, please fill in $gender, lifted weight and Wilks score.');
        }

        $this->validateInput($gender, $unitType, null, $liftedWeight, $wilksScore);

        if ($unitType === 'imperial') {
            $liftedWeight /= self::IMPERIAL;
        }

        $coeff = 500 / ($wilksScore / $liftedWeight);
        $bodyWeight = 0.0;
        $result = 0.0;

        while ($this->calculateDifference($coeff, $result) > 0.5) {
            $bodyWeight += 0.1;
            $result = $this->calculateCoefficient($gender, $bodyWeight);
        }

        return $unitType === 'imperial' ? self::IMPERIAL * $bodyWeight : $bodyWeight;
    }

    /**
     * A helper function to determine the difference between the calculated coefficient and the input.
     *
     * @param $a {number}
     * @param $b {number}
     *
     * @returns {number} The absolute difference between a and b.
     *
     * @private
     */
    private function calculateDifference($a, $b)
    {
        return abs($a - $b);
    }

    /**
     * Calculates the coefficient based on the body weight and the $gender.
     *
     * @param $gender {string}
     * @param $bodyWeight {number}
     *
     * @returns {number} The coefficient.
     *
     * @private
     */
    private function calculateCoefficient($gender, $bodyWeight)
    {
        $coeff = 0;
        $values = $gender === 'm' ? self::MALE_VALUES : self::FEMALE_VALUES;

        for ($i = 0; $i <= 5; $i++) {
            $coeff += $i === 0 ? $values[$i] : ($values[$i] * ($bodyWeight ** $i));
        }

        return $coeff;
    }

    /**
     * A helper function to validate the input.
     *
     * @param $gender {string}
     * @param $unitType {string}
     * @param $bodyWeight {number}
     * @param $liftedWeight {number}
     * @param $wilksScore {number}
     *
     * @private
     * @throws Exception
     */
    private function validateInput($gender, $unitType, $bodyWeight =0, $liftedWeight =0, $wilksScore =0)
    {
        if ($gender !== 'm' && $gender !== 'f') {
            throw new Exception('$gender is not valid. Please select m for Male or f for Female.');
        }

        if (!is_numeric($bodyWeight) || $bodyWeight < 0) {
            throw new Exception('Body weight is not valid.');
        }

        if (!is_numeric($liftedWeight) || $liftedWeight < 0) {
            throw new Exception('Weight is not valid.');
        }

        if ($wilksScore < 0) {
            throw new Exception('Wilks score is not valid.');
        }

        if ($unitType !== 'metric' && $unitType !== 'imperial') {
            throw new Exception('Unit type is not valid. Please select metric or imperial.');
        }
    }
}
