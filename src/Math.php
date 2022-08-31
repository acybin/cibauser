<?php


namespace App;


class Math
{
    public static function sigmoid(float $x): float
    {
        return 1 / (1 + exp((-1) * $x));
    }

    public static function derivative(float $x): float
    {
        return (1 - $x) * $x;
    }

    public static function weight(): float
    {
        $sign = rand(0, 1) ? -1 : 1;
        return rand(1, 1000) / 1000 * $sign;
    }

    public static function normalize(array $input): array
    {
        $t = [];
        foreach ($input as $key => $array)
            foreach ($array as $k => $val)
                $t[$key][$k] = $val ? 1 / $val : 0;

        return $t;
    }
}