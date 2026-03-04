<?php

namespace App\Http\Controllers;

use App\Models\Profession;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfessionController extends Controller
{
    protected $workspace;
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->workspace = Workspace::find(getWorkspaceId());
            $this->user = getAuthenticatedUser();
            return $next($request);
        });
    }

    public function index()
    {
        $professions = $this->workspace->professions()->paginate(10);
        return view('professions.index', compact('professions'));
    }

    public function create()
    {
        return view('professions.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:professions,name,NULL,id,workspace_id,' . $this->workspace->id,
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $profession = Profession::create([
            'name' => $request->name,
            'description' => $request->description,
            'workspace_id' => $this->workspace->id
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Profession created successfully',
            'data' => $profession
        ]);
    }

    public function edit($id)
    {
        $profession = Profession::where('workspace_id', $this->workspace->id)->findOrFail($id);
        return view('professions.edit', compact('profession'));
    }

    public function update(Request $request, $id)
    {
        $profession = Profession::where('workspace_id', $this->workspace->id)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:professions,name,' . $id . ',id,workspace_id,' . $this->workspace->id,
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $profession->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Profession updated successfully',
            'data' => $profession
        ]);
    }

    public function destroy($id)
    {
        $profession = Profession::where('workspace_id', $this->workspace->id)->findOrFail($id);
        $profession->delete();

        return response()->json([
            'error' => false,
            'message' => 'Profession deleted successfully'
        ]);
    }

    public function list(Request $request)
    {
        $search = $request->input('search', '');
        $professions = Profession::where('workspace_id', $this->workspace->id)
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'error' => false,
            'data' => $professions
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $page = $request->input('page', 1);
        $limit = 30;
        
        $professions = Profession::where('workspace_id', $this->workspace->id)
            ->where('name', 'like', '%' . $query . '%')
            ->orderBy('name', 'asc')
            ->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'items' => $professions->items(),
            'total_count' => $professions->total(),
            'incomplete_results' => false,
            'total_pages' => $professions->lastPage()
        ]);
    }
}