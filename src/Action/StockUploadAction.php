<?php
declare (strict_types = 1);

namespace App\Action;

final class StockUploadAction
{
    /**
     * @todo move path to a settings file
     */
    public function __invoke()
    {
        $viewPath = __DIR__ . '/../../views/';
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
            require $viewPath . 'error.php';
            $var = ob_get_contents();
            ob_end_clean();
            return $var;
        }

        move_uploaded_file($_FILES['stock_file']['tmp_name'], $uploadfile);

        ob_start();
        require $viewPath . 'upload.php';
        $var = ob_get_contents();
        ob_end_clean();
        return $var;
    }
}
