<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class PriceImport implements ToModel, WithHeadingRow, WithChunkReading
{
    private $aRecords = array();
    private $sType = 'wooc';

    /**
     * constructor
     */
    public function __construct($sType)
    {
        $this->sType = $sType;
    }
    
    /**
    * @param array $rows
    */
    public function model(array $aRow)
    {
        $aRowMinimize = array(
            'future_price'  => $aRow['future_price'],
            'pack_uom'      => $aRow['pack_uom']
        );

        $sCommonId = ($this->sType === 'wooc') ? 'sku' : 'upc';
        $this->aRecords[$aRow[$sCommonId]] = $aRowMinimize;
    }

    public function headingRow()
    {
        return 3;
    }

    public function getRecordsWithChange()
    {
        return $this->aRecords;
    }
    
    public function chunkSize(): int
    {
        return 1000;
    }
}
