<?php
declare (strict_types = 1);

namespace App\Action;

use App\Domain\Stock;
use Exception;

final class StockInfoAction
{
    public function __invoke()
    {
        $getStock = isset($_GET['stock']) ? $_SESSION['stocks'][$_GET['stock']] : [];
        $stock = new Stock($getStock);

        try {
            $stockInfo = $stock->stockInfo(
                $_GET['start_datepicker'],
                $_GET['end_datepicker']
            );
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();

            ob_start();
            require VIEW_PATH . 'stockError.php';
            $html = ob_get_contents();
            ob_end_clean();

            http_response_code(422);
            return json_encode($html);
        }

        ob_start();
        require VIEW_PATH . 'info.php';
        $html = ob_get_contents();
        ob_end_clean();

        return json_encode($html);
    }
}
