<?php
/**
 * Setup script for audit log directory structure
 * 
 * This script creates the necessary directory structure for audit logging
 * and sets appropriate permissions for security.
 */

// Define base paths
define('STORAGE_PATH', dirname(__DIR__) . '/storage');
define('AUDIT_BASE_PATH', STORAGE_PATH . '/data/tenants');

/**
 * Create audit directory structure for a tenant
 */
function createTenantAuditStructure(string $tenantId): bool
{
    $tenantAuditDir = AUDIT_BASE_PATH . '/' . $tenantId . '/audit';
    
    try {
        // Create directory with recursive flag
        if (!mkdir($tenantAuditDir, 0755, true)) {
            throw new Exception("Failed to create directory: {$tenantAuditDir}");
        }
        
        // Set secure permissions
        chmod($tenantAuditDir, 0755);
        
        // Create .htaccess to prevent direct web access
        $htaccessContent = "Order deny,allow\nDeny from all\n";
        file_put_contents($tenantAuditDir . '/.htaccess', $htaccessContent);
        
        echo "✓ Created audit directory for tenant: {$tenantId}\n";
        return true;
        
    } catch (Exception $e) {
        echo "✗ Error creating audit structure for {$tenantId}: " . $e->getMessage() . "\n";
        return false;
    }
}

/**
 * Setup base audit directory structure
 */
function setupBaseAuditStructure(): bool
{
    try {
        // Create base directories
        $baseDirs = [
            STORAGE_PATH,
            STORAGE_PATH . '/data',
            STORAGE_PATH . '/data/tenants'
        ];
        
        foreach ($baseDirs as $dir) {
            if (!is_dir($dir)) {
                if (!mkdir($dir, 0755, true)) {
                    throw new Exception("Failed to create base directory: {$dir}");
                }
                chmod($dir, 0755);
                echo "✓ Created base directory: {$dir}\n";
            }
        }
        
        // Create .htaccess for the entire tenants directory
        $tenantsHtaccess = AUDIT_BASE_PATH . '/.htaccess';
        if (!file_exists($tenantsHtaccess)) {
            $htaccessContent = "# Prevent direct access to tenant data\nOrder deny,allow\nDeny from all\n";
            file_put_contents($tenantsHtaccess, $htaccessContent);
            echo "✓ Created security .htaccess for tenants directory\n";
        }
        
        return true;
        
    } catch (Exception $e) {
        echo "✗ Error setting up base structure: " . $e->getMessage() . "\n";
        return false;
    }
}

/**
 * Verify audit log integrity
 */
function verifyAuditIntegrity(): array
{
    $issues = [];
    
    try {
        // Check if base directory exists and is writable
        if (!is_dir(AUDIT_BASE_PATH)) {
            $issues[] = "Base audit directory does not exist: " . AUDIT_BASE_PATH;
        } elseif (!is_writable(AUDIT_BASE_PATH)) {
            $issues[] = "Base audit directory is not writable: " . AUDIT_BASE_PATH;
        }
        
        // Check for existing tenant directories
        if (is_dir(AUDIT_BASE_PATH)) {
            $tenantDirs = glob(AUDIT_BASE_PATH . '/*', GLOB_ONLYDIR);
            
            foreach ($tenantDirs as $tenantDir) {
                $tenantId = basename($tenantDir);
                $auditDir = $tenantDir . '/audit';
                
                if (!is_dir($auditDir)) {
                    $issues[] = "Audit directory missing for tenant: {$tenantId}";
                } elseif (!is_writable($auditDir)) {
                    $issues[] = "Audit directory not writable for tenant: {$tenantId}";
                }
            }
        }
        
    } catch (Exception $e) {
        $issues[] = "Error during verification: " . $e->getMessage();
    }
    
    return $issues;
}

/**
 * Clean old audit logs (older than specified months)
 */
