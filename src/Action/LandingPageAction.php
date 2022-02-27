<?php
declare (strict_types = 1);

namespace App\Action;

final class LandingPageAction
{
    /**
     * @todo move path to a settings file
     */
    public function __invoke()
    {
        $viewPath = __DIR__ . '/../../views/';
        require $viewPath . 'index.php';
    }
}
