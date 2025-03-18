<?php

namespace App\Http\Controllers;

use App\Models\AdminAction;
use Illuminate\Http\Request;

class AdminActionController extends Controller
{
    public function index()
    {
        $adminActions = AdminAction::paginate(10);
        return view('admin.admin_logs', compact('adminActions'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'admin_id' => 'required|exists:users,user_id',
            'action_type' => 'required|string|max:255',
        ]);

        $action = AdminAction::create($validatedData);

        return response()->json(['success' => true, 'action' => $action]);
    }

    public function show($id)
    {
        $action = AdminAction::with('admin')->find($id);
        if (!$action) {
            return response()->json(['success' => false, 'message' => 'Action not found']);
        }

        return response()->json(['success' => true, 'action' => $action]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'action_type' => 'sometimes|string|max:255',
        ]);

        $action = AdminAction::find($id);
        if (!$action) {
            return response()->json(['success' => false, 'message' => 'Action not found']);
        }

        $action->update($validatedData);

        return response()->json(['success' => true, 'action' => $action]);
    }

    public function destroy($id)
    {
        $action = AdminAction::find($id);
        if (!$action) {
            return response()->json(['success' => false, 'message' => 'Action not found']);
        }

        $action->delete();

        return response()->json(['success' => true, 'message' => 'Action deleted successfully']);
    }
}
