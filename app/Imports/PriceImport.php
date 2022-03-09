<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PriceImport implements ToModel, WithHeadingRow
{
    private $aRecords = array();
    
    /**
    * @param array $rows
    */
    public function model(array $aRow)
    {
        $aRowMinimize = array(
            'future_price'  => $aRow['future_price'],
            'pack_uom'      => $aRow['pack_uom'],
            'change'        => $aRow['change']
        );
        if ($aRowMinimize['change'] !== floatval(0)) {
            $this->aRecords[$aRow['sku']] = $aRowMinimize;
        }
    }

    public function headingRow()
    {
        return 3;
    }

    public function getRecordsWithChange()
    {
        return $this->aRecords;
    }
}
