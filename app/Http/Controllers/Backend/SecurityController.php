<?php

namespace App\Http\Controllers\Backend;

use App\Models\IPBan;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SecurityController extends Controller
{
    /**
     * Show IP bans management page
     */
    public function showIPBans(Request $request): View
    {
        $query = IPBan::orderBy('banned_until', 'desc')
            ->orderBy('last_attempt_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status === 'active') {
            $query->whereNotNull('banned_until')
                ->where('banned_until', '>', now());
        } elseif ($request->has('status') && $request->status === 'expired') {
            $query->where(function ($q) {
                $q->whereNull('banned_until')
                    ->orWhere('banned_until', '<=', now());
            });
        }

        $bans = $query->paginate(50);

        return view('backend.security.ip-bans', [
            'bans' => $bans,
            'status' => $request->get('status', 'all'),
        ]);
    }

    /**
     * Release an IP ban
     */
    public function releaseBan(IPBan $ban): RedirectResponse
    {
        $ban->update([
            'banned_until' => null,
            'failed_attempts' => 0,
            'reason' => null,
        ]);

        return redirect()->route('backend.security.ip-bans')
            ->with('success', "IP ban for {$ban->ip_address} has been released.");
    }

    /**
     * View ban history for an IP
     */
    public function viewBanHistory(IPBan $ban): View
    {
        // Get related records (would need a logs table in a real scenario)
        return view('backend.security.ban-history', [
            'ban' => $ban,
        ]);
    }

    /**
     * Delete a ban record (cleanup)
     */
    public function deleteBan(IPBan $ban): RedirectResponse
    {
        $ban->delete();

        return redirect()->route('backend.security.ip-bans')
            ->with('success', "Ban record for {$ban->ip_address} has been deleted.");
    }
}
