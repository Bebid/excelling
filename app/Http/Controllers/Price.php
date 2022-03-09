<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\PriceImport;
use App\Imports\ProductsImport;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;

class Price extends Controller
{
    public function import(Request $oRequest)
    {
        $oPriceImport = new PriceImport();
        Excel::import($oPriceImport, $oRequest->file('new'));
        
        $oProductsImport = new ProductsImport($oPriceImport->getRecordsWithChange());
        Excel::import($oProductsImport, $oRequest->file('old'));

        return Excel::download(new ProductsExport($oProductsImport->aUpdated), 'new_prices.csv');
    }
}
