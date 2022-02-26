<?php
declare (strict_types = 1);

namespace App\Test;

use App\Domain\Stock;
use PHPUnit\Framework\TestCase;

class StockTest extends TestCase
{
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
                'AAPL' => [
                    '11-02-2020' => 320,
                    '13-02-2020' => 324,
                    '15-02-2020' => 319,
                    '18-02-2020' => 319,
                    '19-02-2020' => 323,
                    '21-02-2020' => 313,
                    '23-02-2020' => 320,
                ],
                'startDate' => '15-02-2020',
                'endDate' => '21-02-2020',
                'bestProfit' => [
                    'profit' => 7,
                    'buyDate' => '21-02-2020',
                    'sellDate' => '23-02-2020',
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
        array $stock,
        string $startDate,
        string $endDate,
        array $wantBestProfit,
        array $wantMeanAndStandardDeviation
    ): void{
        $stock = new Stock($stock);
        $gotBestProfit = $stock->bestProfit();
        $gotMeanAndStandardDeviation = $stock->meanAndStandardDeviation();

        $this->assertEquals($gotBestProfit, $wantBestProfit);
        $this->assertEquals(
            $gotMeanAndStandardDeviation,
            $wantMeanAndStandardDeviation
        );
    }

}
