<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return User::all(['id', 'name', 'email', 'is_admin']);
    }

    public function ban(Request $request, $id)
    {
        $user = User::findOrFail($id);
        // Actually set a banned flag; here we simulate by updating a column 'is_banned'
        // Assuming you add 'is_banned' column to users table.
        $user->is_banned = true;
        $user->save();
        return response()->json(['message' => 'User banned successfully']);
    }
}