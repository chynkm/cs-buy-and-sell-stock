<?php
declare (strict_types = 1);

namespace App\Action;

use App\Infrastructure\StockFile;
use Exception;

final class StockUploadAction
{
    public function __construct()
    {
        session_start();
    }

    public function __invoke()
    {
        $uploadfile = 'storage/' . basename($_FILES['stock_file']['name']);
        $errorMessage = false;

        if ($_FILES['stock_file']['size'] == 0) {
            $errorMessage = 'A CSV file is required.';
        }

        if (!$errorMessage && $_FILES['stock_file']['type'] != 'text/csv') {
            $errorMessage = 'You have uploaded an invalid file. Please use a CSV file.';
        }

        if ($errorMessage) {
            http_response_code(422);
            ob_start();
            require VIEW_PATH . 'error.php';
            $var = ob_get_contents();
            ob_end_clean();
            return $var;
        }

        move_uploaded_file($_FILES['stock_file']['tmp_name'], $uploadfile);

        try {
            $_SESSION['stocks'] = (new StockFile)->process($uploadfile);
            $stocks = [];
            foreach ($_SESSION['stocks'] as $stock => $datePrice) {
                $stocks[] = $stock;
            }
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();

            ob_start();
            require VIEW_PATH . 'error.php';
            $var = ob_get_contents();
            ob_end_clean();

            http_response_code(422);
            return $var;
        }
        sort($stocks);

        ob_start();
        require VIEW_PATH . 'upload.php';
        $var = ob_get_contents();
        ob_end_clean();
        return $var;
    }
}
