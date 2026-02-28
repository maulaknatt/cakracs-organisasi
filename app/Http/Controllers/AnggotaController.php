<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    /**
     * Check if user can access anggota module
     */
    private function checkAccess()
    {
        $user = auth()->user();
        if (! $user || (! $user->isSuperAdmin() && ! $user->isAdmin())) {
            abort(403, 'Akses ditolak. Hanya Admin/Super Admin yang dapat mengakses halaman ini.');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->checkAccess();

        $query = \App\Models\User::with('role');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('jabatan', 'like', "%{$search}%");
            });
        }

        // Filter: Jabatan
        if ($request->filled('jabatan')) {
            $query->where('jabatan', $request->jabatan);
        }

        // Filter: Role
        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        // Filter: Status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $request->get("per_page", 20); 
        $anggota = $query->paginate($perPage)->withQueryString();

        // Get filter options
        $jabatanList = \App\Models\User::distinct()->pluck('jabatan')->filter()->sort();
        $roles = \DB::table('roles')->where('nama_role', '!=', 'Super Admin')->orderBy('nama_role')->get();

        // Get online user IDs (active in last 5 minutes)
        $onlineUserIds = \DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '>=', now()->subMinutes(5)->getTimestamp())
            ->pluck('user_id')
            ->toArray();

        return view('dashboard.anggota.index', compact('anggota', 'jabatanList', 'roles', 'onlineUserIds'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAccess();
        $roles = \DB::table('roles')->where('nama_role', '!=', 'Super Admin')->orderBy('nama_role')->get();

        return view('dashboard.anggota.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkAccess();
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'jabatan' => 'nullable|string',
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'required|boolean',
        ]);
        $user = new \App\Models\User;
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = bcrypt($validated['password']);
        $user->jabatan = $validated['jabatan'] ?? null;
        $user->role_id = $validated['role_id'];
        $user->is_active = $validated['is_active'];
        $user->save();

        // Log activity
        ActivityLogService::logCreate('anggota', $user, $request);

        return redirect()->route('anggota.index')->with('toast', [
            'type' => 'success',
            'message' => 'User berhasil ditambahkan.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $this->checkAccess();
        $anggota = \App\Models\User::with('role')->findOrFail($id);

        return view('dashboard.anggota.show', compact('anggota'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $this->checkAccess();
        $anggota = \App\Models\User::findOrFail($id);
        $roles = \DB::table('roles')->where('nama_role', '!=', 'Super Admin')->orderBy('nama_role')->get();

        return view('dashboard.anggota.edit', compact('anggota', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->checkAccess();
        $anggota = \App\Models\User::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$anggota->id,
            'jabatan' => 'nullable|string',
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'required|boolean',
        ]);
        $anggota->name = $validated['name'];
        $anggota->email = $validated['email'];
        $anggota->jabatan = $validated['jabatan'] ?? null;
        $oldData = $anggota->toArray();
        $anggota->role_id = $validated['role_id'];
        $anggota->is_active = $validated['is_active'];
        $anggota->save();

        // Log activity - khusus untuk perubahan role
        if ($oldData['role_id'] != $anggota->role_id) {
            ActivityLogService::log(
                action: 'update',
                module: 'anggota',
                targetId: $anggota->id,
                description: "Mengubah role user {$anggota->name} dari role ID {$oldData['role_id']} ke {$anggota->role_id}",
                oldValue: ['role_id' => $oldData['role_id']],
                newValue: ['role_id' => $anggota->role_id],
                request: $request
            );
        }

        // Log update biasa
        ActivityLogService::logUpdate('anggota', $anggota, $oldData, $request);

        return redirect()->route('anggota.show', $anggota->id)->with('toast', [
            'type' => 'success',
            'message' => 'Data user berhasil diupdate.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Anggota $anggota)
    {
        $this->checkAccess();

        // Log activity before delete
        ActivityLogService::logDelete('anggota', $anggota, request());

        $anggota->delete();

        return redirect()->route('anggota.index')->with('toast', [
            'type' => 'success',
            'message' => 'Anggota berhasil dihapus.',
        ]);
    }
}
