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
    public function stockProviderWithDates(): array
    {
        return [
            [
                // start/end dates are present
                'AAPL' => self::AAPL_STOCKS,
                'startDate' => '2020-02-15',
                'endDate' => '2020-02-21',
                'bestProfit' => [
                    'profit' => 4,
                    'buyDate' => '2020-02-15',
                    'sellDate' => '2020-02-19',
                    'stockProfit' => 800,
                ],
                'meanAndStandardDeviation' => [
                    'mean' => 319.29,
                    'standardDeviation' => 3.10,
                ],
            ],
            [
                // start date present and end date missing
                'AAPL' => self::AAPL_STOCKS,
                'startDate' => '2020-02-11',
                'endDate' => '2020-02-12',
                'bestProfit' => [
                    'profit' => 0,
                    'buyDate' => '2020-02-11',
                    'sellDate' => '2020-02-12',
                    'stockProfit' => 0,
                ],
                'meanAndStandardDeviation' => [
                    'mean' => 320,
                    'standardDeviation' => 0,
                ],
            ],
            [
                // start date present and end date missing
                'AAPL' => self::AAPL_STOCKS,
                'startDate' => '2020-02-13',
                'endDate' => '2020-02-15',
                'bestProfit' => [
                    'profit' => 0,
                    'buyDate' => '2020-02-13',
                    'sellDate' => '2020-02-14',
                    'stockProfit' => 0,
                ],
                'meanAndStandardDeviation' => [
                    'mean' => 322.33,
                    'standardDeviation' => 2.36,
                ],
            ],
            [
                // first start date and last end date present
                'AAPL' => self::AAPL_STOCKS,
                'startDate' => '2020-02-11',
                'endDate' => '2020-02-23',
                'bestProfit' => [
                    'profit' => 7,
                    'buyDate' => '2020-02-21',
                    'sellDate' => '2020-02-23',
                    'stockProfit' => 1400,
                ],
                'meanAndStandardDeviation' => [
                    'mean' => 319.69,
                    'standardDeviation' => 3.41,
                ],
            ],
            [
                // start date inside given data
                // and end date exceeding given data
                'GOOGL' => [
                    '2020-02-11' => 1510,
                    '2020-02-12' => 1518,
                    '2020-02-14' => 1520,
                    '2020-02-15' => 1523,
                    '2020-02-16' => 1530,
                    '2020-02-21' => 1483,
                    '2020-02-22' => 1485,
                ],
                'startDate' => '2020-02-18',
                'endDate' => '2020-02-23',
                'bestProfit' => [
                    'profit' => 2,
                    'buyDate' => '2020-02-21',
                    'sellDate' => '2020-02-22',
                    'stockProfit' => 400,
                ],
                'meanAndStandardDeviation' => [
                    'mean' => 1507.17,
                    'standardDeviation' => 22.84,
                ],
            ],
            [
                // different stock value
                'MSFT' => [
                    '2020-02-11' => 185,
                    '2020-02-12' => 184,
                    '2020-02-15' => 189,
                    '2020-02-18' => 187,
                    '2020-02-21' => 178,
                    '2020-02-22' => 180,
                ],
                'startDate' => '2020-02-11',
                'endDate' => '2020-02-20',
                'bestProfit' => [
                    'profit' => 5,
                    'buyDate' => '2020-02-12',
                    'sellDate' => '2020-02-15',
                    'stockProfit' => 1000,
                ],
                'meanAndStandardDeviation' => [
                    'mean' => 186.5,
                    'standardDeviation' => 2.01,
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
        $dateFilledStocks = $stock->fillMissingDates($startDate, $endDate);
        $gotBestProfit = $stock->bestProfit($dateFilledStocks);
        $gotMeanAndStandardDeviation = $stock->meanAndStandardDeviation($dateFilledStocks);

        $this->assertEquals($gotBestProfit, $wantBestProfit);
        $this->assertEquals($gotMeanAndStandardDeviation, $wantMeanAndStandardDeviation);
    }

    /**
     * @return array<int, array>
     */
    public function invalidStockInfoProvider(): array
    {
        return [
            [
                // missing start date
                'startDate' => '',
                'endDate' => '2020-02-21',
            ],
            [
                // missing end date
                'startDate' => '2020-02-11',
                'endDate' => '',
            ],
            [
                // same start and end date
                'startDate' => '2020-02-11',
                'endDate' => '2020-02-11',
            ],
            [
                // missing start and end date
                'startDate' => '',
                'endDate' => '',
            ],
            [
                // start date > end date
                'startDate' => '2020-02-23',
                'endDate' => '2020-02-11',
            ],
            [
                // invalid start date
                'startDate' => '2020 11',
                'endDate' => '2020-02-11',
            ],
            [
                // invalid end date
                'startDate' => '2020-02-11',
                'endDate' => '2020 21',
            ],
        ];
    }

    /**
     * @dataProvider invalidStockInfoProvider
     */
    public function testInvalidStockInfoProviders(string $startDate, string $endDate): void
    {
        $this->expectException(Exception::class);
        $stocks = self::AAPL_STOCKS;
        (new Stock($stocks))->stockInfo($startDate, $endDate);
    }

    public function testEmptyStock(): void
    {
        $this->expectException(Exception::class);
        (new Stock([]))->stockInfo('2020-02-11', '2020-02-14');
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
