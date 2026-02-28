<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class BackupController extends Controller
{
    /**
     * Download the SQLite database file.
     */
    public function downloadDatabase()
    {
        $dbPath = database_path('database.sqlite');

        if (!file_exists($dbPath)) {
            return redirect()->back()->with('error', 'Database file not found!');
        }

        $filename = 'backup_database_' . date('Y_m_d_His') . '.sqlite';

        return Response::download($dbPath, $filename);
    }
}
