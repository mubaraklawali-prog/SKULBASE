<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SchoolAdmin500Test extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['database.default' => 'mysql']);
        config(['database.connections.mysql' => [
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'port' => '3306',
            'database' => 'school_system',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
        ]]);
        config(['session.driver' => 'database']);

        DB::purge('mysql');
    }

    public function test_school_admin_no_500(): void
    {
        $user = User::where('role', 'school_admin')->first();

        if (! $user) {
            $this->fail('No user with role "school_admin" found in database.');
        }

        $this->actingAs($user);

        echo "\nUser: ID={$user->id} role=\"{$user->role}\" school_id=".var_export($user->school_id, true)."\n\n";

        $routes = [
            '/dashboard',
            '/announcements',
            '/messages',
            '/events',
            '/my-subscription',
            '/assignments',
            '/admissions',
            '/settings',
            '/reports',
            '/teachers',
            '/students',
            '/classes',
            '/subjects',
            '/fees',
            '/attendance',
            '/periods',
            '/timetables',
            '/results',
            '/results/scores',
            '/results/report-cards',
            '/results/approvals',
        ];

        $expectedForbidden = [
            '/schools',
            '/plans',
            '/subscriptions',
        ];

        $results = [];

        foreach ($routes as $uri) {
            $response = $this->get($uri);
            $status = $response->status();

            $label = match (true) {
                $status === 200 => '200 OK',
                $status === 302 => '302 Redirect',
                $status === 403 => '403 Forbidden',
                $status === 404 => '404 Not Found',
                $status === 500 => '500 !!!',
                default => (string) $status,
            };

            echo "  GET {$uri} => {$label}\n";

            if ($status >= 400) {
                $content = $response->getContent();
                preg_match('/<title>(.*?)<\/title>/', $content, $matches);
                $title = $matches[1] ?? 'unknown';
                echo "    Page title: {$title}\n";

                preg_match('/<div class="error-code">(.*?)<\/div>/', $content, $codeMatches);
                $errorCode = $codeMatches[1] ?? 'n/a';
                echo "    Error code shown: {$errorCode}\n";
            }

            $results[$uri] = $status;
        }

        foreach ($expectedForbidden as $uri) {
            $response = $this->get($uri);
            $status = $response->status();
            $label = match (true) {
                $status === 200 => '200 OK',
                $status === 302 => '302 Redirect',
                $status === 403 => '403 Forbidden',
                $status === 404 => '404 Not Found',
                $status === 500 => '500 !!!',
                default => (string) $status,
            };
            echo "  GET {$uri} => {$label} (expected 403)\n";
            $results[$uri] = $status;
        }

        $fifties = array_filter($results, fn ($s) => $s === 500);
        $wrongForbidden = array_filter($expectedForbidden, fn ($uri) => $results[$uri] !== 403);

        echo "\n=== RESULT ===\n";
        echo 'Routes tested: '.count($results)."\n";
        echo '500 errors: '.count($fifties)."\n";
        echo 'Wrong forbidden: '.count($wrongForbidden)."\n";

        if (count($fifties) > 0) {
            foreach ($fifties as $uri => $s) {
                echo "  500: {$uri}\n";
            }
            $this->fail('HTTP 500 found on: '.implode(', ', array_keys($fifties)));
        }

        if (count($wrongForbidden) > 0) {
            foreach ($wrongForbidden as $uri) {
                echo "  NOT-403: {$uri} (got {$results[$uri]})\n";
            }
            $this->fail('Expected 403 on: '.implode(', ', $wrongForbidden));
        }

        $this->assertTrue(true, 'No HTTP 500 errors found.');
    }

    public function test_super_admin_still_works(): void
    {
        $user = User::where('role', 'super_admin')->first();

        if (! $user) {
            $this->fail('No user with role "super_admin" found in database.');
        }

        $this->actingAs($user);

        echo "\nSuper Admin: ID={$user->id}\n\n";

        $routes = [
            '/dashboard',
            '/schools',
            '/students',
            '/teachers',
            '/classes',
            '/subjects',
            '/fees',
            '/attendance',
            '/periods',
            '/timetables',
            '/results',
            '/reports',
            '/settings',
            '/plans',
            '/subscriptions',
        ];

        $results = [];

        foreach ($routes as $uri) {
            $response = $this->get($uri);
            $status = $response->status();

            $label = match (true) {
                $status === 200 => '200 OK',
                $status === 302 => '302 Redirect',
                $status === 403 => '403 Forbidden',
                $status === 404 => '404 Not Found',
                $status === 500 => '500 !!!',
                default => (string) $status,
            };

            echo "  GET {$uri} => {$label}\n";

            if ($status >= 400) {
                $content = $response->getContent();
                preg_match('/<title>(.*?)<\/title>/', $content, $matches);
                $title = $matches[1] ?? 'unknown';
                echo "    Page title: {$title}\n";
            }

            $results[$uri] = $status;
        }

        $fifties = array_filter($results, fn ($s) => $s === 500);
        $forbidden = array_filter($results, fn ($s) => $s === 403);

        echo "\n=== SUPER ADMIN RESULT ===\n";
        echo 'Routes tested: '.count($results)."\n";
        echo '500 errors: '.count($fifties)."\n";
        echo '403 errors: '.count($forbidden)."\n";

        if (count($fifties) > 0) {
            foreach ($fifties as $uri => $s) {
                echo "  500: {$uri}\n";
            }
            $this->fail('Super Admin got 500 on: '.implode(', ', array_keys($fifties)));
        }

        if (count($forbidden) > 0) {
            foreach ($forbidden as $uri => $s) {
                echo "  403: {$uri}\n";
            }
            $this->fail('Super Admin got 403 on: '.implode(', ', array_keys($forbidden)));
        }

        $this->assertTrue(true, 'Super Admin access verified.');
    }

    public function test_schools_sidebar_visibility(): void
    {
        $schoolAdmin = User::where('role', 'school_admin')->first();
        $superAdmin = User::where('role', 'super_admin')->first();

        if (! $schoolAdmin || ! $superAdmin) {
            $this->fail('Need both school_admin and super_admin users.');
        }

        $this->actingAs($superAdmin);
        $saContent = $this->get('/dashboard')->getContent();
        $saSeesSchools = str_contains($saContent, 'Schools');
        echo "\nSuper Admin sees Schools sidebar: ".($saSeesSchools ? 'YES' : 'NO')."\n";

        $this->actingAs($schoolAdmin);
        $scaContent = $this->get('/dashboard')->getContent();
        $scaSeesSchools = str_contains($scaContent, 'Schools');
        echo 'School Admin sees Schools sidebar: '.($scaSeesSchools ? 'YES' : 'NO')."\n";

        $this->assertTrue($saSeesSchools, 'Super Admin should see Schools menu.');
        $this->assertFalse($scaSeesSchools, 'School Admin should NOT see Schools menu.');
    }
}
