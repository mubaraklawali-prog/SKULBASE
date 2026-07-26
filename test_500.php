<?php

use App\Models\User;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

require __DIR__.'/vendor/autoload.php';

$app = (require_once __DIR__.'/bootstrap/app.php');

$kernel = $app->make(Kernel::class);

$user = User::where('role', 'school_admin')->first();

if (! $user) {
    echo "No school_admin user found.\n";
    exit(1);
}

echo "Testing as: {$user->name} ({$user->email})\n\n";

Auth::login($user);

$response = $kernel->handle(
    $request = Request::create('/dashboard', 'GET')
);

echo 'Status: '.$response->getStatusCode()."\n";

if ($response->getStatusCode() === 500) {
    echo "HTTP 500 DETECTED\n";
    echo substr($response->getContent(), 0, 5000)."\n";
} else {
    echo "Dashboard loaded OK\n";
}

$kernel->terminate($request, $response);
