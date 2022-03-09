<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class ProductsExport implements FromArray
{
    private $aProductsNewPrices = array();

    public function __construct($aProductsNewPrices)
    {
        $this->aProductsNewPrices = $aProductsNewPrices;
    }

    /**
    * @return array
    */
    public function array(): array
    {
        return array_values($this->aProductsNewPrices);
    }
}
