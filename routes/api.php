<?php

use App\Models\Category;
use App\Models\Customer;
use App\Models\Part;
use App\Models\Service;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/categories', function (Request $request) {
    $rows = $request->validate(['*.name' => 'required|string|max:255']);
    return response()->json(collect($rows)->map(fn($r) => Category::create($r)), 201);
});

Route::post('/customers', function (Request $request) {
    $rows = $request->validate([
        '*.name' => 'required|string|max:255',
        '*.phone' => 'nullable|string|max:20',
        '*.address' => 'nullable|string',
    ]);
    return response()->json(collect($rows)->map(fn($r) => Customer::create($r)), 201);
});

Route::post('/services', function (Request $request) {
    $rows = $request->validate([
        '*.name' => 'required|string|max:255',
        '*.price' => 'required|numeric|min:0',
        '*.duration_minutes' => 'nullable|integer|min:0',
    ]);
    return response()->json(collect($rows)->map(fn($r) => Service::create($r)), 201);
});

Route::post('/parts', function (Request $request) {
    $rows = $request->validate([
        '*.category_id' => 'required|exists:categories,id',
        '*.sku' => 'required|string|max:255|unique:parts,sku',
        '*.name' => 'required|string|max:255',
        '*.buy_price' => 'required|numeric|min:0',
        '*.sell_price' => 'required|numeric|min:0',
        '*.stock' => 'required|integer|min:0',
        '*.min_stock' => 'nullable|integer|min:0',
    ]);
    return response()->json(collect($rows)->map(fn($r) => Part::create($r)), 201);
});

Route::post('/vehicles', function (Request $request) {
    $rows = $request->validate([
        '*.customer_id' => 'required|exists:customers,id',
        '*.plate_number' => 'required|string|max:20|unique:vehicles,plate_number',
        '*.brand' => 'required|string|max:255',
        '*.model' => 'nullable|string|max:255',
        '*.type' => 'required|in:Motor,Mobil',
        '*.color' => 'nullable|string|max:50',
        '*.year' => 'nullable|integer|min:1900|max:2100',
    ]);
    return response()->json(collect($rows)->map(fn($r) => Vehicle::create($r)), 201);
});

// Bonus: cek data cepat lewat GET
Route::get('/customers', fn() => Customer::with('vehicles')->get());
Route::get('/parts', fn() => Part::with('category')->get());
