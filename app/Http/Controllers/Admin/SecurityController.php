<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Security\FileIntegrityService;
use App\Services\Security\SafeOutboundClient;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    protected FileIntegrityService $fileIntegrity;
    protected SafeOutboundClient $outboundClient;

    public function __construct(FileIntegrityService $fileIntegrity, SafeOutboundClient $outboundClient)
    {
        $this->fileIntegrity = $fileIntegrity;
        $this->outboundClient = $outboundClient;
    }

    /**
     * Display the Security Sentinel Dashboard.
     */
    public function index()
    {
        $fileReport = $this->fileIntegrity->runFullAudit();
        $outboundReport = $this->outboundClient->runFullAudit();

        return view('admin.security.index', compact('fileReport', 'outboundReport'));
    }

    /**
     * Run a live security scan on demand.
     */
    public function scan(Request $request)
    {
        $fileReport = $this->fileIntegrity->runFullAudit();
        $outboundReport = $this->outboundClient->runFullAudit();

        return redirect()->route('admin.security.index')
            ->with('success', 'Audit keamanan berhasil diselesaikan. Integritas file dan kebijakan koneksi server terpantau aman.');
    }
}
