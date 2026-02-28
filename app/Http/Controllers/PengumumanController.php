<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use App\Services\ActivityLogService;
use App\Models\User;
use App\Notifications\AnnouncementNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    /**
     * Check if user can access pengumuman module
     */
    private function checkAccess()
    {
        if (!auth()->check()) {
            abort(401);
        }
    }

    private function checkManageAccess()
    {
        $user = auth()->user();
        if (!$user || (!$user->isSuperAdmin() && !$user->isAdmin() && !$user->isPengurus())) {
            abort(403, 'Akses ditolak. Hanya Admin dan Pengurus yang dapat melakukan tindakan ini.');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->checkAccess();

        $query = \App\Models\Pengumuman::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('isi', 'like', "%{$search}%");
            });
        }

        // Filter: Tanggal dari
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }

        // Filter: Tanggal sampai
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        // Filter: Tahun
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        // Filter: Pinned only
        if ($request->filled('pinned_only')) {
            $query->where('is_pinned', true);
        }

        // Sort
        $sortBy    = $request->get('sort_by', 'tanggal');
        $sortOrder = 'desc';

        // Pinned items always come first, then apply user sorting
        $query->orderBy('is_pinned', 'desc');

        match ($sortBy) {
            'tanggal_asc'     => $query->orderBy('tanggal', 'asc')->orderBy('id', 'asc'),
            'likes_count'     => $query->withCount('likes')->orderBy('likes_count', 'desc')->orderBy('tanggal', 'desc'),
            'comments_count'  => $query->withCount('comments')->orderBy('comments_count', 'desc')->orderBy('tanggal', 'desc'),
            default           => $query->orderBy('tanggal', 'desc')->orderBy('id', 'desc'),
        };

        $pengumuman = $query->withCount(['likes', 'comments'])->paginate(100)->withQueryString();

        // Get filter options
        $years = \App\Models\Pengumuman::selectRaw('EXTRACT(YEAR FROM tanggal)::int as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('dashboard.pengumuman.index', compact('pengumuman', 'years'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkManageAccess();

        return view('dashboard.pengumuman.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkManageAccess();

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        $validated['user_id'] = auth()->id();
        $pengumuman = \App\Models\Pengumuman::create($validated);

        // Log activity
        ActivityLogService::logCreate('pengumuman', $pengumuman, $request);

        // Notify all other users
        $users = User::where('id', '!=', auth()->id())->get();
        Notification::send($users, new AnnouncementNotification($pengumuman, auth()->user()));

        return redirect()->route('pengumuman.index')
            ->with('toast', [
            'type' => 'success',
            'message' => 'Pengumuman berhasil dibuat.',
        ]);
    }

    public function show(Request $request, Pengumuman $pengumuman)
    {
        $this->checkAccess();

        $sortBy = $request->get('sort', 'latest'); // latest or popular

        $pengumuman->load(['comments' => function ($q) use ($sortBy) {
            $q->whereNull('parent_id')
              ->with(['user', 'replies.user', 'replies' => function($sq) {
                  $sq->orderBy('created_at', 'asc');
              }]);
              
            if ($sortBy === 'popular') {
                $q->withCount('replies')->orderBy('replies_count', 'desc')->orderBy('created_at', 'desc');
            } else {
                $q->orderBy('created_at', 'desc');
            }
        }, 'likes', 'user']);
        $pengumuman->loadCount(['likes', 'comments']);

        return view('dashboard.pengumuman.show', compact('pengumuman', 'sortBy'));
    }

    /**
     * Toggle like for pengumuman.
     */
    public function toggleLike(Pengumuman $pengumuman)
    {
        $this->checkAccess();
        $user = auth()->user();

        $like = $pengumuman->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $status = 'unliked';
        }
        else {
            $pengumuman->likes()->create(['user_id' => $user->id]);
            $status = 'liked';
        }

        return back()->with('toast', [
            'type' => 'success',
            'message' => $status === 'liked' ? 'Anda menyukai pengumuman ini.' : 'Batal menyukai pengumuman.',
        ]);
    }

    /**
     * Store a comment.
     */
    public function storeComment(Request $request, Pengumuman $pengumuman)
    {
        $this->checkAccess();

        $validated = $request->validate([
            'isi' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:pengumuman_comments,id',
        ]);

        $comment = $pengumuman->comments()->create([
            'user_id' => auth()->id(),
            'isi' => $validated['isi'],
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        $comment->load('user');

        // Extract mentions and create notifications (basic implementation)
        preg_match_all('/@([a-zA-Z0-9_.-]+)/', $validated['isi'], $matches);
        if (!empty($matches[1])) {
            $slugs = array_unique($matches[1]);
            // Ideally we find users by the slug, but for now we search by name or username if exists
            $users = \App\Models\User::whereIn('name', $slugs)->get();
            foreach ($users as $u) {
                // if notifications setup
            }
        }

        if ($request->wantsJson()) {
            if ($comment->parent_id) {
                $html = view('dashboard.pengumuman.partials.reply_item', ['reply' => $comment, 'parent_id' => $comment->parent_id])->render();
            } else {
                $html = view('dashboard.pengumuman.partials.comment_item', ['komentar' => $comment, 'pengumuman' => $pengumuman])->render();
            }
            
            return response()->json([
                'success' => true,
                'html' => $html,
                'message' => 'Komentar berhasil dikirim.'
            ]);
        }

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Komentar berhasil dikirim.',
        ]);
    }

    /**
     * Update a comment.
     */
    public function updateComment(Request $request, \App\Models\PengumumanComment $comment)
    {
        $this->checkAccess();

        if ($comment->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'isi' => 'required|string|max:1000',
        ]);

        $comment->update([
            'isi' => $validated['isi'],
            'is_edited' => true,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'comment' => $comment
            ]);
        }

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Komentar berhasil diupdate.',
        ]);
    }

    /**
     * Toggle like for comment.
     */
    public function toggleCommentLike(\App\Models\PengumumanComment $comment)
    {
        $this->checkAccess();
        $user = auth()->user();

        // Needs DB Table for comment likes
        $like = \Illuminate\Support\Facades\DB::table('pengumuman_comment_likes')
            ->where('pengumuman_comment_id', $comment->id)
            ->where('user_id', $user->id)
            ->first();

        if ($like) {
            \Illuminate\Support\Facades\DB::table('pengumuman_comment_likes')
                ->where('id', $like->id)->delete();
            $status = 'unliked';
        } else {
            \Illuminate\Support\Facades\DB::table('pengumuman_comment_likes')->insert([
                'pengumuman_comment_id' => $comment->id,
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $status = 'liked';
        }
        
        $likesCount = \Illuminate\Support\Facades\DB::table('pengumuman_comment_likes')
            ->where('pengumuman_comment_id', $comment->id)->count();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $status,
                'likes_count' => $likesCount
            ]);
        }

        return back();
    }

    public function destroyComment(\App\Models\PengumumanComment $comment)
    {
        $this->checkAccess();

        // Only author or admin can delete
        if ($comment->user_id !== auth()->id() && !auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $comment->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil dihapus.'
            ]);
        }

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Komentar berhasil dihapus.',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengumuman $pengumuman)
    {
        $this->checkManageAccess();

        return view('dashboard.pengumuman.edit', compact('pengumuman'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengumuman $pengumuman)
    {
        $this->checkManageAccess();

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'tanggal' => 'required|date',
        ]);

        $validated['user_id'] = auth()->id(); // Update author to current editor as requested
        $oldData = $pengumuman->toArray();
        $pengumuman->update($validated);

        // Log activity
        ActivityLogService::logUpdate('pengumuman', $pengumuman, $oldData, $request);

        return redirect()->route('pengumuman.index')
            ->with('toast', [
            'type' => 'success',
            'message' => 'Pengumuman berhasil diupdate.',
        ]);
    }

    /**
     * Toggle pin for pengumuman.
     */
    public function togglePin(Pengumuman $pengumuman)
    {
        $this->checkManageAccess();

        $pengumuman->update([
            'is_pinned' => !$pengumuman->is_pinned
        ]);

        return back()->with('toast', [
            'type' => 'success',
            'message' => $pengumuman->is_pinned ? 'Pengumuman berhasil disematkan.' : 'Sematkan dibatalkan.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengumuman $pengumuman)
    {
        $this->checkManageAccess();

        // Log activity before delete
        ActivityLogService::logDelete('pengumuman', $pengumuman, request());

        $pengumuman->delete();

        return redirect()->route('pengumuman.index')
            ->with('toast', [
            'type' => 'success',
            'message' => 'Pengumuman berhasil dihapus.',
        ]);
    }
}
