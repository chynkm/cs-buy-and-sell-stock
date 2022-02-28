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

    public function testProcessStocks(): void
    {
        $data = [
            'id_no,date,stock_name,price',
            '1,11-02-2020,AaPL,320',
            '2,2020-02-11,gOogL,1510',
            '3,11 Feb 2020,msft,185',
            '4,2020/02/12,GOOGL,1518',
        ];

        $handle = fopen($this->filename, 'wb');
        foreach ($data as $line) {
            $row = explode(',', $line);
            fputcsv($handle, $row);
        }
        fclose($handle);

        $got = (new StockFile)->process($this->filename);
        $want = [
            'AAPL' => ['2020-02-11' => 320],
            'GOOGL' => [
                '2020-02-11' => 1510,
                '2020-02-12' => 1518,
            ],
            'MSFT' => ['2020-02-11' => 185],
        ];

        $this->assertEquals($got, $want);
    }

}
