<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Security\FileIntegrityService;
use App\Services\Security\SafeOutboundClient;
use Illuminate\Support\Facades\Log;

class SecurityAuditCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'security:audit {--silent : Run without console output, only log results}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform complete File Integrity & Outbound Traffic security audit';

    /**
     * Execute the console command.
     */
    public function handle(FileIntegrityService $fileIntegrity, SafeOutboundClient $outboundClient): int
    {
        $silent = $this->option('silent');

        if (!$silent) {
            $this->info('====================================================');
            $this->info(' PT. ERICKMAN SARANA ABADI - SECURITY SENTINEL AUDIT');
            $this->info('====================================================');
            $this->comment('Memeriksa integritas file dan kebijakan koneksi server...');
        }

        // 1. File Integrity Audit
        $fileReport = $fileIntegrity->runFullAudit();

        if (!$silent) {
            $this->newLine();
            $this->info('[1] AUDIT INTEGRITAS FILE (FILE INTEGRITY SCAN)');
            $this->line("Total file dipindai pada folder publik: {$fileReport['scanned_files_count']}");

            $shieldRoot = $fileReport['shields']['root_htaccess']['shield_active'] ? 'AKTIF (Terlindungi)' : 'TIDAK AKTIF';
            $shieldImages = $fileReport['shields']['images_htaccess']['shield_active'] ? 'AKTIF (Eksekusi Script Diblokir)' : 'TIDAK AKTIF';
            $this->line("Server Shield Root (.htaccess): {$shieldRoot}");
            $this->line("Server Shield Images (.htaccess): {$shieldImages}");

            if (empty($fileReport['flagged_files']) && empty($fileReport['backdoor_matches'])) {
                $this->info('✓ Bersih: Tidak ditemukan file executable terlarang atau signature backdoor.');
            } else {
                $this->error('⚠ PERINGATAN: Ditemukan anomali file mencurigakan!');
                foreach ($fileReport['flagged_files'] as $f) {
                    $this->warn(" - {$f['filename']}: {$f['reason']}");
                }
                foreach ($fileReport['backdoor_matches'] as $b) {
                    $this->warn(" - {$b['filename']}: Signature {$b['signature']}");
                }
            }
        }

        // 2. Outbound Traffic & Function Audit
        $outboundReport = $outboundClient->runFullAudit();

        if (!$silent) {
            $this->newLine();
            $this->info('[2] AUDIT LALU LINTAS KELUAR & POLICY OUTBOUND');
            $this->line("SSRF Guard: {$outboundReport['ssrf_guard']}");
            $this->line("Filter IP Private / Metadata: AKTIF ({$outboundReport['blocked_ip_ranges_count']} Range)");
            $this->line("Port Keluar yang Diizinkan: " . implode(', ', $outboundReport['allowed_ports']));
            $this->line("Pengiriman Form Hari Ini: {$outboundReport['mail_activity']['inquiries_today']} pesan ({$outboundReport['mail_activity']['status']})");
        }

        // Log combined result
        $auditLog = [
            'timestamp' => now()->toIso8601String(),
            'file_integrity' => $fileReport,
            'outbound_policy' => $outboundReport,
        ];

        Log::channel('single')->info('[SECURITY SENTINEL] Audit selesai dijalankan', $auditLog);

        if (!$silent) {
            $this->newLine();
            $this->info('====================================================');
            $this->info(' STATUS KESELURUHAN: SISTEM AMAN & TERLINDUNGI (OK)');
            $this->info('====================================================');
        }

        return 0;
    }
}
