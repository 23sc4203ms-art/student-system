<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Degree;
use Illuminate\Http\Request;

class DegreeController extends Controller
{
    public function index()
    {
        $degrees = Degree::orderBy('id')->get();

        return view('degrees.index', compact('degrees'));
    }

    public function create()
    {
        return view('degrees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:degrees,name'],
        ]);

        $degree = Degree::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'action' => 'created', 'model' => 'degree', 'record' => ['id' => $degree->id, 'name' => $degree->name]]);
        }

        return redirect()->route('degrees.show', $degree->id)->with('success', 'Degree added successfully.');
    }

    public function show(string $id)
    {
        $degree = Degree::findOrFail($id);

        return view('degrees.show', compact('degree'));
    }

    public function edit(string $id)
    {
        $degree = Degree::findOrFail($id);

        return view('degrees.edit', compact('degree'));
    }

    public function update(Request $request, string $id)
    {
        $degree = Degree::findOrFail($id);
        $before = $degree->only(['name']);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:degrees,name,' . $degree->id],
        ]);

        $degree->name = $validated['name'];
        $degree->save();
        $degree->refresh();

        $after = $degree->only(['name']);
        $changes = [];

        if (($before['name'] ?? null) != ($after['name'] ?? null)) {
            $changes['name'] = [
                'old' => $before['name'] ?? null,
                'new' => $after['name'] ?? null,
            ];
        }

        ActivityLog::create([
            'action' => 'EDIT',
            'subject_type' => 'Degree',
            'record_id' => $degree->id,
            'description' => 'Edited degree: ' . $degree->name,
            'changes' => $changes,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'action' => 'updated', 'model' => 'degree', 'record' => ['id' => $degree->id, 'name' => $degree->name]]);
        }

        return redirect()->route('degrees.show', $degree->id)->with('success', 'Degree updated successfully.');
    }

    public function destroy(string $id)
    {
        $degree = Degree::findOrFail($id);
        $before = $degree->only(['name']);
        $degree->delete();

        ActivityLog::create([
            'action' => 'DELETE',
            'subject_type' => 'Degree',
            'record_id' => $id,
            'description' => 'Deleted degree: ' . ($before['name'] ?? 'N/A'),
            'changes' => [
                'name' => [
                    'old' => $before['name'] ?? null,
                    'new' => null,
                ],
            ],
        ]);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'action' => 'deleted', 'model' => 'degree', 'record' => ['id' => $id]]);
        }

        return redirect()->route('degrees.index')->with('success', 'Degree deleted successfully.');
    }
}
