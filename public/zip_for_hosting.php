<?php
// Run this via terminal: php public/zip_for_hosting.php
ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');

echo "Starting zip process...\n";

$rootPath = realpath(__DIR__ . '/../');
$zipFileName = 'final_thriwex_host.zip';
$zipFilePath = $rootPath . '/' . $zipFileName;

if (!class_exists('ZipArchive')) {
    die("Error: ZipArchive class is not enabled in your PHP installation.\n");
}

$zip = new ZipArchive();
if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
    die("Failed to create ZIP file.\n");
}

$allowedFolders = ['app', 'bootstrap', 'config', 'database', 'public', 'resources', 'routes', 'storage', 'vendor'];
$allowedFiles = ['.env', 'artisan', 'composer.json', 'composer.lock', 'server.php'];

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);

$count = 0;
foreach ($iterator as $file) {
    $filePath = $file->getRealPath();
    $relativePath = substr($filePath, strlen($rootPath) + 1);
    $relativePath = str_replace('\\', '/', $relativePath);

    if (strpos($relativePath, 'public/' . $zipFileName) === 0) {
        continue;
    }

    $include = false;
    
    foreach ($allowedFolders as $folder) {
        if (strpos($relativePath, $folder . '/') === 0 || $relativePath === $folder) {
            if (strpos($relativePath, 'node_modules') !== false || strpos($relativePath, 'admin-panel') !== false) {
                $include = false;
                break;
            }
            $include = true;
            break;
        }
    }

    if (!$include && in_array($relativePath, $allowedFiles)) {
        $include = true;
    }

    if ($include) {
        if ($file->isDir()) {
            $zip->addEmptyDir($relativePath);
        } else {
            $zip->addFile($filePath, $relativePath);
            $count++;
            if ($count % 500 == 0) {
                echo "Added $count files...\n";
            }
        }
    }
}

$zip->close();
echo "Done! Total $count files added.\n";
echo "Your zip file is ready at: $zipFilePath\n";
