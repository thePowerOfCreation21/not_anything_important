<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentModel;
use App\Actions\WalletHistoryAction;

class WalletHistoryController extends Controller
{
    public function store(Request $request, $id)
    {
        return response()->json([
            'message' => 'ok',
            'data' => (new WalletHistoryAction())
                ->setRequest($request)
                ->setValidationRule('store')
                ->storeAmountById($id)
        ]);
    }
}
