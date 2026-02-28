<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PengaturanController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        $pengaturan = Pengaturan::first();
        $user = auth()->user();

        return view('dashboard.pengaturan.index', compact('pengaturan', 'user'));
    }

    /**
     * Update Organization Profile (Super Admin Only).
     */
    public function updateOrganization(Request $request)
    {
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'nama_organisasi' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048', // 2MB Max
        ]);

        $pengaturan = Pengaturan::first() ?? new Pengaturan;
        $oldData = $pengaturan->exists ? $pengaturan->toArray() : [];

        $data = ['nama_organisasi' => $validated['nama_organisasi']];

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($pengaturan->logo && Storage::disk('public')->exists($pengaturan->logo)) {
                Storage::disk('public')->delete($pengaturan->logo);
            }
            $data['logo'] = $request->file('logo')->store('logo', 'public');
        }

        $pengaturan->fill($data);
        $pengaturan->save();

        // Log activity
        if (!empty($oldData)) {
            ActivityLogService::logUpdate('pengaturan_organisasi', $pengaturan, $oldData, $request);
        }
        else {
            ActivityLogService::logCreate('pengaturan_organisasi', $pengaturan, $request);
        }

        return redirect()->route('pengaturan.index')->with('success', 'Profil Organisasi berhasil diperbarui.')->with('active_tab', 'organisasi');
    }

    /**
     * Delete Organization Logo.
     */
    public function deleteOrganizationLogo()
    {
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }

        $pengaturan = Pengaturan::first();

        if ($pengaturan && $pengaturan->logo && Storage::disk('public')->exists($pengaturan->logo)) {
            Storage::disk('public')->delete($pengaturan->logo);
            $pengaturan->update(['logo' => null]);
            return redirect()->route('pengaturan.index')->with('success', 'Logo organisasi berhasil dihapus.')->with('active_tab', 'organisasi');
        }

        return redirect()->route('pengaturan.index')->with('error', 'Logo organisasi tidak ditemukan.')->with('active_tab', 'organisasi');
    }

    /**
     * Update Personal Profile.
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'foto_profil' => 'nullable|image|max:2048',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if ($request->hasFile('foto_profil')) {
            // Delete old photo if exists
            if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            $data['foto_profil'] = $request->file('foto_profil')->store('profiles', 'public');
        }

        $user->update($data);

        return redirect()->route('pengaturan.index')
            ->with('success', 'Profil Pribadi berhasil diperbarui.')
            ->with('active_tab', 'pribadi');
    }

    /**
     * Delete Profile Photo.
     */
    public function deleteProfilePhoto()
    {
        $user = auth()->user();

        if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
            Storage::disk('public')->delete($user->foto_profil);
            $user->update(['foto_profil' => null]);
            return redirect()->route('pengaturan.index')->with('success', 'Foto profil berhasil dihapus.')->with('active_tab', 'pribadi');
        }

        return redirect()->route('pengaturan.index')->with('error', 'Foto profil tidak ditemukan.')->with('active_tab', 'pribadi');
    }

    /**
     * Update Password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('pengaturan.index')->with('success', 'Password berhasil diubah.')->with('active_tab', 'password');
    }
}
