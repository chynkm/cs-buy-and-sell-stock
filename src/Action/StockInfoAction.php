<?php
declare (strict_types = 1);

namespace App\Action;

use App\Domain\Stock;

final class StockInfoAction
{
    /**
     * @todo move path to a settings file
     */
    public function __invoke()
    {
        $stock = new Stock($_SESSION['stocks'][$_GET['stock']]);
        try {
            $stockInfo = $stock->stockInfo(
                $_GET['start_datepicker'],
                $_GET['end_datepicker']
            );
        } catch (Exception $e) {
            // @todo need to complete
        }

        $viewPath = __DIR__ . '/../../views/';
        ob_start();
        require $viewPath . 'info.php';
        $html = ob_get_contents();
        ob_end_clean();

        return json_encode($html);
    }
}
