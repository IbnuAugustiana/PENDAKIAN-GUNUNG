<?php
// Quick script to simulate calling PendaftaranController::store
require __DIR__ . '/../vendor/autoload.php';

// Boot the framework
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

// Now use facades
Illuminate\Foundation\Application::setInstance($app);

// Use Eloquent
use App\Models\RutePendakian;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request as HttpRequest;

$r = RutePendakian::first();
if (! $r) {
    echo "NO_RUTE_FOUND\n";
    exit(1);
}

// login user with id 1 if exists
$user = User::first();
if (! $user) {
    echo "NO_USER_FOUND\n";
    exit(1);
}

Auth::login($user);

// build a Request object with minimal required fields
$data = [
    'rute_pendakian_id' => $r->id_rute,
    'tanggal_pendakian' => date('Y-m-d', strtotime('+1 day')),
    'jumlah_pendaki' => 1,
    'jenis_identitas' => 'KTP',
    'no_identitas' => '1234567890',
    'anggota' => [],
];

$req = HttpRequest::create('/pendaftaran', 'POST', $data);
$req->setUserResolver(function() use ($user){ return $user; });

try {
    $controller = new App\Http\Controllers\PendaftaranController();
    $res = $controller->store($req);
    echo "RESULT:\n";
    if ($res instanceof Illuminate\Http\JsonResponse) {
        echo $res->getContent() . "\n";
    } elseif ($res instanceof Illuminate\Http\RedirectResponse) {
        echo "REDIRECT TO: " . $res->getTargetUrl() . "\n";
    } else {
        var_export($res);
    }
} catch (\Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}

// cleanup
$kernel->terminate($request, $response);
