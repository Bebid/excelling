<?php

namespace App\Imports;

use App\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ProductsImport implements ToModel, WithHeadingRow, WithChunkReading
{
    public $aUpdated = array();
    private $aNewPrices = array();
    private $sType = 'wooc';
    private $fMultiplier = 1.5;

    /**
     * constructor
     * 
     * @param   array   $aNewPrices
     * @param   string  $sType
     * @param   float   $fMultiplier
     */
    public function __construct($aNewPrices, $sType, $fMultiplier)
    {
        $this->aNewPrices = $aNewPrices;
        $this->sType = $sType;

        if ($sType === 'wooc') {
            array_push($this->aUpdated, array('ID', 'Regular Price'));
        } else if ($sType === 'bigc') {
            array_push($this->aUpdated, array('Product ID', 'Price'));
        }

        $this->fMultiplier = $fMultiplier;
    }
    
    /**
    * @param array $aRow
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model($aRow)
    {
        if ($this->sType === 'wooc') {
            $this->handleWoocProducts($aRow);
        } else if ($this->sType === 'bigc') {
            $this->handleBigcProducts($aRow);
        }
    }

    /**
     * handle woocommerce products
     * 
     * @param   array   $aRows
     */
    private function handleWoocProducts($aRow)
    {
        if ($aRow['type'] === 'variation') {
            $aExplodedValue = explode(' ', $aRow['attribute_1_values']);
            if (isset($this->aNewPrices[$aRow['parent']]) === true) {
                if (strtolower($aRow['attribute_1_values']) === 'single') {
                    $aRowNewPrice = array(
                        'id'            => $aRow['id'],
                        'regular_price' => $this->aNewPrices[$aRow['parent']]['future_price'] * $this->fMultiplier
                    );
                    array_push($this->aUpdated, $aRowNewPrice);
                } else if (count($aExplodedValue) === 2 && strtolower($aExplodedValue[1]) === 'case') {
                    $aRowNewPrice = array(
                        'id'            => $aRow['id'],
                        'regular_price' => $this->aNewPrices[$aRow['parent']]['future_price'] * $this->aNewPrices[$aRow['parent']]['pack_uom'] * $this->fMultiplier
                    );
                    array_push($this->aUpdated, $aRowNewPrice);
                }
                
            }
        }
    }

    /**
     * handle bigcommerce products
     * 
     * @param   array   $aRows
     */
    private function handleBigcProducts($aRow)
    {
        if (isset($this->aNewPrices[$aRow['product_upcean']]) === true) {
            $aExplodedValue = explode(' - ', $aRow['product_name']);
            if (count($aExplodedValue) > 1 && strstr(strtolower($aRow['product_name']), 'case')) {
                $aRowNewPrice = array(
                    'product_id'    => $aRow['product_id'],
                    'price' => $this->aNewPrices[$aRow['product_upcean']]['future_price'] * $this->aNewPrices[$aRow['product_upcean']]['pack_uom'] * $this->fMultiplier
                );
            } else {
                $aRowNewPrice = array(
                    'product_id'    => $aRow['product_id'],
                    'price' => $this->aNewPrices[$aRow['product_upcean']]['future_price'] * $this->fMultiplier
                );
            }
            array_push($this->aUpdated, $aRowNewPrice);
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
