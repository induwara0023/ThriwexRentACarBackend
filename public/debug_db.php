<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\Vehicle;
$vehicles = Vehicle::all();
echo "<pre>";
foreach ($vehicles as $v) {
    echo "ID: {$v->id}, Model: {$v->model}, Image Path: '{$v->image_path}'\n";
}
echo "</pre>";
