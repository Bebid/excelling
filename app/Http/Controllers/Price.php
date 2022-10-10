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
    private $aRequiredColumns = array(
        'new-pricing' => array(
            'sku', 'future_price', 'pack_uom', 'upc'
        ),
        'old-pricing' => array(
            'wooc' => array(
                'id', 'parent', 'type', 'attribute_1_values', 'regular_price'
            ),
            'bigc' => array('product_upcean', 'product_name', 'price', 'product_id')
        ),
    );

    /**
     * generate a new pricing list
     * 
     * @param   object  $Request
     * 
     * @return  excel
     */
    public function generateNewPricing(Request $oRequest)
    {
        set_time_limit(0);
        ini_set('memory_limit', '500M');

        $aRules = array(
            'type'          => 'required|in:wooc,bigc',
            'old-pricing'   => 'required',
            'new-pricing'   => 'required',
            'multiplier'    => 'required' 
        );

        $oRequest->validate($aRules);

        $aResult = $this->validateColumns($oRequest);

        if (!$aResult['valid']) {
            throw ValidationException::withMessages($aResult['messages']);
        }

        $oPriceImport = new PriceImport($oRequest->type);
        Excel::import($oPriceImport, $oRequest->file('new-pricing'));
        
        $oProductsImport = new ProductsImport($oPriceImport->getRecordsWithChange(), $oRequest->type, (float)$oRequest->multiplier);
        Excel::import($oProductsImport, $oRequest->file('old-pricing'));

        return Excel::download(new ProductsExport($oProductsImport->aUpdated), '[new-price] ' . $oRequest->file('old-pricing')->getClientOriginalName());
    }

    /**
     * validate columns
     * 
     * @param   object  $oRequest
     * 
     * @param   array
     */
    public function validateColumns($oRequest)
    {
        $aErrorMessages = array();
        $aWithError = false;

        $aHeadings = (new HeadingRowImport(3))->toArray($oRequest->file('new-pricing'));
        $aResult = $this->checkColumns($aHeadings[0][0], $this->aRequiredColumns['new-pricing']);

        if (!$aResult['is_complete']) {
            $aErrorMessages['new-pricing'] = 'Following columns are missing; ' . implode(', ', $aResult['missing_columns']);
            $aWithError = true;
        }

        $aHeadings = (new HeadingRowImport)->toArray($oRequest->file('old-pricing'));
        $aResult = $this->checkColumns($aHeadings[0][0], $this->aRequiredColumns['old-pricing'][$oRequest->type]);

        if (!$aResult['is_complete']) {
            $aErrorMessages['old-pricing'] = 'Following columns are missing; ' . implode(', ', $aResult['missing_columns']);
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
    public function checkColumns($aHeadings, $aRequiredColumns)
    {
        $aMissingColumns = array();

        $bIsComplete = true;
        foreach ($aRequiredColumns as $sColumn) {
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
