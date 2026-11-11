<?php
require __DIR__ . '/../vendor/autoload.php';

// Try getenv first, otherwise parse project .env
$serverKey = getenv('MIDTRANS_SERVER_KEY') ?: '';
if (empty($serverKey)) {
    $env = @file_get_contents(__DIR__ . '/../.env');
    if ($env !== false) {
        if (preg_match('/^MIDTRANS_SERVER_KEY=(.+)$/m', $env, $m)) {
            $serverKey = trim($m[1]);
        }
    }
}

try {
    // Configure Midtrans
    \Midtrans\Config::$serverKey = $serverKey;
    \Midtrans\Config::$isProduction = false;
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;

    $params = [
        'transaction_details' => [
            'order_id' => 'TEST-' . rand(1000,9999),
            'gross_amount' => 10000,
        ],
    ];

    $token = \Midtrans\Snap::getSnapToken($params);
    echo "SNAP TOKEN: " . $token . PHP_EOL;
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
}
