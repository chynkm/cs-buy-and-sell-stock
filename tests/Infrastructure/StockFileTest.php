<?php
declare (strict_types = 1);

namespace App\Infrastructure;

use Exception;
use PHPUnit\Framework\TestCase;

class StockFileTest extends TestCase
{
    public $filename = __DIR__ . "/../../public/storage/test.csv";

    public function testFileDoesntExistsThrowsException(): void
    {
        $this->expectException(Exception::class);
        (new StockFile)->process('');
    }

    public function testEmptyFilesThrowsException(): void
    {
        $this->expectException(Exception::class);
        $filename = '/tmp/new.csv';
        touch($filename);
        (new StockFile)->process($filename);
    }

    /**
     * @return array<int, array>
     */
    public function invalidCSVProvider(): array
    {
        return [
            [
                // missing id
                'csv' => [
                    'id_no,date,stock_name,price',
                    '1,11-02-2020,AaPL,320',
                    // this should work since we are least bothered about id_no
                    ',2020-02-11,gOogL,1510',
                    '11 Feb 2020,msft,185',
                    '4,2020/02/12,GOOGL,1518',
                ],
            ],
            [
                // missing date
                'csv' => [
                    'id_no,date,stock_name,price',
                    '12,18-02-2020,AAPL,319',
                    '13,18-02-2020,MSFT,187',
                    '14,,AAPL,323',
                    '15,26-02-2020,AAPL,313',
                ],
            ],
            [
                // missing date
                'csv' => [
                    'id_no,date,stock_name,price',
                    '12,18-02-2020,AAPL,319',
                    '14,AAPL,323',
                    '13,18-02-2020,MSFT,187',
                    '15,26-02-2020,AAPL,313',
                ],
            ],
            [
                // missing stock
                'csv' => [
                    'id_no,date,stock_name,price',
                    '28,11-02-2020,,320',
                    '29,15-02-2020,BPL,310',
                    '30,13-02-2020,BPL,290',
                    '31,18-02-2020,BPL,280',
                ],
            ],
            [
                // missing stock
                'csv' => [
                    'id_no,date,stock_name,price',
                    '28,11-02-2020,320',
                    '29,15-02-2020,BPL,310',
                    '30,13-02-2020,BPL,290',
                    '31,18-02-2020,BPL,280',
                ],
            ],
            [
                // missing price
                'csv' => [
                    'id_no,date,stock_name,price',
                    '28,11-02-2020,BPL,',
                    '29,15-02-2020,BPL,310',
                    '30,13-02-2020,BPL,290',
                    '31,18-02-2020,BPL,280',
                ],
            ],
            [
                // missing price
                'csv' => [
                    'id_no,date,stock_name,price',
                    '28,11-02-2020,BPL',
                    '29,15-02-2020,BPL,310',
                    '30,13-02-2020,BPL,290',
                    '31,18-02-2020,BPL,280',
                ],
            ],
            [
                // missing stock
                'csv' => [
                    'id_no,date,stock_name,price',
                    '',
                ],
            ],
            [
                // missing stock
                'csv' => [
                    'id_no,date,stock_name,price',
                ],
            ],
            [
                // no data
                'csv' => [],
            ],
            [
                // incorrect order
                'csv' => [
                    'id_no,date,stock_name,price',
                    '12,AAPL,18-02-2020,319',
                    '13,18-02-2020,MSFT,187',
                    '14,25-02-2020,AAPL,323',
                    '15,26-02-2020,AAPL,313',
                ],
            ],
            [
                // incorrect order
                'csv' => [
                    'id_no,date,stock_name,price',
                    '12,18-02-2020,AAPL,319',
                    '13,18-02-2020,187,MSFT',
                    '14,25-02-2020,AAPL,323',
                    '15,26-02-2020,AAPL,313',
                ],
            ],
            [
                // invalid date
                'csv' => [
                    'id_no,date,stock_name,price',
                    '12,10 2020,AAPL,319',
                    '13,18-02-2020,MSFT,187',
                    '14,25-02-2020,AAPL,323',
                    '15,26-02-2020,AAPL,313',
                ],
            ],
            [
                // invalid date
                'csv' => [
                    'id_no,date,stock_name,price',
                    '12,10 2020,AAPL,319',
                    '13,example date,MSFT,187',
                    '14,2020 10,AAPL,323',
                    '15,26-02-2020,AAPL,313',
                ],
            ],
            [
                // invalid date
                'csv' => [
                    'id_no,date,stock_name,price',
                    '12,10 2020,AAPL,319',
                    '13,18-02-2020,MSFT,187',
                    '14,2020 10,AAPL,323',
                    '15,26-02-2020,AAPL,313',
                ],
            ],
            [
                // invalid date
                'csv' => [
                    'id_no,date,stock_name,price',
                    '12,10 2020,AAPL,319',
                    '13,18-02-2020,MSFT,187',
                    '14,2020 10,AAPL,323',
                    '15,2020 10 02,AAPL,313',
                ],
            ],
        ];
    }

    /**
     * @dataProvider invalidCSVProvider
     */
    public function testInvalidFileThrowsException(array $csv): void
    {
        $this->expectException(Exception::class);
        $handle = fopen($this->filename, 'wb');
        foreach ($csv as $line) {
            $row = explode(',', $line);
            fputcsv($handle, $row);
        }
        fclose($handle);

        $got = (new StockFile)->process($this->filename);
    }

    /**
     * @return array<int, array>
     */
    public function validCSVProvider(): array
    {
        return [
            [
                // different date formats
                'csv' => [
                    'id_no,date,stock_name,price',
                    '1,11-02-2020,AaPL,320',
                    '2,2020-02-11,gOogL,1510',
                    '3,11 Feb 2020,msft,185',
                    '4,2020/02/12,GOOGL,1518',
                ],
                'result' => [
                    'AAPL' => ['2020-02-11' => 320],
                    'GOOGL' => [
                        '2020-02-11' => 1510,
                        '2020-02-12' => 1518,
                    ],
                    'MSFT' => ['2020-02-11' => 185],
                ],
            ],
            [
                // two different stocks
                'csv' => [
                    'id_no,date,stock_name,price',
                    '12,18-02-2020,AAPL,319',
                    '13,18-02-2020,MSFT,187',
                    '14,25-02-2020,AAPL,323',
                    '15,26-02-2020,AAPL,313',
                ],
                'result' => [
                    'AAPL' => [
                        '2020-02-18' => 319,
                        '2020-02-25' => 323,
                        '2020-02-26' => 313,
                    ],
                    'MSFT' => ['2020-02-18' => 187],
                ],
            ],
            [
                // one stock
                'csv' => [
                    'id_no,date,stock_name,price',
                    '28,11-02-2020,BPL,320',
                    '29,15-02-2020,BPL,310',
                    '30,13-02-2020,BPL,290',
                    '31,18-02-2020,BPL,280',
                ],
                'result' => [
                    'BPL' => [
                        '2020-02-11' => 320,
                        '2020-02-13' => 290,
                        '2020-02-15' => 310,
                        '2020-02-18' => 280,
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider validCSVProvider
     */
    public function testProcessStocks(array $csv, array $result): void
    {
        $handle = fopen($this->filename, 'wb');
        foreach ($csv as $line) {
            $row = explode(',', $line);
            fputcsv($handle, $row);
        }
        fclose($handle);

        $got = (new StockFile)->process($this->filename);

        $this->assertEquals($got, $result);
    }

}
