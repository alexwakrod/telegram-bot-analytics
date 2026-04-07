<?php

namespace App\Http\Controllers;

use App\Models\TempInvite;
use Illuminate\Http\Request;

class InviteController extends Controller
{
    public function index()
    {
        $invites = TempInvite::orderBy('created_at', 'desc')->get();
        return response()->json($invites);
    }

    public function destroy($id)
    {
        TempInvite::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}