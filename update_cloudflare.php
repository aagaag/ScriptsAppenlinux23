<?php

// Debug: Initialize log file for last run
$parent_path = dirname(__FILE__);
$file = $parent_path . '/update-cloudflare-dns.log';
if (!is_executable($file)) {
    touch($file);
}

$log_file = $parent_path . '/update-cloudflare-dns.log';

// Start output buffering to capture all outputs
ob_start();

echo "==> " . date("Y-m-d H:i:s") . "\n";

// Debug: Validate if config-file exists
$config_file = $parent_path . '/update-cloudflare-dns.conf';
if ($argc < 2) {
    if (!file_exists($config_file)) {
        echo 'Debug: Error! Missing configuration file update-cloudflare-dns.conf or invalid syntax!' . "\n";
        exit(0);
    }
    require $config_file;
} else {
    if (!file_exists($parent_path . '/' . $argv[1])) {
        echo 'Debug: Error! Missing configuration file ' . $argv[1] . ' or invalid syntax!' . "\n";
        exit(0);
    }
    require $parent_path . '/' . $argv[1];
}

$proxied = true;

// Debug: Output the what_ip parameter from config
echo "Debug: Value of what_ip is " . $what_ip . "\n";

// ... (no change here for other validations)

$what_ip = "external";

// Debug: Check what_ip and proxied parameters
echo "Debug: what_ip is set to " . $what_ip . "\n";
echo "Debug: proxied is set to " . $proxied . "\n";

// Get external IP from https://checkip.amazonaws.com
if ($what_ip == "external") {
    $ip = trim(file_get_contents("https://checkip.amazonaws.com"));
    // Debug: Output the fetched external IP
    echo "Debug: Fetched external IP is " . $ip . "\n";
    // ... (no change here)
}

// Get Internal ip from primary interface
// ... (PHP equivalent code here)
// Inside this block, add a debug statement for the internal IP, similar to the external IP debug

// Build coma separated array from dns_record parameter to update multiple A records
// ... (PHP equivalent code here)

// Debug: Output the DNS records to be updated
echo "Debug: DNS records to be updated are " . implode(", ", $dns_records) . "\n";

// The rest of your script (no changes in logic)
// ... (PHP equivalent code here)
// You can add debug lines before or after key operations, like fetching DNS record info or updating DNS records.

// Save the output to log file
file_put_contents($log_file, ob_get_contents());
ob_end_clean();

// Rest of the PHP script
// ...

?>
