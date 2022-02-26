<?php
declare (strict_types = 1);

namespace App\Domain;

class Stock
{
    public $stock;
    public $profit = PHP_INT_MIN;
    const SHARECOUNT = 200;

    public function __construct(array $stock)
    {
        $this->stock = $stock;
    }

    public function getProfitAndDates()
    {
        $total = 0;
        $totalSquared = 0;
        for ($i = 0; $i < count($this->stock); $i++) {
            $total += $this->stock[$i]['price'];
            $totalSquared += $this->stock[$i]['price'] * $this->stock[$i]['price'];
            for ($j = $i + 1; $j < count($this->stock); $j++) {
                $difference = $this->stock[$j]['price'] - $this->stock[$i]['price'];
                if ($difference > $this->profit) {
                    $this->profit = $difference;
                    $buyDate = $this->stock[$i]['date'];
                    $sellDate = $this->stock[$j]['date'];
                }
            }
        }

        $mean = $total / $i;
        $standardDeviation = sqrt(($totalSquared / $i) - ($mean * $mean));

        return [
            'profit' => $this->profit,
            'buyDate' => $buyDate,
            'sellDate' => $sellDate,
            'mean' => round($mean, 2),
            'standardDeviation' => round($standardDeviation, 2),
            'profit' => round($this->profit * self::SHARECOUNT, 2),
        ];
    }
}
