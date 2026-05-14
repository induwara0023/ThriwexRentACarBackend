<?php
$target = __DIR__ . '/../storage/app/public';
$link = __DIR__ . '/storage';

function deleteDirectory($dir) {
    if (!file_exists($dir)) return true;
    if (!is_dir($dir)) return unlink($dir);
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') continue;
        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) return false;
    }
    return rmdir($dir);
}

if (file_exists($link)) {
    echo "Something exists at $link. Deleting recursively...<br>";
    if (deleteDirectory($link)) {
        echo "Deleted successfully.<br>";
    } else {
        echo "Delete failed.<br>";
    }
}

echo "Creating link from $target to $link...<br>";
// Try PHP symlink first
if (symlink($target, $link)) {
    echo "Success via symlink()!";
} else {
    echo "Failed via symlink(). Trying mklink /j...<br>";
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        exec("mklink /j \"$link\" \"$target\"", $output, $return_var);
        echo "Return var: $return_var<br>";
        print_r($output);
    }
}
