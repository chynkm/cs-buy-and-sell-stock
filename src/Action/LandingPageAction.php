<?php
declare (strict_types = 1);

namespace App\Action;

final class LandingPageAction
{
    public function __invoke()
    {
        require VIEW_PATH . 'index.php';
    }
}
