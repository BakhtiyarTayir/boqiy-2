<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FreeProduct;
use App\Models\ProductType;
use Illuminate\Http\Request;
use App\Models\Marketplace;
use App\Models\Media_files;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Currency;
use Illuminate\Support\Facades\DB as DataBase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Session;
use DB;
use App\Models\FileUploader;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class MarketplaceProductController extends Controller
{
    // Define constants for file paths
    const THUMBNAIL_PATH = 'public/marketplace/thumbnail';
    const COVERPHOTO_PATH = 'public/marketplace/coverphoto';
    
    /**
     * Display a listing of products
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    $products = ProductType::all()->where('is_deleted', 0);

        // Add image URL to each product

        foreach ($products as $product) {
            // dd($product);
            if ($product->image) {
                $product->image_url = asset('storage/marketplace/thumbnail/' . $product->image);
            } else {
                $product->image_url = asset('storage/marketplace/thumbnail/default/default.jpg');
            }
        }
            
        $page_data = [
            'products' => $products,
            'page_name' => 'marketplace_products',
            'page_title' => get_phrase('Marketplace Products'),
            'view_path' => 'marketplace/products'
        ];
        
        return view('backend.index', $page_data);
    }
    
    /**
     * Show the form for creating a new product
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_data = [
            'users' => User::all(),
            'categories' => Category::all(),
            'brands' => Brand::all(),
            'currencies' => Currency::all(),
            'page_name' => 'marketplace_product_create',
            'page_title' => get_phrase('Create Product'),
            'view_path' => 'marketplace/product_create'
        ];
        
        return view('backend.index', $page_data);
    }
    
    /**
     * Store a newly created product
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		// @todo Asilbek change
	    $rules = array(
		    'name' => 'required|max:255',
		    'price_for_sponsor' => 'required',
		    'price_for_every_one' => 'required',
		    'text_for_sponsor' => 'required',
		    'text_for_every_one' => 'required',
	    );
	
	    $validator = Validator::make($request->all(), $rules);
	
	    if ($validator->fails()) {
		    return redirect()->back()->withErrors($validator)->withInput();
	    }
	
	    $image = $request->file('image');
	
	    $productType = ProductType::create([
		    'name' => $request->name,
		    'price_for_sponsor' => $request->price_for_sponsor,
		    'price_for_every_one' => $request->price_for_every_one,
		    'text_for_sponsor' => $request->text_for_sponsor,
		    'text_for_every_one' => $request->text_for_every_one,
		    'created_date' => now(),
	    ]);
	
	    $file_path = null;
	
	    if (!empty($image)) {
		    $file_path = $this->saveProductCommentFile($image, $productType->id);
	    }
	
	    if ($file_path) {
		    $productType->file_path = $file_path;
		    $productType->save();
	    }
	
	    return redirect(route('admin.marketplace.products'));
    }
	
	private function saveProductCommentFile($imageFile, $productTypeId)
	{
		$uniqueId = Str::uuid()->toString();
		
		$fileName = time() . '.' . $imageFile->getClientOriginalExtension();
		
		$path = "product/type/{$productTypeId}/{$uniqueId}";
		
		if (!File::exists($path)) {
			File::makeDirectory($path, 0755, true);
		}
		
		$imageFile->move(public_path($path), $fileName);
		
		return $path . '/' . $fileName;
	}
	
	public function showProductTypeFile(Request $request)
	{
		$product_type = ProductType::where('id', $request->product_type_id)
			->first();
		
		if (!$product_type || !$product_type->file_path) {
			abort(404, 'Rasm topilmadi.');
		}
		
		$path = public_path($product_type->file_path);
		
		if (!File::exists($path)) {
			abort(404, 'Fayl mavjud emas.');
		}
		
		return Response::make(File::get($path), 200, [
			'Content-Type' => File::mimeType($path),
			'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
		]);
	}
    
    /**
     * Display the specified product
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Marketplace::with('getUser')->findOrFail($id);
        $product_images = Media_files::where('product_id', $id)->where('file_type', 'image')->get();
        
        $page_data = [
            'product' => $product,
            'product_images' => $product_images,
            'page_name' => 'marketplace_product_details',
            'page_title' => get_phrase('Product Details'),
            'view_path' => 'marketplace/product_details'
        ];
        
        return view('backend.index', $page_data);
    }
    
    /**
     * Show the form for editing the specified product
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		// @todo Asilbek change
	    $productType = ProductType::findOrFail($id);
	    $product_images = null; // Media_files::where('product_id', $id)->where('file_type', 'image')->get();
	
	    $page_data = [
		    'productType' => $productType,
		    'product_images' => $product_images,
		    'users' => User::all(),
		    'categories' => Category::all(),
		    'brands' => Brand::all(),
		    'currencies' => Currency::all(),
		    'page_name' => 'marketplace_product_edit',
		    'page_title' => get_phrase('Edit Product'),
		    'view_path' => 'marketplace/product_create'
	    ];
	
	    return view('backend.index', $page_data);
    }
    
    /**
     * Update the specified product
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
	    $rules = array(
		    'name' => 'required|max:255',
		    'price_for_sponsor' => 'required',
		    'price_for_every_one' => 'required',
		    'text_for_sponsor' => 'required',
		    'text_for_every_one' => 'required',
	    );
	
	    $validator = Validator::make($request->all(), $rules);
	    if ($validator->fails()) {
		    return redirect()->back()->withErrors($validator)->withInput();
	    }
	
	    $productType = ProductType::findOrFail($id);
	    $productType->name = $request->name;
	    $productType->price_for_sponsor = $request->price_for_sponsor;
	    $productType->price_for_every_one = $request->price_for_every_one;
	    $productType->text_for_sponsor = $request->text_for_sponsor;
	    $productType->text_for_every_one = $request->text_for_every_one;
	    $productType->save();
	
	    if (0 && $request->hasFile('multiple_files')){
		    // Delete previous images if replace_images is checked
		    if($request->has('replace_images') && $request->replace_images == 1) {
			    $previousfiles = Media_files::where('product_id', $id)->get();
			    foreach($previousfiles as $prevfile){
				    $media = Media_files::find($prevfile->id);
				    $imagename = $media->file_name;
				    $done = $media->delete();
				    if ($done) {
					    removeFile('marketplace', $imagename);
				    }
			    }
		    }
		
		    $files = $request->file('multiple_files');
		    foreach ($files as $key => $media_file) {
			    // Используем FileUploader для сохранения изображений
			    $file_name = FileUploader::upload($media_file, 'public/storage/marketplace/thumbnail', 315);
			    FileUploader::upload($media_file, 'public/storage/marketplace/coverphoto/'.$file_name, 600);
			
			    $file_type = 'image';
			
			    $media_file_data = array(
				    'user_id' => $request->user_id,
				    'product_id' => $id,
				    'file_name' => $file_name,
				    'file_type' => $file_type
			    );
			    $media_file_data['created_at'] = time();
			    $media_file_data['updated_at'] = $media_file_data['created_at'];
			    Media_files::create($media_file_data);
			
			    if($key == 0 && ($request->has('replace_images') && $request->replace_images == 1)) {
				    $marketplace->image = $file_name;
				    $marketplace->save();
			    }
		    }
	    }
	
	    //    Session::flash('success_message', get_phrase('Product updated successfully'));
	
	    return redirect()->route('admin.marketplace.products');
    }
    
    /**
     * Remove the specified product
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
	    $productType = ProductType::findOrFail($id);
	
	    // Delete associated media files
	
	    if ($productType) {
		    $productType->is_deleted = 1;
		    $productType->save();
	    }
	
	    return redirect()->route('admin.marketplace.products');
    }
	
	public function runDeadline()
	{
		$activeFreeProducts = \Illuminate\Support\Facades\DB::table('free_products')
			->where('is_sold', 1)
			->where('is_active', 1)
			->get();
		
		foreach ($activeFreeProducts as $item) {
			$deliveredTimestamp = strtotime($item->delivered_date);
			$hoursPassed = (time() - $deliveredTimestamp) / 3600;
			
			if ($hoursPassed > $item->deadline_hour) {
				DB::table('free_products')
					->where('id', $item->id)
					->update(['is_active' => 0]);
			}
		}
		
		return redirect(route('admin.marketplace.free.products'));
	}
	
	public function indexFreeProduct()
	{
		$freeProducts = \Illuminate\Support\Facades\DB::table('free_products')
			->join('product_types as pt', 'pt.id', '=', 'free_products.product_type_id')
			->join('users as sponsors', 'sponsors.id', '=', 'free_products.sponsor_id')
			->leftJoin('users as receivers', 'receivers.id', '=', 'free_products.receiver_id')
			->select(
				'free_products.*',
				'pt.name as product_name',
				'pt.file_path as file_path',
				'sponsors.name as sponsor_name',
				'receivers.name as receiver_name'
			)
			->get();
		
		$page_data = [
			'freeProducts' => $freeProducts,
			'page_name' => 'marketplace_products',
			'page_title' => get_phrase('Marketplace Products'),
			'view_path' => 'marketplace/freeProducts'
		];
		
		return view('backend.index', $page_data);
	}
	
	public function getUnshippedOrders()
	{
		$freeProducts = \Illuminate\Support\Facades\DB::table('free_products')
			->join('product_types as pt', 'pt.id', '=', 'free_products.product_type_id')
			->join('users as sponsors', 'sponsors.id', '=', 'free_products.sponsor_id')
			->join('users as receivers', 'receivers.id', '=', 'free_products.receiver_id')
			->select(
				'free_products.*',
				'pt.name as product_name',
				'pt.file_path as file_path',
				'sponsors.name as sponsor_name',
				'receivers.name as receiver_name'
			)
			->where('free_products.is_sold', 1)
			->where('free_products.is_active', 1)
			->where('free_products.is_ordered', 0)
			->get();

		$page_data = [
			'freeProducts' => $freeProducts,
			'page_name' => 'marketplace_products',
			'page_title' => get_phrase('Marketplace Products'),
			'view_path' => 'marketplace/freeProducts'
		];
		
		return view('backend.index', $page_data);
	}
	
	public function createFreeProduct()
	{
		$page_data = [
			'users' => User::all(),
			'product_types' => ProductType::all()->where('is_deleted', 0),
			'page_name' => 'marketplace_product_create',
			'page_title' => get_phrase('Create Product'),
			'view_path' => 'marketplace/free_product_create'
		];
		
		return view('backend.index', $page_data);
	}
	
	public function storeFreeProduct(Request $request)
	{
		$rules = array(
			'product_type_id' => 'required',
			'sponsor_id' => 'required',
		);
		
		$validator = Validator::make($request->all(), $rules);
		
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}
		
		$isSold = false;
		$is_active = false;
		
		if (!empty($request->receiver_id)) {
			$isSold = true;
		}
		
		if (!empty($request->is_active)) {
			$is_active = true;
		}
		

		FreeProduct::create([
			'product_type_id' => $request->product_type_id,
			'sponsor_id' => $request->sponsor_id,
			'is_payment_sponsor' => 1,
			'receiver_id' => $request->receiver_id ?? null,
			'is_sold' => (int) $isSold,
			'is_active' => (int) $is_active,
			'deadline_hour' => $request->deadline_hour ?? 36,
			'created_date' => now(),
		]);
		
		return redirect(route('admin.marketplace.free.products'));
	}
	
	public function editFreeProduct($id)
	{
		$freeProduct = FreeProduct::findOrFail($id);
		$product_images = null; // Media_files::where('product_id', $id)->where('file_type', 'image')->get();
		
		$freeProduct = DataBase::table('free_products')
			->join('users as sponsors', 'sponsors.id', '=', 'free_products.sponsor_id')
			->leftJoin('users as receivers', 'receivers.id', '=', 'free_products.receiver_id')
			->select(
				'free_products.*',
				'sponsors.address as sponsorAddress',
				'sponsors.regionId as sponsorDistrictId',
				'sponsors.districtId as sponsorRegionId',
				'receivers.address as receiverAddress',
				'receivers.regionId as receiverRegionId',
				'receivers.districtId as receiverDistrictId'
			)
			->where('free_products.id', $id)
			->first();
		
		$sponsorFullAddress = '';
		$receiverFullAddress = '';
		
		$sponsorAddress = $freeProduct->sponsorAddress;
		$sponsorRegion = $freeProduct->sponsorRegionId ? User::REGIONS[$freeProduct->sponsorRegionId] : '';
		$sponsorDistrict = $freeProduct->sponsorRegionId && $freeProduct->sponsorDistrictId
			? User::DISTRICTS[$freeProduct->sponsorRegionId][$freeProduct->sponsorDistrictId]
			: '';
		
		$receiverAddress = $freeProduct->receiverAddress;
		$receiverRegion = $freeProduct->receiverRegionId ? User::REGIONS[$freeProduct->receiverRegionId] : '';
		$receiverDistrict = $freeProduct->receiverRegionId && $freeProduct->receiverDistrictId
			? User::DISTRICTS[$freeProduct->receiverRegionId][$freeProduct->receiverDistrictId]
			: '';
		
		if ($sponsorRegion) {
			$sponsorFullAddress = sprintf('%s, %s tumani, %s', $sponsorRegion, $sponsorDistrict, $sponsorAddress);
		}
		
		if ($receiverRegion) {
			$receiverFullAddress = sprintf('%s, %s tumani, %s', $receiverRegion, $receiverDistrict, $receiverAddress);
		}
		
		$page_data = [
			'freeProduct' => $freeProduct,
			'users' => User::all(),
			'product_types' => ProductType::all()->where('is_deleted', 0),
			'page_name' => 'marketplace_product_create',
			'page_title' => get_phrase('Create Product'),
			'view_path' => 'marketplace/free_product_create',
			'sponsorFullAddress' => $sponsorFullAddress,
			'receiverFullAddress' => $receiverFullAddress,
		];
		
		return view('backend.index', $page_data);
	}
	
	public function updateFreeProduct(Request $request, $id)
	{
		$rules = array(
			'product_type_id' => 'required',
			'sponsor_id' => 'required',
		);
		
		$validator = Validator::make($request->all(), $rules);
		
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}
		
		$isSold = false;
		$is_active = false;
		$is_ordered = false;
		
		if (!empty($request->receiver_id)) {
			$isSold = true;
		}
		
		if (!empty($request->is_active)) {
			$is_active = true;
		}
		
		if (!empty($request->is_ordered)) {
			$is_ordered = true;
		}

		$freeProduct = FreeProduct::findOrFail($id);
		
		$freeProduct->receiver_id = $request->receiver_id;
		$freeProduct->delivered_date = now();
		$freeProduct->is_sold = (int) $isSold;
		$freeProduct->is_ordered = (int) $is_ordered;
		$freeProduct->is_active = (int) $is_active;
		$freeProduct->deadline_hour = $request->deadline_hour;

		$freeProduct->save();
		
		if (0 && $request->hasFile('multiple_files')){
			// Delete previous images if replace_images is checked
			if($request->has('replace_images') && $request->replace_images == 1) {
				$previousfiles = Media_files::where('product_id', $id)->get();
				foreach($previousfiles as $prevfile){
					$media = Media_files::find($prevfile->id);
					$imagename = $media->file_name;
					$done = $media->delete();
					if ($done) {
						removeFile('marketplace', $imagename);
					}
				}
			}
			
			$files = $request->file('multiple_files');
			foreach ($files as $key => $media_file) {
				// Используем FileUploader для сохранения изображений
				$file_name = FileUploader::upload($media_file, 'public/storage/marketplace/thumbnail', 315);
				FileUploader::upload($media_file, 'public/storage/marketplace/coverphoto/'.$file_name, 600);
				
				$file_type = 'image';
				
				$media_file_data = array(
					'user_id' => $request->user_id,
					'product_id' => $id,
					'file_name' => $file_name,
					'file_type' => $file_type
				);
				$media_file_data['created_at'] = time();
				$media_file_data['updated_at'] = $media_file_data['created_at'];
				Media_files::create($media_file_data);
				
				if($key == 0 && ($request->has('replace_images') && $request->replace_images == 1)) {
					$marketplace->image = $file_name;
					$marketplace->save();
				}
			}
		}
		
		//    Session::flash('success_message', get_phrase('Product updated successfully'));
		
		return redirect()->route('admin.marketplace.free.products');
	}
    
    /**
     * Display featured products
     *
     * @return \Illuminate\Http\Response
     */
    public function featuredProducts()
    {
        $products = Marketplace::with(['getUser', 'getCurrency'])
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        // Add image URL to each product
        foreach ($products as $product) {
            if ($product->image) {
                $product->image_url = asset('storage/marketplace/thumbnail/' . $product->image);
            } else {
                $product->image_url = asset('assets/images/placeholder.jpg');
            }
        }
            
        $page_data = [
            'products' => $products,
            'page_name' => 'marketplace_featured_products',
            'page_title' => get_phrase('Featured Products'),
            'view_path' => 'marketplace/products'
        ];
        
        return view('backend.index', $page_data);
    }
    
    /**
     * Display out of stock products
     *
     * @return \Illuminate\Http\Response
     */
    public function outOfStockProducts()
    {
        $products = Marketplace::with(['getUser', 'getCurrency'])
            ->where('status', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        // Add image URL to each product
        foreach ($products as $product) {
            if ($product->image) {
                $product->image_url = asset('storage/marketplace/thumbnail/' . $product->image);
            } else {
                $product->image_url = asset('assets/images/placeholder.jpg');
            }
        }
            
        $page_data = [
            'products' => $products,
            'page_name' => 'marketplace_out_of_stock_products',
            'page_title' => get_phrase('Out of Stock Products'),
            'view_path' => 'marketplace/products'
        ];
        
        return view('backend.index', $page_data);
    }
    
    /**
     * Remove an image from a product
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function removeImage($id)
    {
        $media = Media_files::findOrFail($id);
        $product_id = $media->product_id;
        $imagename = $media->file_name;
        
        $product = Marketplace::find($product_id);
        if($product->image == $imagename) {
            // If this is the main image, try to find another image to set as main
            $another_media = Media_files::where('product_id', $product_id)
                ->where('id', '!=', $id)
                ->where('file_type', 'image')
                ->first();
                
            if($another_media) {
                $product->image = $another_media->file_name;
                $product->save();
            } else {
                $product->image = null;
                $product->save();
            }
        }
        
        $done = $media->delete();
        if ($done) {
            removeFile('marketplace', $imagename);
            
            Session::flash('success_message', get_phrase('Image removed successfully'));
        } else {
            Session::flash('error_message', get_phrase('Something went wrong'));
        }
        
        return redirect()->back();
    }
} 