<?php
/**
 * Debug Photo URL Generation
 * Run: php debug_photo_url.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DEBUG PHOTO URL GENERATION ===\n\n";

// 1. Check environment
echo "1. Environment Check:\n";
echo "   APP_URL: " . env('APP_URL') . "\n";
echo "   STORAGE_PUBLIC_PATH: " . env('STORAGE_PUBLIC_PATH') . "\n\n";

// 2. Test PublicStorageUrl
echo "2. PublicStorageUrl Tests:\n";
$testPaths = [
    'photos/test.jpg',
    'storage/photos/test.jpg',
    'storage_public/photos/test.jpg',
    '/storage_public/photos/test.jpg',
];

foreach ($testPaths as $testPath) {
    $url = \App\Support\PublicStorageUrl::storageUrl($testPath);
    echo "   Input:  '{$testPath}'\n";
    echo "   Output: '{$url}'\n\n";
}

// 3. Check actual student data
echo "3. Sample Student Data (with photos):\n";
$students = \App\Models\Student::whereNotNull('photo_path')
    ->where('photo_path', '!=', '')
    ->limit(5)
    ->get(['nis', 'name', 'email', 'photo_path']);

if ($students->isEmpty()) {
    echo "   ❌ No students with photos found\n\n";
} else {
    foreach ($students as $student) {
        echo "   Student: {$student->name} ({$student->nis})\n";
        echo "   Email: {$student->email}\n";
        echo "   DB photo_path: '{$student->photo_path}'\n";
        
        // Check if user exists
        $user = \App\Models\User::where('email', $student->email)->first();
        if ($user) {
            echo "   User exists: YES\n";
            echo "   User photo field: '" . ($user->photo ?? 'NULL') . "'\n";
            echo "   User photo_url: '{$user->photo_url}'\n";
            
            // Check if photo URL contains ui-avatars (fallback)
            $hasRealPhoto = $user->photo_url && !str_contains($user->photo_url, 'ui-avatars.com');
            echo "   Has real photo: " . ($hasRealPhoto ? "✅ YES" : "❌ NO (using fallback)") . "\n";
        } else {
            echo "   User exists: ❌ NO\n";
        }
        
        // Check if file exists physically
        $fullPath = '/home/sitexamy/public_html/storage_public/' . preg_replace('#^(storage_public|storage|public)/#', '', $student->photo_path);
        echo "   Full file path: {$fullPath}\n";
        echo "   File exists: " . (file_exists($fullPath) ? "✅ YES" : "❌ NO") . "\n";
        echo "\n";
    }
}

// 4. Check admin/users with direct photo
echo "4. Sample Users (with direct photo field):\n";
$users = \App\Models\User::whereNotNull('photo')
    ->where('photo', '!=', '')
    ->limit(3)
    ->get(['name', 'email', 'photo', 'role']);

if ($users->isEmpty()) {
    echo "   No users with direct photo field found\n\n";
} else {
    foreach ($users as $user) {
        echo "   User: {$user->name} ({$user->role})\n";
        echo "   DB photo: '{$user->photo}'\n";
        echo "   Photo URL: '{$user->photo_url}'\n\n";
    }
}

echo "=== END DEBUG ===\n";
