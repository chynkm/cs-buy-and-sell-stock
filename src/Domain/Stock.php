<?php
declare (strict_types = 1);

namespace App\Domain;

class Stock
{
    public $stock;
    public $profit = PHP_INT_MIN;

    public function __construct(array $stock)
    {
        $this->stock = $stock;
    }

    public function getProfitAndDates()
    {
        for ($i = 0; $i < count($this->stock); $i++) {
            for ($j = $i + 1; $j < count($this->stock); $j++) {
                $difference = $this->stock[$j]['price'] - $this->stock[$i]['price'];
                if ($difference > $this->profit) {
                    $this->profit = $difference;
                    $buyDate = $this->stock[$i]['date'];
                    $sellDate = $this->stock[$j]['date'];
                }
            }
        }

        return [
            'profit' => $this->profit,
            'buyDate' => $buyDate,
            'sellDate' => $sellDate,
        ];
    }
}
