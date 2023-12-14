<?php


namespace App\Helpers;


class BodyFatHelper
{
    /*
     * JP3
     */
    public function manJP3($chest,
                           $abdominal,
                           $thigh,
                           $age)
    {

        $sumSkinFolds = $chest + $abdominal + $thigh;
        $bodyDensity =
            1.10938 -
            0.0008267 * $sumSkinFolds +
            0.0000016 * $sumSkinFolds * $sumSkinFolds -
            0.0002574 * $age;
        return $this->getPercentageFromDensity($bodyDensity);
    }

    public function womanJP3(
        $tricep,
        $suprailiac,
        $thigh,
        $age
    )
    {
        $sumSkinFolds = $tricep + $suprailiac + $thigh;
        $bodyDensity =
            1.0994921 -
            0.0009929 * $sumSkinFolds +
            0.0000023 * $sumSkinFolds * $sumSkinFolds -
            0.0001392 * $age;
        return $this->getPercentageFromDensity($bodyDensity);
    }

    /*
     * JP7
     */
    public function manJP7(
        $chest,
        $abdominal,
        $thigh,
        $suprailiac,
        $axillary,
        $tricep,
        $subscapular,
        $age
    )
    {
        $sumSkinFolds = $tricep + $suprailiac + $thigh + $abdominal + $chest + $axillary + $subscapular;
        $bodyDensity =
            1.112 -
            0.00043499 * $sumSkinFolds +
            0.00000055 * $sumSkinFolds * $sumSkinFolds -
            0.00028826 * $age;

        return $this->getPercentageFromDensity($bodyDensity);
    }

    public function womanJP7(
        $chest,
        $abdominal,
        $thigh,
        $suprailiac,
        $axillary,
        $tricep,
        $subscapular,
        $age
    )
    {
        $sumSkinFolds = $tricep + $suprailiac + $thigh + $abdominal + $chest + $axillary + $subscapular;
        $bodyDensity =
            1.097 -
            0.00046971 * $sumSkinFolds +
            0.00000056 * $sumSkinFolds * $sumSkinFolds -
            0.00012828 * $age;

        return $this->getPercentageFromDensity($bodyDensity);
    }

    private function getPercentageFromDensity($bodyDensity)
    {
        return 495 / $bodyDensity - 450;
    }

    /*
     * Parillo
     */
    public function Parillo(
        $chest,
        $abdominal,
        $thigh,
        $suprailiac,
        $lowerBack,
        $tricep,
        $subscapular,
        $calf,
        $bicep,
        $weight
    )
    {
        $sumSkinFolds =
            $tricep +
            $bicep +
            $calf +
            $suprailiac +
            $thigh +
            $abdominal +
            $chest +
            $lowerBack +
            $subscapular;
        return ($sumSkinFolds * 27) / ($weight * 2.20462);
    }

    /*
     *
     * Durnin
     */
    public function manDurnin(
        $tricep,
        $bicep,
        $subscapular,
        $suprailiac,
        $age
    )
    {
        if ($age < 16) {
            throw new Error("Age should be greater than 16");
        }
        if ($age < 17) {
            return $this->getBodyFatDurnin($tricep, $bicep, $subscapular, $suprailiac, 1.1533, 0.0643);
        } else if ($age < 20) {
            return $this->getBodyFatDurnin($tricep, $bicep, $subscapular, $suprailiac, 1.1620, 0.0630);
        } else if ($age < 30) {
            return $this->getBodyFatDurnin($tricep, $bicep, $subscapular, $suprailiac, 1.1631, 0.0632);
        } else if ($age < 40) {
            return $this->getBodyFatDurnin($tricep, $bicep, $subscapular, $suprailiac, 1.1422, 0.0544);
        } else if ($age < 50) {
            return $this->getBodyFatDurnin($tricep, $bicep, $subscapular, $suprailiac, 1.1620, 0.0700);
        }
        return $this->getBodyFatDurnin($tricep, $bicep, $subscapular, $suprailiac, 1.1715, 0.0779);
    }

    public function femaleDurnin(
        $tricep,
        $bicep,
        $subscapular,
        $suprailiac,
        $age
    )
    {
        if ($age < 16) {
            throw new Error("Age should be greater than 16");
        }

        if ($age < 20) {
            return $this->getBodyFatDurnin($tricep, $bicep, $subscapular, $suprailiac, 1.1549, 0.0678);
        } else if ($age < 30) {
            return $this->getBodyFatDurnin($tricep, $bicep, $subscapular, $suprailiac, 1.1599, 0.0717);
        } else if ($age < 40) {
            return $this->getBodyFatDurnin($tricep, $bicep, $subscapular, $suprailiac, 1.1423, 0.0632);
        } else if ($age < 50) {
            return $this->getBodyFatDurnin($tricep, $bicep, $subscapular, $suprailiac, 1.1333, 0.0612);
        }
        return $this->getBodyFatDurnin($tricep, $bicep, $subscapular, $suprailiac, 1.1339, 0.0645);
    }

    private function getBodyFatDurnin(
        $tricep,
        $bicep,
        $subscapular,
        $suprailiac,
        $constant,
        $coefficient
    )
    {
        return ($this->getPercentageFromDensity(
            $constant -
            $coefficient * log10($tricep + $bicep + $suprailiac + $subscapular))
        );
    }
}
