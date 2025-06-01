<?php

namespace App\Http\Controllers;

use App\Models\Marketplace;
use App\Models\ProductSponsor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductSponsorController extends Controller
{
	// @todo Bahtiyor aka bilan birga o'zgartirish kerak
    public function sponsorForm($productId)
    {
        $product = Marketplace::findOrFail($productId);

        $page_data = [
            'product' => $product,
            'view_path' => 'frontend.marketplace.sponsor_form'
        ];
        
        return view('frontend.index', $page_data);
    }

    public function store(Request $request, $productId)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'instagram' => 'nullable|url',
            'telegram' => 'nullable|url',
            'youtube' => 'nullable|url',
            'twitter' => 'nullable|url',
            'facebook' => 'nullable|url',
            'sponsor_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'views_purchased' => 'nullable|integer|min:1000'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $product = Marketplace::findOrFail($productId);
        
        $sponsorData = $request->except('sponsor_image');
        
        if ($request->hasFile('sponsor_image')) {
            $image = $request->file('sponsor_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/sponsor_images', $imageName);
            $sponsorData['sponsor_image'] = $imageName;
        }

        $sponsor = ProductSponsor::create([
            'product_id' => $productId,
            'company_name' => $sponsorData['company_name'],
            'phone_number' => $sponsorData['phone_number'],
            'instagram' => $sponsorData['instagram'],
            'telegram' => $sponsorData['telegram'],
            'youtube' => $sponsorData['youtube'],
            'twitter' => $sponsorData['twitter'],
            'facebook' => $sponsorData['facebook'],
            'sponsor_image' => $sponsorData['sponsor_image'] ?? null,
            'views_purchased' => $sponsorData['views_purchased'] ?? 1000
        ]);

        // Здесь будет логика оплаты через Payme
        // Пока просто меняем категорию товара
        $product->category = 'free product';
        $product->save();

        $product = Marketplace::findOrFail($productId);


        
        $page_data = [
            'productId' => $productId,
            'view_path' => 'frontend.marketplace.single_product'
        ];
        


        return redirect()->route('allproducts', $page_data)
            ->with('success', 'Product sponsored successfully!');

    }
} 