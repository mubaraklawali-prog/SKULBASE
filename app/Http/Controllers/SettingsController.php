<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\SchoolSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->role === 'super_admin' && ! $user->school_id) {
            if ($request->has('school_id')) {
                $school = School::findOrFail($request->input('school_id'));
                $schoolSetting = $this->resolveSchoolSetting($school);

                return view('settings.index', compact('school', 'schoolSetting'));
            }

            return redirect()->route('schools.index')
                ->with('info', 'Select a school from the Schools page to manage its settings.');
        }

        $school = $this->resolveSchool();
        $schoolSetting = $this->resolveSchoolSetting($school);

        return view('settings.index', compact('school', 'schoolSetting'));
    }

    public function update(Request $request): RedirectResponse
    {
        $school = $this->resolveSchool();

        if (! $school) {
            return redirect()->route('schools.index')
                ->with('error', 'Select a school first to manage its settings.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'motto' => 'nullable|string|max:500',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'principal_name' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($school->logo && Storage::disk('public')->exists($school->logo)) {
                Storage::disk('public')->delete($school->logo);
            }

            $validated['logo'] = $request->file('logo')->store('schools', 'public');
        }

        if ($request->boolean('remove_logo') && $school->logo) {
            if (Storage::disk('public')->exists($school->logo)) {
                Storage::disk('public')->delete($school->logo);
            }
            $validated['logo'] = null;
        }

        unset($validated['remove_logo']);

        $school->update($validated);

        return redirect()
            ->route('settings.index')
            ->with('success', 'School profile updated successfully.');
    }

    public function academicUpdate(Request $request): RedirectResponse
    {
        $school = $this->resolveSchool();

        if (! $school) {
            return redirect()->route('schools.index')
                ->with('error', 'Select a school first to manage its settings.');
        }

        $validated = $request->validate([
            'current_session' => 'required|string|max:20',
            'current_term' => 'required|string|in:First Term,Second Term,Third Term',
            'school_open_time' => 'required|date_format:H:i',
            'school_close_time' => 'required|date_format:H:i|after:school_open_time',
        ]);

        $school->setting()->updateOrCreate(
            ['school_id' => $school->id],
            $validated
        );

        return redirect()
            ->route('settings.index')
            ->with('success', 'Academic settings updated successfully.');
    }

    public function systemUpdate(Request $request): RedirectResponse
    {
        $school = $this->resolveSchool();

        if (! $school) {
            return redirect()->route('schools.index')
                ->with('error', 'Select a school first to manage its settings.');
        }

        $validated = $request->validate([
            'timezone' => 'required|string|max:100',
            'date_format' => 'required|string|max:20',
            'time_format' => 'required|string|in:12 Hour,24 Hour',
            'currency' => 'required|string|max:10',
            'currency_symbol' => 'required|string|max:10',
            'maintenance_mode' => 'sometimes|boolean',
            'maintenance_message' => 'nullable|string|max:1000',
        ]);

        $validated['maintenance_mode'] = $request->boolean('maintenance_mode');

        $school->setting()->updateOrCreate(
            ['school_id' => $school->id],
            $validated
        );

        return redirect()
            ->route('settings.index')
            ->with('success', 'System settings updated successfully.');
    }

    public function notificationUpdate(Request $request): RedirectResponse
    {
        $school = $this->resolveSchool();

        if (! $school) {
            return redirect()->route('schools.index')
                ->with('error', 'Select a school first to manage its settings.');
        }

        $validated = $request->validate([
            'default_sender_name' => 'required|string|max:255',
            'default_reply_email' => 'required|email|max:255',
            'email_notifications' => 'sometimes|boolean',
            'assignment_notifications' => 'sometimes|boolean',
            'attendance_notifications' => 'sometimes|boolean',
            'result_notifications' => 'sometimes|boolean',
            'fee_notifications' => 'sometimes|boolean',
            'announcement_notifications' => 'sometimes|boolean',
            'event_notifications' => 'sometimes|boolean',
            'admission_notifications' => 'sometimes|boolean',
        ]);

        $validated['email_notifications'] = $request->boolean('email_notifications');
        $validated['assignment_notifications'] = $request->boolean('assignment_notifications');
        $validated['attendance_notifications'] = $request->boolean('attendance_notifications');
        $validated['result_notifications'] = $request->boolean('result_notifications');
        $validated['fee_notifications'] = $request->boolean('fee_notifications');
        $validated['announcement_notifications'] = $request->boolean('announcement_notifications');
        $validated['event_notifications'] = $request->boolean('event_notifications');
        $validated['admission_notifications'] = $request->boolean('admission_notifications');

        $school->setting()->updateOrCreate(
            ['school_id' => $school->id],
            $validated
        );

        return redirect()
            ->route('settings.index')
            ->with('success', 'Notification settings updated successfully.');
    }

    public function backupMaintenance(): View
    {
        $systemInfo = [
            'laravel_version' => app()->version(),
            'php_version' => PHP_VERSION,
            'database_driver' => config('database.default'),
            'app_env' => config('app.env'),
            'app_url' => config('app.url'),
            'timezone' => config('app.timezone'),
            'server_time' => now()->format('Y-m-d H:i:s'),
            'storage_usage' => $this->getStorageUsage(),
        ];

        $isMaintenanceMode = app()->isDownForMaintenance();

        return view('settings.backup-maintenance', compact('systemInfo', 'isMaintenanceMode'));
    }

    public function about(): View
    {
        $systemInfo = [
            'laravel_version' => app()->version(),
            'php_version' => PHP_VERSION,
            'database_driver' => config('database.default'),
            'app_env' => config('app.env'),
            'app_version' => '1.0.0',
            'current_year' => date('Y'),
        ];

        return view('settings.about', compact('systemInfo'));
    }

    public function clearCache(): RedirectResponse
    {
        try {
            Artisan::call('cache:clear');

            return redirect()
                ->route('settings.backup-maintenance')
                ->with('success', 'Application cache cleared successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('settings.backup-maintenance')
                ->with('error', 'Failed to clear cache: '.$e->getMessage());
        }
    }

    public function clearConfigCache(): RedirectResponse
    {
        try {
            Artisan::call('config:clear');

            return redirect()
                ->route('settings.backup-maintenance')
                ->with('success', 'Configuration cache cleared successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('settings.backup-maintenance')
                ->with('error', 'Failed to clear config cache: '.$e->getMessage());
        }
    }

    public function clearRouteCache(): RedirectResponse
    {
        try {
            Artisan::call('route:clear');

            return redirect()
                ->route('settings.backup-maintenance')
                ->with('success', 'Route cache cleared successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('settings.backup-maintenance')
                ->with('error', 'Failed to clear route cache: '.$e->getMessage());
        }
    }

    public function clearViewCache(): RedirectResponse
    {
        try {
            Artisan::call('view:clear');

            return redirect()
                ->route('settings.backup-maintenance')
                ->with('success', 'View cache cleared successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('settings.backup-maintenance')
                ->with('error', 'Failed to clear view cache: '.$e->getMessage());
        }
    }

    public function createStorageLink(): RedirectResponse
    {
        try {
            $targetPath = public_path('storage');
            $linkPath = storage_path('app/public');

            if (File::exists($targetPath)) {
                return redirect()
                    ->route('settings.backup-maintenance')
                    ->with('info', 'Storage link already exists.');
            }

            Artisan::call('storage:link');

            return redirect()
                ->route('settings.backup-maintenance')
                ->with('success', 'Storage link created successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('settings.backup-maintenance')
                ->with('error', 'Failed to create storage link: '.$e->getMessage());
        }
    }

    public function createBackup(): RedirectResponse
    {
        try {
            $backupDir = storage_path('app/backups');

            if (! File::isDirectory($backupDir)) {
                File::makeDirectory($backupDir, 0755, true);
            }

            $timestamp = now()->format('Y-m-d_H-i-s');
            $filename = "backup_{$timestamp}.sql";
            $filepath = $backupDir.DIRECTORY_SEPARATOR.$filename;

            $connection = config('database.default');
            $config = config("database.connections.{$connection}");

            $command = $this->buildBackupCommand($config, $filepath);

            $process = proc_open($command, [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);

            if (! is_resource($process)) {
                throw new \Exception('Failed to start backup process.');
            }

            fclose($pipes[0]);
            $stdout = stream_get_contents($pipes[1]);
            $stderr = stream_get_contents($pipes[2]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            $exitCode = proc_close($process);

            if ($exitCode !== 0) {
                throw new \Exception($stderr ?: 'Backup process failed with exit code: '.$exitCode);
            }

            $fileSize = File::exists($filepath) ? $this->formatBytes(File::size($filepath)) : 'unknown';

            return redirect()
                ->route('settings.backup-maintenance')
                ->with('success', "Database backup created successfully: {$filename} ({$fileSize})");
        } catch (\Exception $e) {
            return redirect()
                ->route('settings.backup-maintenance')
                ->with('error', 'Failed to create backup: '.$e->getMessage());
        }
    }

    public function enableMaintenance(): RedirectResponse
    {
        try {
            Artisan::call('down', [
                '--secret' => config('app.key'),
            ]);

            return redirect()
                ->route('settings.backup-maintenance')
                ->with('success', 'Maintenance mode enabled successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('settings.backup-maintenance')
                ->with('error', 'Failed to enable maintenance mode: '.$e->getMessage());
        }
    }

    public function disableMaintenance(): RedirectResponse
    {
        try {
            Artisan::call('up');

            return redirect()
                ->route('settings.backup-maintenance')
                ->with('success', 'Maintenance mode disabled successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('settings.backup-maintenance')
                ->with('error', 'Failed to disable maintenance mode: '.$e->getMessage());
        }
    }

    private function getStorageUsage(): string
    {
        try {
            $path = storage_path('app');
            $totalSize = 0;

            if (File::isDirectory($path)) {
                $files = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS)
                );

                foreach ($files as $file) {
                    if ($file->isFile()) {
                        $totalSize += $file->getSize();
                    }
                }
            }

            return $this->formatBytes($totalSize);
        } catch (\Exception $e) {
            return 'N/A';
        }
    }

    private function buildBackupCommand(array $config, string $filepath): string
    {
        $driver = $config['driver'] ?? 'mysql';

        return match ($driver) {
            'mysql' => $this->buildMysqlCommand($config, $filepath),
            'pgsql' => $this->buildPgsqlCommand($config, $filepath),
            'sqlite' => $this->buildSqliteCommand($config, $filepath),
            default => throw new \Exception("Unsupported database driver: {$driver}"),
        };
    }

    private function buildMysqlCommand(array $config, string $filepath): string
    {
        $host = escapeshellarg($config['host'] ?? '127.0.0.1');
        $port = escapeshellarg($config['port'] ?? '3306');
        $database = escapeshellarg($config['database']);
        $username = escapeshellarg($config['username']);
        $password = $config['password'] ?? '';

        $passwordArg = $password ? '-p'.escapeshellarg($password) : '';

        return "mysqldump -h {$host} -P {$port} -u {$username} {$passwordArg} {$database} > ".escapeshellarg($filepath).' 2>&1';
    }

    private function buildPgsqlCommand(array $config, string $filepath): string
    {
        $host = escapeshellarg($config['host'] ?? '127.0.0.1');
        $port = escapeshellarg($config['port'] ?? '5432');
        $database = escapeshellarg($config['database']);
        $username = escapeshellarg($config['username']);

        $envVars = $config['password'] ? 'PGPASSWORD='.escapeshellarg($config['password']).' ' : '';

        return "{$envVars}pg_dump -h {$host} -p {$port} -U {$username} {$database} > ".escapeshellarg($filepath).' 2>&1';
    }

    private function buildSqliteCommand(array $config, string $filepath): string
    {
        $database = $config['database'] ?? '';

        return 'sqlite3 '.escapeshellarg($database).' .dump > '.escapeshellarg($filepath).' 2>&1';
    }

    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision).' '.$units[$pow];
    }

    private function resolveSchool()
    {
        $user = auth()->user();

        if ($user->role === 'super_admin') {
            return $user->school;
        }

        abort_unless($user->school_id, 403, 'No school assigned to your account.');

        return $user->school;
    }

    private function resolveSchoolSetting($school): SchoolSetting
    {
        return $school->setting()->firstOrCreate(['school_id' => $school->id]);
    }
}