function cleanOldAuditLogs(int $monthsToKeep = 12): array
{
    $cleaned = [];
    $cutoffDate = new DateTime();
    $cutoffDate->sub(new DateInterval("P{$monthsToKeep}M"));
    
    try {
        if (!is_dir(AUDIT_BASE_PATH)) {
            return $cleaned;
        }
        
        $tenantDirs = glob(AUDIT_BASE_PATH . '/*', GLOB_ONLYDIR);
        
        foreach ($tenantDirs as $tenantDir) {
            $auditDir = $tenantDir . '/audit';
            
            if (!is_dir($auditDir)) {
                continue;
            }
            
            $logFiles = glob($auditDir . '/*.log');
            
            foreach ($logFiles as $logFile) {
                $fileDate = DateTime::createFromFormat('Y-m', basename($logFile, '.log'));
                
                if ($fileDate && $fileDate < $cutoffDate) {
                    if (unlink($logFile)) {
                        $cleaned[] = "Deleted old log: {$logFile}";
                    }
                }
            }
        }
        
    } catch (Exception $e) {
        $cleaned[] = "Error during cleanup: " . $e->getMessage();
    }
    
    return $cleaned;
}

/**
 * Main execution
 */
function main(): void
{
    echo "=== Audit Log Setup Script ===\n\n";
    
    // Setup base structure
    echo "1. Setting up base directory structure...\n";
    if (!setupBaseAuditStructure()) {
        echo "✗ Failed to setup base structure. Exiting.\n";
        exit(1);
    }
    
    // Create audit structure for example tenant
    echo "\n2. Creating example tenant audit structure...\n";
    createTenantAuditStructure('example-tenant-ulid');
    
    // Verify integrity
    echo "\n3. Verifying audit log integrity...\n";
    $issues = verifyAuditIntegrity();
    
    if (empty($issues)) {
        echo "✓ Audit log structure is properly configured\n";
    } else {
        echo "✗ Issues found:\n";
        foreach ($issues as $issue) {
            echo "  - {$issue}\n";
        }
    }
    
    // Show usage instructions
    echo "\n4. Usage Instructions:\n";
    echo "   - To create audit structure for a new tenant:\n";
    echo "     php scripts/setup-audit-logs.php create <tenant-ulid>\n";
    echo "   - To verify audit log integrity:\n";
    echo "     php scripts/setup-audit-logs.php verify\n";
    echo "   - To clean old audit logs:\n";
    echo "     php scripts/setup-audit-logs.php clean [months-to-keep]\n";
    
    echo "\n=== Setup Complete ===\n";
}

// Handle command line arguments
if (php_sapi_name() === 'cli') {
    $args = array_slice($argv, 1);
    
    if (empty($args)) {
        main();
    } else {
        $command = $args[0];
        
        switch ($command) {
            case 'create':
                if (empty($args[1])) {
                    echo "Error: Tenant ULID required\n";
                    echo "Usage: php scripts/setup-audit-logs.php create <tenant-ulid>\n";
                    exit(1);
                }
                createTenantAuditStructure($args[1]);
                break;
                
            case 'verify':
                $issues = verifyAuditIntegrity();
                if (empty($issues)) {
                    echo "✓ Audit log structure is properly configured\n";
                } else {
                    echo "✗ Issues found:\n";
                    foreach ($issues as $issue) {
                        echo "  - {$issue}\n";
                    }
                    exit(1);
                }
                break;
                
            case 'clean':
                $months = isset($args[1]) ? (int)$args[1] : 12;
                $cleaned = cleanOldAuditLogs($months);
                if (empty($cleaned)) {
                    echo "✓ No old logs to clean\n";
                } else {
                    echo "Cleaned logs:\n";
                    foreach ($cleaned as $message) {
                        echo "  {$message}\n";
                    }
                }
                break;
                
            default:
                echo "Unknown command: {$command}\n";
                echo "Available commands: create, verify, clean\n";
                exit(1);
        }
    }
} else {
    // Web access - run verification only
    header('Content-Type: text/plain');
    main();
}
