<?php

use App\Models\Admission;
use App\Models\Attendance;
use App\Models\FeePayment;
use App\Models\School;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

echo 'Schools: '.School::count().PHP_EOL;
echo 'Students: '.Student::count().PHP_EOL;
echo 'Teachers: '.Teacher::count().PHP_EOL;
echo 'FeePayments: '.FeePayment::count().PHP_EOL;
echo 'Attendance: '.Attendance::count().PHP_EOL;
echo 'Admissions: '.Admission::count().PHP_EOL;
echo 'Users: '.User::count().PHP_EOL;
echo PHP_EOL.'--- Student Growth Data ---'.PHP_EOL;
$twelveMonthsAgo = Carbon\Carbon::now()->subMonths(11)->startOfMonth();
$studentGrowth = Student::where('created_at', '>=', $twelveMonthsAgo)
    ->orderBy('created_at')
    ->get()
    ->groupBy(fn ($s) => $s->created_at->format('M Y'))
    ->map(fn ($group) => $group->count())
    ->toArray();
echo 'Student growth labels: '.json_encode(array_keys($studentGrowth)).PHP_EOL;
echo 'Student growth data: '.json_encode(array_values($studentGrowth)).PHP_EOL;
echo PHP_EOL.'--- User with super_admin role ---'.PHP_EOL;
$superAdmin = User::where('role', 'super_admin')->first();
if ($superAdmin) {
    echo 'Found: '.$superAdmin->name.' (ID: '.$superAdmin->id.')'.PHP_EOL;
} else {
    echo 'No super admin found!'.PHP_EOL;
}
