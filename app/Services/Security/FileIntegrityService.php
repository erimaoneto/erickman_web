<?php

namespace App\Services\Security;

use Illuminate\Support\Facades\Log;

class FileIntegrityService
{
    /**
     * Extensions strictly forbidden in asset/upload folders.
     */
    protected array $forbiddenExtensions = [
        'php', 'php3', 'php4', 'php5', 'phtml', 'phar',
        'sh', 'bash', 'py', 'pl', 'cgi',
        'exe', 'bat', 'cmd', 'vbs', 'msi',
        'js', 'jsp', 'asp', 'aspx'
    ];

    /**
     * Dangerous shell / backdoor code signatures.
     */
    protected array $backdoorSignatures = [
        'eval(base64_decode',
        'eval(gzinflate',
        'eval(gzuncompress',
        'system($_GET',
        'system($_POST',
        'system($_REQUEST',
        'passthru($_GET',
        'passthru($_POST',
        'passthru($_REQUEST',
        'shell_exec($_GET',
        'shell_exec($_POST',
        'shell_exec($_REQUEST',
        'assert($_POST',
        'assert($_GET',
        'c99shell',
        'r57shell',
        'WSOsetcookie',
    ];

    /**
     * Scan public image and upload directories for unauthorized executable files.
     */
    public function scanUploadDirectories(?string $customPath = null): array
    {
        $targetPath = $customPath ?? public_path('images');
        $flaggedFiles = [];
        $scannedCount = 0;

        if (!is_dir($targetPath)) {
            return [
                'scanned_count' => 0,
                'flagged_files' => [],
            ];
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($targetPath, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $scannedCount++;
                $filename = $file->getFilename();

                // Skip .htaccess which is our security shield
                if ($filename === '.htaccess') {
                    continue;
                }

                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                // Check extension
                if (in_array($ext, $this->forbiddenExtensions, true)) {
                    $flaggedFiles[] = [
                        'path' => $file->getPathname(),
                        'filename' => $filename,
                        'reason' => "Ekstensi terlarang pada folder publik: .{$ext}",
                        'severity' => 'CRITICAL',
                    ];
                    continue;
                }

                // Check for double extensions (e.g. image.php.jpg)
                if (preg_match('/\.php[0-9]?\./i', $filename)) {
                    $flaggedFiles[] = [
                        'path' => $file->getPathname(),
                        'filename' => $filename,
                        'reason' => 'Pola double extension terdeteksi (potensi bypass)',
                        'severity' => 'CRITICAL',
                    ];
                }
            }
        }

        return [
            'scanned_count' => $scannedCount,
            'flagged_files' => $flaggedFiles,
        ];
    }

    /**
     * Scan for backdoor / web shell signatures in public and storage areas.
     */
    public function scanForBackdoorSignatures(?string $basePath = null): array
    {
        $scanDirs = [
            public_path(),
            storage_path('app'),
        ];

        $suspiciousMatches = [];

        foreach ($scanDirs as $dir) {
            if (!is_dir($dir)) {
                continue;
            }

            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS)
            );

            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    // Only inspect script-like files and files under 2MB
                    $filename = $file->getFilename();
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                    // In public, index.php is allowed; inspect other PHP files
                    if ($file->getPathname() === public_path('index.php')) {
                        continue;
                    }

                    if (in_array($ext, ['php', 'phtml', 'txt', 'html'], true) && $file->getSize() < 2 * 1024 * 1024) {
                        $content = @file_get_contents($file->getPathname());
                        if ($content !== false) {
                            foreach ($this->backdoorSignatures as $signature) {
                                if (stripos($content, $signature) !== false) {
                                    $suspiciousMatches[] = [
                                        'path' => $file->getPathname(),
                                        'filename' => $filename,
                                        'signature' => $signature,
                                        'severity' => 'CRITICAL',
                                    ];
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $suspiciousMatches;
    }

    /**
     * Audit file permissions to ensure no world-writable (777) files or directories exist.
     */
    public function auditPermissions(): array
    {
        $insecureItems = [];
        $dirsToCheck = [
            base_path('app'),
            base_path('config'),
            base_path('routes'),
            public_path(),
        ];

        foreach ($dirsToCheck as $dir) {
            if (is_dir($dir)) {
                $perms = fileperms($dir);
                // Check if world writable (0002)
                if (($perms & 0002) !== 0) {
                    $insecureItems[] = [
                        'path' => $dir,
                        'permissions' => substr(sprintf('%o', $perms), -4),
                        'reason' => 'Direktori berizin 777 (world-writable), rentan disusupi',
                        'severity' => 'WARNING',
                    ];
                }
            }
        }

        return $insecureItems;
    }

    /**
     * Verify web-server shield files (.htaccess in public/ and public/images/).
     */
    public function verifyServerShields(): array
    {
        $shields = [];

        // 1. Root public .htaccess
        $rootHtaccess = public_path('.htaccess');
        $rootShieldActive = false;
        if (file_exists($rootHtaccess)) {
            $content = file_get_contents($rootHtaccess);
            $rootShieldActive = str_contains($content, 'Deny from all') || str_contains($content, 'nosniff');
        }
        $shields['root_htaccess'] = [
            'path' => $rootHtaccess,
            'exists' => file_exists($rootHtaccess),
            'shield_active' => $rootShieldActive,
        ];

        // 2. Images .htaccess
        $imagesHtaccess = public_path('images/.htaccess');
        $imagesShieldActive = false;
        if (file_exists($imagesHtaccess)) {
            $content = file_get_contents($imagesHtaccess);
            $imagesShieldActive = str_contains($content, 'Deny from all');
        }
        $shields['images_htaccess'] = [
            'path' => $imagesHtaccess,
            'exists' => file_exists($imagesHtaccess),
            'shield_active' => $imagesShieldActive,
        ];

        return $shields;
    }

    /**
     * Perform complete File Integrity Audit.
     */
    public function runFullAudit(): array
    {
        $uploadsScan = $this->scanUploadDirectories();
        $backdoors = $this->scanForBackdoorSignatures();
        $permIssues = $this->auditPermissions();
        $shields = $this->verifyServerShields();

        $totalThreats = count($uploadsScan['flagged_files']) + count($backdoors) + count($permIssues);
        $status = 'SECURE';

        if ($totalThreats > 0) {
            $status = count($uploadsScan['flagged_files']) > 0 || count($backdoors) > 0 ? 'CRITICAL' : 'WARNING';
        }

        $result = [
            'status' => $status,
            'scanned_files_count' => $uploadsScan['scanned_count'],
            'flagged_files' => $uploadsScan['flagged_files'],
            'backdoor_matches' => $backdoors,
            'permission_issues' => $permIssues,
            'shields' => $shields,
            'audited_at' => now()->toIso8601String(),
        ];

        if ($status !== 'SECURE') {
            Log::channel('single')->warning('[SECURITY SENTINEL] File integrity warning detected', $result);
        }

        return $result;
    }
}
