<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\CoinDeskServices;

class CoindeskController extends Controller
{
    public function index () {
        $coindesk = new CoinDeskServices();
        $data = $coindesk->priceIndex();

        return response()->json($data);
    }
}
