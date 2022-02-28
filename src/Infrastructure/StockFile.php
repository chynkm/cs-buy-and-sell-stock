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
            $date = new DateTime($data[1]);
            $stocks[strtoupper($data[2])][$date->format(self::DATE_FORMAT)] = $data[3];
        }
        fclose($handle);

        return $stocks;
    }
}
