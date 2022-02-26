<?php
declare (strict_types = 1);

namespace App\Domain;

class Stock
{
    public $stock;
    const SHARECOUNT = 200;
    const ROUNDOFF = 2;

    public function __construct(array $stock)
    {
        $this->stock = $stock;
    }

    public function getProfitAndDates()
    {
        $profit = PHP_INT_MIN;
        $total = 0;
        $totalSquared = 0;
        for ($i = 0; $i < count($this->stock); $i++) {
            $total += $this->stock[$i]['price'];
            $totalSquared += $this->stock[$i]['price'] * $this->stock[$i]['price'];
            for ($j = $i + 1; $j < count($this->stock); $j++) {
                $difference = $this->stock[$j]['price'] - $this->stock[$i]['price'];
                if ($difference > $profit) {
                    $profit = $difference;
                    $buyDate = $this->stock[$i]['date'];
                    $sellDate = $this->stock[$j]['date'];
                }
            }
        }

        $mean = $total / $i;
        $standardDeviation = sqrt(($totalSquared / $i) - ($mean * $mean));

        return [
            'profit' => $profit,
            'buyDate' => $buyDate,
            'sellDate' => $sellDate,
            'mean' => round($mean, self::ROUNDOFF),
            'standardDeviation' => round($standardDeviation, self::ROUNDOFF),
            'stockProfit' => round($profit * self::SHARECOUNT, self::ROUNDOFF),
        ];
    }

    public function stockInfo()
    {
        return $this->bestProfit() + $this->meanAndStandardDeviation();
    }

    /**
     * Calculates the minimum loss and maximum profit with buy/sell dates
     *
     * @return array
     */
    public function bestProfit()
    {
        $profit = PHP_INT_MIN;

        foreach ($this->stock as $dateI => $priceI) {
            foreach ($this->stock as $dateJ => $priceJ) {
                if ($dateJ > $dateI) {
                    $difference = $priceJ - $priceI;

                    if ($difference > $profit) {
                        $profit = $difference;
                        $buyDate = $dateI;
                        $sellDate = $dateJ;
                    }
                }
            }
        }

        return [
            'profit' => $profit,
            'buyDate' => $buyDate,
            'sellDate' => $sellDate,
            'stockProfit' => round($profit * self::SHARECOUNT, self::ROUNDOFF),
        ];
    }

    /**
     * Calculates mean, standard deviation and stock profit
     *
     * @return array
     */
    public function meanAndStandardDeviation()
    {
        $total = 0;
        $totalSquared = 0;

        foreach ($this->stock as $dateI => $priceI) {
            $total += $priceI;
            $totalSquared += $priceI * $priceI;
        }

        $n = count($this->stock);
        $mean = $total / $n;
        $standardDeviation = sqrt(($totalSquared / $n) - ($mean * $mean));

        return [
            'mean' => round($mean, self::ROUNDOFF),
            'standardDeviation' => round($standardDeviation, self::ROUNDOFF),
        ];
    }
}
