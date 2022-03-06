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

    public function __construct(array $stocks)
    {
        $this->stocks = $stocks;
    }

    /**
     * Get stock information in a consolidated method
     */
    public function stockInfo(string $startDate, string $endDate): array
    {
        if (empty($startDate)) {
            throw new Exception('Please enter a start date.');
        }

        if (empty($endDate)) {
            throw new Exception('Please enter an end date.');
        }

        if (empty($this->stocks)) {
            throw new Exception('Please select a stock.');
        }

        if (strtotime($startDate) === false) {
            throw new Exception('The start date value is incorrect');
        }

        if (strtotime($endDate) === false) {
            throw new Exception('The end date value is incorrect');
        }

        if ($startDate >= $endDate) {
            throw new Exception('The start date should be less than end date.');
        }

        $dateFilledStocks = $this->fillMissingDates($startDate, $endDate);
        ksort($dateFilledStocks);
        return $this->bestProfit($dateFilledStocks)
         + $this->meanAndStandardDeviation($dateFilledStocks);
    }

    /**
     * Calculates the minimum loss and maximum profit with buy/sell dates
     */
    public function bestProfit(array $stocks): array
    {
        $profit = PHP_INT_MIN;

        foreach ($stocks as $dateI => $priceI) {
            foreach ($stocks as $dateJ => $priceJ) {
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
            'profit' => round($profit, self::ROUND_OFF),
            'buyDate' => $buyDate,
            'sellDate' => $sellDate,
            'stockProfit' => round($profit * self::SHARE_COUNT, self::ROUND_OFF),
        ];
    }

    /**
     * Calculates mean, standard deviation and stock profit
     */
    public function meanAndStandardDeviation(array $stocks): array
    {
        $total = 0;
        $totalSquared = 0;

        foreach ($stocks as $dateI => $priceI) {
            $total += $priceI;
            $totalSquared += $priceI * $priceI;
        }

        $stockCount = count($stocks);
        $mean = $total / $stockCount;
        $standardDeviation = sqrt(($totalSquared / $stockCount) - ($mean * $mean));

        return [
            'mean' => round($mean, self::ROUND_OFF),
            'standardDeviation' => round($standardDeviation, self::ROUND_OFF),
        ];
    }

    /**
     * Retrieve the previous date stock price
     */
    public function previousDateStockPrice(string $date): float
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

        return floatval($previousPrice);
    }

    /**
     * Fill the missing days where stock price isn't available with the previous day value
     */
    public function fillMissingDates(string $startDate, string $endDate): array
    {
        $dateFilledStocks = [];

        $startDate = new DateTime($startDate);
        $endDate = (new DateTime($endDate))->modify('+1 day');
        $interval = new DateInterval('P1D');
        $dateRange = new DatePeriod($startDate, $interval, $endDate);

        if (!isset($this->stocks[$startDate->format(DATE_FORMAT)])) {
            $this->stocks[$startDate->format(DATE_FORMAT)] = $this->previousDateStockPrice($startDate->format(DATE_FORMAT));
        }

        foreach ($dateRange as $date) {
            if (isset($this->stocks[$date->format(DATE_FORMAT)])) {
                $latestStockValue = $this->stocks[$date->format(DATE_FORMAT)];
                $dateFilledStocks[$date->format(DATE_FORMAT)] = $this->stocks[$date->format(DATE_FORMAT)];
            } else {
                $dateFilledStocks[$date->format(DATE_FORMAT)] = $latestStockValue;
            }
        }

        return $dateFilledStocks;
    }
}
