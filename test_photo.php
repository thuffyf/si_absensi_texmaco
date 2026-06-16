<?php
/**
 * Test Photo URL - Access via browser
 * URL: https://sitexa.my.id/test_photo.php
 */

// Load Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$request = Illuminate\Http\Request::capture();
$kernel->bootstrap();

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Photo URLs</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        .section { background: white; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f9f9f9; font-weight: bold; }
        img { max-width: 100px; max-height: 100px; border: 1px solid #ddd; border-radius: 4px; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
    </style>
</head>
<body>
    <h1>🔍 Photo URL Debug</h1>
    
    <div class="section">
        <h2>1. Environment</h2>
        <table>
            <tr>
                <th>Variable</th>
                <th>Value</th>
            </tr>
            <tr>
                <td>APP_URL</td>
                <td><code><?= env('APP_URL') ?></code></td>
            </tr>
            <tr>
                <td>STORAGE_PUBLIC_PATH</td>
                <td><code><?= env('STORAGE_PUBLIC_PATH') ?></code></td>
            </tr>
            <tr>
                <td>Config app.url</td>
                <td><code><?= config('app.url') ?></code></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>2. PublicStorageUrl Tests</h2>
        <table>
            <tr>
                <th>Input Path</th>
                <th>Generated URL</th>
            </tr>
            <?php
            $testPaths = [
                'photos/test.jpg',
                'storage/photos/test.jpg',
                'storage_public/photos/test.jpg',
                '/photos/test.jpg',
            ];
            foreach ($testPaths as $path) {
                $url = \App\Support\PublicStorageUrl::storageUrl($path);
                echo "<tr><td><code>{$path}</code></td><td><code>{$url}</code></td></tr>";
            }
            ?>
        </table>
    </div>

    <div class="section">
        <h2>3. Students with Photos</h2>
        <?php
        $students = \App\Models\Student::whereNotNull('photo_path')
            ->where('photo_path', '!=', '')
            ->limit(10)
            ->get(['nis', 'name', 'email', 'photo_path']);
        
        if ($students->isEmpty()) {
            echo '<p class="warning">⚠️ No students with photos found</p>';
        } else {
            echo '<table>';
            echo '<tr><th>Student</th><th>DB photo_path</th><th>Has User?</th><th>User photo_url</th><th>Preview</th><th>File Exists?</th></tr>';
            
            foreach ($students as $student) {
                $user = \App\Models\User::where('email', $student->email)->first();
                
                echo '<tr>';
                echo '<td>' . htmlspecialchars($student->name) . '<br><small>' . $student->nis . '</small></td>';
                echo '<td><code>' . htmlspecialchars($student->photo_path) . '</code></td>';
                
                if ($user) {
                    echo '<td class="success">✅ Yes</td>';
                    $photoUrl = $user->photo_url;
                    $isUiAvatars = str_contains($photoUrl, 'ui-avatars.com');
                    
                    if ($isUiAvatars) {
                        echo '<td class="warning">Fallback UI Avatars<br><small><code>' . htmlspecialchars(substr($photoUrl, 0, 60)) . '...</code></small></td>';
                        echo '<td><img src="' . htmlspecialchars($photoUrl) . '" alt="fallback"></td>';
                    } else {
                        echo '<td><code>' . htmlspecialchars($photoUrl) . '</code></td>';
                        echo '<td><img src="' . htmlspecialchars($photoUrl) . '" alt="photo" onerror="this.parentElement.innerHTML=\'❌ Failed\'"></td>';
                    }
                    
                    // Check file exists
                    $normalizedPath = preg_replace('#^(storage_public|storage|public)/#', '', $student->photo_path);
                    $fullPath = '/home/sitexamy/public_html/storage_public/' . $normalizedPath;
                    $fileExists = file_exists($fullPath);
                    
                    if ($fileExists) {
                        $fileSize = filesize($fullPath);
                        echo '<td class="success">✅ Yes<br><small>' . number_format($fileSize / 1024, 2) . ' KB</small></td>';
                    } else {
                        echo '<td class="error">❌ No<br><small>' . htmlspecialchars($fullPath) . '</small></td>';
                    }
                } else {
                    echo '<td class="error">❌ No</td>';
                    echo '<td colspan="3" class="error">User record not found</td>';
                }
                
                echo '</tr>';
            }
            
            echo '</table>';
        }
        ?>
    </div>

    <div class="section">
        <h2>4. Test Direct URL Access</h2>
        <p>Try accessing a photo directly:</p>
        <?php
        $sampleStudent = \App\Models\Student::whereNotNull('photo_path')
            ->where('photo_path', '!=', '')
            ->first();
        
        if ($sampleStudent) {
            $normalizedPath = preg_replace('#^(storage_public|storage|public)/#', '', $sampleStudent->photo_path);
            $directUrl = 'https://sitexa.my.id/storage_public/' . $normalizedPath;
            echo '<p>Sample URL: <a href="' . htmlspecialchars($directUrl) . '" target="_blank"><code>' . htmlspecialchars($directUrl) . '</code></a></p>';
            echo '<p>Preview:</p>';
            echo '<img src="' . htmlspecialchars($directUrl) . '" alt="Direct photo" style="max-width: 200px; max-height: 200px;" onerror="this.parentElement.innerHTML+=\'<p class=error>❌ Failed to load image. Check if file exists and URL is accessible.</p>\'">';
        }
        ?>
    </div>

    <div class="section">
        <h2>5. Storage Directory Check</h2>
        <?php
        $storagePath = '/home/sitexamy/public_html/storage_public';
        echo '<table>';
        echo '<tr><th>Check</th><th>Result</th></tr>';
        
        // Check directory exists
        echo '<tr><td>Directory exists</td><td>';
        if (is_dir($storagePath)) {
            echo '<span class="success">✅ Yes</span>';
        } else {
            echo '<span class="error">❌ No</span>';
        }
        echo '</td></tr>';
        
        // Check readable
        echo '<tr><td>Readable</td><td>';
        if (is_readable($storagePath)) {
            echo '<span class="success">✅ Yes</span>';
        } else {
            echo '<span class="error">❌ No</span>';
        }
        echo '</td></tr>';
        
        // Check photos subdirectory
        $photosPath = $storagePath . '/photos';
        echo '<tr><td>photos/ subdirectory</td><td>';
        if (is_dir($photosPath)) {
            $photoCount = count(glob($photosPath . '/*'));
            echo '<span class="success">✅ Exists (' . $photoCount . ' files)</span>';
        } else {
            echo '<span class="error">❌ Not found</span>';
        }
        echo '</td></tr>';
        
        // List permissions
        if (is_dir($storagePath)) {
            $perms = substr(sprintf('%o', fileperms($storagePath)), -4);
            echo '<tr><td>Permissions</td><td><code>' . $perms . '</code></td></tr>';
        }
        
        echo '</table>';
        ?>
    </div>

</body>
</html>
