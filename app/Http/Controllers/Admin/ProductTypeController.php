<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductType;
use Illuminate\Http\Request;

class ProductTypeController
{
	// Hammasini olish
	public function index()
	{
		$types = ProductType::where('is_deleted', 0)->get();
		return response()->json($types);
	}
	
	// Yangi product_type qo‘shish
	public function store(Request $request)
	{
		$validated = $request->validate([
			'price_for_sponsor' => 'required|numeric',
			'price_for_every_one' => 'required|numeric',
			'text_for_sponsor' => 'nullable|string',
			'text_for_every_one' => 'nullable|string',
			'file_path' => 'nullable|string',
		]);

		$type = ProductType::create($validated);

		return response()->json(['message' => 'Yaratildi', 'data' => $type]);
	}

	// Bitta product_type ko‘rsatish
	public function edit(Request $request)
	{
		$type = ProductType::findOrFail($request->id);

		return view('backend.admin.productType.create');
	}
	
	// Tahrirlash
	public function update(Request $request)
	{
		$type = ProductType::findOrFail($request->id);

		$validated = $request->validate([
			'price_for_sponsor' => 'nullable|numeric',
			'price_for_every_one' => 'nullable|numeric',
			'text_for_sponsor' => 'nullable|string',
			'text_for_every_one' => 'nullable|string',
			'file_path' => 'nullable|string',
		]);

		$type->update($validated);

		return redirect(route('productTypeList'));
	}
	
	// Soft delete (faqat is_deleted = 1 bo‘ladi)
	public function delete(Request $request)
	{
		$type = ProductType::findOrFail($request->id);
		$type->update(['is_deleted' => 1]);

		return redirect(route('productTypeList'));
	}
}