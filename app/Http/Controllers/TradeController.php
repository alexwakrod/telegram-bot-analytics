<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use Illuminate\Http\Request;

class TradeController extends Controller
{
    public function index()
    {
        $trades = Trade::with(['initiator', 'acceptor'])->orderBy('created_at', 'desc')->get();
        return response()->json($trades);
    }

    public function show($id)
    {
        $trade = Trade::findOrFail($id);
        return response()->json($trade);
    }

    public function destroy($id)
    {
        Trade::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}