<?php

namespace App\Http\Controllers;

use App\Models\ModerationRule;
use Illuminate\Http\Request;

class ModerationController extends Controller
{
    public function index()
    {
        return ModerationRule::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rule_type' => 'required|in:warn,mute,ban',
            'threshold' => 'required|integer|min:1',
            'duration_minutes' => 'nullable|integer|min:1',
            'enabled' => 'boolean',
        ]);
        $rule = ModerationRule::create($validated);
        return response()->json($rule, 201);
    }

    public function update(Request $request, $id)
    {
        $rule = ModerationRule::findOrFail($id);
        $validated = $request->validate([
            'rule_type' => 'sometimes|in:warn,mute,ban',
            'threshold' => 'sometimes|integer|min:1',
            'duration_minutes' => 'nullable|integer|min:1',
            'enabled' => 'boolean',
        ]);
        $rule->update($validated);
        return response()->json($rule);
    }

    public function destroy($id)
    {
        ModerationRule::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}