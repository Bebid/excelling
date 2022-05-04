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
        'new-pricing'   => array(),
        'product-list'  => array(),
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
        $aRules = array(
            'product-list'      => 'required',
            'new-pricing'       => 'required',
            'product-type'      => 'required',
            'product-type-2'    => 'required',
            'product-price-id'  => 'required',
            'product-id'        => 'required',
            'price-price-id'    => 'required',
            'price-new-price'   => 'required',
            'price-pack'        => 'required',
            'price-change'      => 'required'
        );

        if ($oRequest->input('price-multiple-sheets')) {
            $aRules['price-sheet-name']  = 'required';
        }

        $this->aRequiredColumns['new-pricing'] = array(
            $oRequest->input('price-price-id'),
            $oRequest->input('price-new-price'),
            $oRequest->input('price-pack'),
            $oRequest->input('price-change')
        );

        $this->aRequiredColumns['product-list'] = array(
            $oRequest->input('product-type'),
            $oRequest->input('product-type-2'),
            $oRequest->input('product-price-id'),
            $oRequest->input('product-id')
        );

        $oRequest->validate($aRules);

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
        $aMissingColumns = array();

        $bIsComplete = true;
        foreach ($this->aRequiredColumns[$sType] as $sColumn) {
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
