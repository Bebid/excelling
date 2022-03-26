<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\PriceImport;
use App\Imports\ProductsImport;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\HeadingRowImport;

class Price extends Controller
{
    /**
     * generate a new pricing list
     * 
     * @param   object  $Request
     * 
     * @return  excel
     */
    public function generateNewPricing(Request $oRequest)
    {
        $oRequest->validate(array(
            'product-list'  => 'required|mimes:csv,xls,xlsx',
            'new-pricing'   => 'required|mimes:csv,xls,xlsx'
        ));

        $aResult = $this->validateHeaders($oRequest);

        if (!$aResult['valid']) {
            throw ValidationException::withMessages($aResult['messages']);
        }

        $oPriceImport = new PriceImport();
        Excel::import($oPriceImport, $oRequest->file('new-pricing'));
        
        $oProductsImport = new ProductsImport($oPriceImport->getRecordsWithChange());
        Excel::import($oProductsImport, $oRequest->file('product-list'));

        return Excel::download(new ProductsExport($oProductsImport->aUpdated), 'new_prices.csv');
    }

    /**
     * validate headers
     * 
     * @param   object  $oRequest
     * 
     * @param   array
     */
    public function validateHeaders($oRequest)
    {
        $aErrorMessages = array();
        $aWithError = false;

        $aHeadings = (new HeadingRowImport(3))->toArray($oRequest->file('new-pricing'));
        $aResult = $this->checkColumns($aHeadings[0][0], 'new-pricing');

        if (!$aResult['is_complete']) {
            $aErrorMessages['new-pricing'] = 'Following columns are missing; ' . implode(', ', $aResult['missing_columns']);
            $aWithError = true;
        }

        $aHeadings = (new HeadingRowImport)->toArray($oRequest->file('product-list'));
        $aResult = $this->checkColumns($aHeadings[0][0], 'product-list');

        if (!$aResult['is_complete']) {
            $aErrorMessages['product-list'] = 'Following columns are missing; ' . implode(', ', $aResult['missing_columns']);
            $aWithError = true;
        }

        return array(
            'valid'     => !$aWithError,
            'messages'  => $aErrorMessages
        );
    }

    /**
     * check if required columns are existing
     * 
     * @param   array   $aHeadings
     * @param   string  $sType
     * 
     * @return  array
     */
    public function checkColumns($aHeadings, $sType)
    {
        $aRequiredColumns = array(
            'new-pricing'   => array('future_price', 'pack_uom', 'change', 'sku'),
            'product-list'  => array('type', 'attribute_1_values', 'parent', 'id'),
        );

        $aMissingColumns = array();

        $bIsComplete = true;
        foreach ($aRequiredColumns[$sType] as $sColumn) {
            if (!in_array($sColumn, $aHeadings)) {
                array_push($aMissingColumns, $sColumn);
                $bIsComplete = false;
            }
        }

        return array(
            'is_complete'       => $bIsComplete,
            'missing_columns'   => $aMissingColumns
        );
    }
}
