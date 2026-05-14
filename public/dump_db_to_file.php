<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\Vehicle;
$vehicles = Vehicle::all();
$output = "";
foreach ($vehicles as $v) {
    $output .= "ID: {$v->id}, Model: {$v->model}, Image Path: '{$v->image_path}'\n";
}
file_put_contents(__DIR__ . '/db_dump.txt', $output);
echo "Dumped to db_dump.txt";
