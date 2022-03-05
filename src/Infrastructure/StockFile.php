<?php
declare (strict_types = 1);

namespace App\Infrastructure;

use DateTime;
use Exception;

class StockFile
{
    /**
     * @todo move to config file
     */
    const DATE_FORMAT = 'Y-m-d';
    const ROW_ITEMS = 4;

    public function process(string $filename): array
    {
        if (!file_exists($filename)) {
            throw new Exception('The file does not exists');
        }

        if (!filesize($filename)) {
            throw new Exception('The file is empty');
        }

        $columnFlag = true;
        $stocks = [];
        $handle = fopen($filename, 'r');

        while (($data = fgetcsv($handle, 0, ',')) !== false) {
            if ($columnFlag) {
                $columnFlag = false;
                continue;
            }

            if (count($data) != self::ROW_ITEMS) {
                throw new Exception('The CSV file is missing a column entry');
            }

            if (empty($data[1])) {
                throw new Exception('The CSV file is missing a date value');
            }

            if (strtotime($data[1]) == false) {
                throw new Exception('The CSV file contains an incorrect date value');
            }

            if (empty($data[2])) {
                throw new Exception('The CSV file is missing a stock value');
            }

            if (empty($data[3])) {
                throw new Exception('The CSV file is missing a price value');
            }

            if (!is_numeric($data[3])) {
                throw new Exception('The CSV file contains an incorrect price value');
            }

            $date = new DateTime($data[1]);
            $stocks[strtoupper($data[2])][$date->format(self::DATE_FORMAT)] = intval($data[3]);
        }
        fclose($handle);

        return $stocks;
    }
}
