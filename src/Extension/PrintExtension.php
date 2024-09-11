<?php

namespace App\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class PrintExtension extends AbstractExtension
{

    public function getFilters(): array
    {
        return [
            new TwigFilter('print', [$this, 'print']),
        ];
    }

    public function print($element): string
    {
        return print_r($element, true);
    }

}