<?php
declare (strict_types = 1);

namespace App\Domain;

use DateInterval;
use DatePeriod;
use DateTime;
use Exception;

class Stock
{
    public $stock;
    const SHARE_COUNT = 200;
    const ROUND_OFF = 2;
    const DATE_FORMAT = 'Y-m-d';

    public function __construct(array $stocks)
    {
        $this->stocks = $stocks;
    }

    public function getProfitAndDates()
    {
        $profit = PHP_INT_MIN;
        $total = 0;
        $totalSquared = 0;
        for ($i = 0; $i < count($this->stocks); $i++) {
            $total += $this->stocks[$i]['price'];
            $totalSquared += $this->stocks[$i]['price'] * $this->stocks[$i]['price'];
            for ($j = $i + 1; $j < count($this->stocks); $j++) {
                $difference = $this->stocks[$j]['price'] - $this->stocks[$i]['price'];
                if ($difference > $profit) {
                    $profit = $difference;
                    $buyDate = $this->stocks[$i]['date'];
                    $sellDate = $this->stocks[$j]['date'];
                }
            }
        }

        $mean = $total / $i;
        $standardDeviation = sqrt(($totalSquared / $i) - ($mean * $mean));

        return [
            'profit' => $profit,
            'buyDate' => $buyDate,
            'sellDate' => $sellDate,
            'mean' => round($mean, self::ROUND_OFF),
            'standardDeviation' => round($standardDeviation, self::ROUND_OFF),
            'stockProfit' => round($profit * self::SHARE_COUNT, self::ROUND_OFF),
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

        foreach ($this->stocks as $dateI => $priceI) {
            foreach ($this->stocks as $dateJ => $priceJ) {
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
            'stockProfit' => round($profit * self::SHARE_COUNT, self::ROUND_OFF),
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

        foreach ($this->stocks as $dateI => $priceI) {
            $total += $priceI;
            $totalSquared += $priceI * $priceI;
        }

        $n = count($this->stocks);
        $mean = $total / $n;
        $standardDeviation = sqrt(($totalSquared / $n) - ($mean * $mean));

        return [
            'mean' => round($mean, self::ROUND_OFF),
            'standardDeviation' => round($standardDeviation, self::ROUND_OFF),
        ];
    }

    /**
     * Retrieve the previous date stock price
     *
     * @return int
     */
    public function previousDateStockPrice(string $date)
    {
        if (isset($this->stocks[$date])) {
            return $this->stocks[$date];
        }

        if ($date < key($this->stocks)) {
            throw new Exception('The specified start date dont have a stock price');
        }

        foreach ($this->stocks as $stockDate => $price) {

            if ($stockDate < $date) {
                $previousPrice = $price;
            }
        }

        return $previousPrice;
    }

    public function fillMissingDates(string $startDate, string $endDate)
    {
        $dateFilledStocks = [];

        $startDate = new DateTime($startDate);
        $endDate = (new DateTime($endDate))->modify('+1 day');
        $interval = new DateInterval('P1D');
        $dateRange = new DatePeriod($startDate, $interval, $endDate);

        if (!isset($this->stocks[$startDate->format(self::DATE_FORMAT)])) {
            $this->stocks[$startDate->format(self::DATE_FORMAT)] = $this->previousDateStockPrice($startDate->format(self::DATE_FORMAT));
        }

        foreach ($dateRange as $date) {
            if (isset($this->stocks[$date->format(self::DATE_FORMAT)])) {
                $latestStockValue = $this->stocks[$date->format(self::DATE_FORMAT)];
                $dateFilledStocks[$date->format(self::DATE_FORMAT)] = $this->stocks[$date->format(self::DATE_FORMAT)];
            } else {
                $dateFilledStocks[$date->format(self::DATE_FORMAT)] = $latestStockValue;
            }
        }

        return $dateFilledStocks;
    }
}
