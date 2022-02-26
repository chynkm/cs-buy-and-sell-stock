<?php
declare (strict_types = 1);

namespace App\Test;

use App\Domain\Stock;
use Exception;
use PHPUnit\Framework\TestCase;

class StockTest extends TestCase
{
    const AAPL_STOCKS = [
        '2020-02-11' => 320,
        '2020-02-13' => 324,
        '2020-02-15' => 319,
        '2020-02-18' => 319,
        '2020-02-19' => 323,
        '2020-02-21' => 313,
        '2020-02-23' => 320,
    ];

    /**
     * @return array<int, array>
     */
    public function stockProviderWithoutDates(): array
    {
        return [
            [
                'AAPL' => [
                    ['date' => '11-02-2020', 'price' => 320],
                    ['date' => '13-02-2020', 'price' => 324],
                    ['date' => '15-02-2020', 'price' => 319],
                    ['date' => '18-02-2020', 'price' => 319],
                    ['date' => '19-02-2020', 'price' => 323],
                    ['date' => '21-02-2020', 'price' => 313],
                    ['date' => '23-02-2020', 'price' => 320],
                ],
                'result' => [
                    'profit' => 7,
                    'buyDate' => '21-02-2020',
                    'sellDate' => '23-02-2020',
                    'mean' => 319.71,
                    'standardDeviation' => 3.28,
                    'stockProfit' => 1400,
                ],
            ],
            [
                'GOOGL' => [
                    ['date' => '11-02-2020', 'price' => 1510],
                    ['date' => '12-02-2020', 'price' => 1518],
                    ['date' => '14-02-2020', 'price' => 1520],
                    ['date' => '15-02-2020', 'price' => 1523],
                    ['date' => '16-02-2020', 'price' => 1530],
                    ['date' => '21-02-2020', 'price' => 1483],
                    ['date' => '22-02-2020', 'price' => 1485],
                ],
                'result' => [
                    'profit' => 20,
                    'buyDate' => '11-02-2020',
                    'sellDate' => '16-02-2020',
                    'mean' => 1509.86,
                    'standardDeviation' => 17.27,
                    'stockProfit' => 4000,
                ],

            ],
            [
                'MSFT' => [
                    ['date' => '11-02-2020', 'price' => 185],
                    ['date' => '12-02-2020', 'price' => 184],
                    ['date' => '15-02-2020', 'price' => 189],
                    ['date' => '18-02-2020', 'price' => 187],
                    ['date' => '21-02-2020', 'price' => 178],
                    ['date' => '22-02-2020', 'price' => 180],
                ],
                'result' => [
                    'profit' => 5,
                    'buyDate' => '12-02-2020',
                    'sellDate' => '15-02-2020',
                    'mean' => 183.83,
                    'standardDeviation' => 3.80,
                    'stockProfit' => 1000,
                ],
            ],
        ];
    }

    /**
     * @dataProvider stockProviderWithoutDates
     */
    public function testGetProfit(array $stock, array $result): void
    {
        $got = (new Stock($stock))->getProfitAndDates();

        $this->assertEquals($got, $result);
    }

    /**
     * @return array<int, array>
     */
    public function stockProviderWithDates(): array
    {
        return [
            [
                'AAPL' => self::AAPL_STOCKS,
                'startDate' => '2020-02-15',
                'endDate' => '2020-02-21',
                'bestProfit' => [
                    'profit' => 7,
                    'buyDate' => '2020-02-21',
                    'sellDate' => '2020-02-23',
                    'stockProfit' => 1400,
                ],
                'meanAndStandardDeviation' => [
                    'mean' => 319.71,
                    'standardDeviation' => 3.28,
                ],
            ],

        ];
    }

    /**
     * @dataProvider stockProviderWithDates
     */
    public function testGetStockInfo(
        array $stocks,
        string $startDate,
        string $endDate,
        array $wantBestProfit,
        array $wantMeanAndStandardDeviation
    ): void{
        $stock = new Stock($stocks);
        $gotBestProfit = $stock->bestProfit();
        $gotMeanAndStandardDeviation = $stock->meanAndStandardDeviation();

        $this->assertEquals($gotBestProfit, $wantBestProfit);
        $this->assertEquals(
            $gotMeanAndStandardDeviation,
            $wantMeanAndStandardDeviation
        );
    }

    /**
     * @return array<int, array>
     */
    public function validStockProvidersForPreviousDateStockPrice(): array
    {
        return [
            ['date' => '2020-02-15', 'price' => 319],
            ['date' => '2020-02-17', 'price' => 319],
            ['date' => '2020-02-14', 'price' => 324],
            ['date' => '2020-02-25', 'price' => 320],
        ];
    }

    /**
     * @dataProvider validStockProvidersForPreviousDateStockPrice
     */
    public function testPreviousDateStockPrice(string $date, int $price): void
    {
        $stocks = self::AAPL_STOCKS;
        $got = (new Stock($stocks))->previousDateStockPrice($date);

        $this->assertEquals($got, $price);
    }

    /**
     * @return array<int, array>
     */
    public function inValidStockProvidersForPreviousDateStockPrice(): array
    {
        return [
            ['date' => '2020-02-10'],
            ['date' => '2020-02-05'],
        ];
    }

    /**
     * @dataProvider inValidStockProvidersForPreviousDateStockPrice
     */
    public function testInvalidPreviousDateStockPrice(string $date): void
    {
        $this->expectException(Exception::class);
        $stocks = self::AAPL_STOCKS;
        (new Stock($stocks))->previousDateStockPrice($date);
    }

    /**
     * @return array<int, array>
     */
    public function missingDatesProviders(): array
    {
        return [
            [
                'date' => '2020-02-14',
                'result' => [
                    '2020-02-14' => 324,
                    '2020-02-15' => 319,
                    '2020-02-16' => 319,
                    '2020-02-17' => 319,
                    '2020-02-18' => 319,
                    '2020-02-19' => 323,
                    '2020-02-20' => 323,
                    '2020-02-21' => 313,
                ],
            ],
            [
                'date' => '2020-02-15',
                'result' => [
                    '2020-02-15' => 319,
                    '2020-02-16' => 319,
                    '2020-02-17' => 319,
                    '2020-02-18' => 319,
                    '2020-02-19' => 323,
                    '2020-02-20' => 323,
                    '2020-02-21' => 313,
                ],
            ],
        ];
    }

    /**
     * @dataProvider missingDatesProviders
     */
    public function testFillMissingDatesOfStock($startDate, $result): void
    {
        $stocks = self::AAPL_STOCKS;
        $endDate = '2020-02-21';

        $got = (new Stock($stocks))->fillMissingDates($startDate, $endDate);
        $this->assertEquals($got, $result);
    }
}
