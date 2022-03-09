<?php

namespace App\Imports;

use App\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ProductsImport implements ToModel, WithHeadingRow, WithChunkReading
{
    public $aUpdated = array(array('ID', 'Regular Price'));
    private $aNewPrices = array();

    /**
     * constructor
     */
    public function __construct($aNewPrices)
    {
        $this->aNewPrices = $aNewPrices;
    }
    
    /**
    * @param array $aRow
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model($aRow)
    {
        if ($aRow['type'] === 'variation') {
            $aExplodedValue = explode(' ', $aRow['attribute_1_values']);
            if (isset($this->aNewPrices[$aRow['parent']]) === true) {
                if (strtolower($aRow['attribute_1_values']) === 'single') {
                    $aRowNewPrice = array(
                        'id'            => $aRow['id'],
                        'regular_price' => $this->aNewPrices[$aRow['parent']]['future_price'] * 1.5
                    );
                    array_push($this->aUpdated, $aRowNewPrice);
                } else if (count($aExplodedValue) === 2 && strtolower($aExplodedValue[1]) === 'case') {
                    $aRowNewPrice = array(
                        'id'            => $aRow['id'],
                        'regular_price' => $this->aNewPrices[$aRow['parent']]['future_price'] * $this->aNewPrices[$aRow['parent']]['pack_uom'] * 1.5
                    );
                    array_push($this->aUpdated, $aRowNewPrice);
                }
            }
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
