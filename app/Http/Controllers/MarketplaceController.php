<?php

namespace App\Http\Controllers;

use App\Models\FreeProduct;
use App\Models\Marketplace;
use App\Models\Media_files;
use App\Models\ProductComment;
use App\Models\ProductType;
use App\Models\SavedProduct;
use App\Models\FileUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as DataBase;
use Image;
use Session;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Models\Wallet;
use App\Models\MarketplaceOrder;

class MarketplaceController extends Controller
{
	// @todo Asilbek change
	public function allproducts(){
		$freeProducts = DataBase::table('free_products')
			->join('product_types as pt', 'pt.id', '=', 'free_products.product_type_id')
			->join('users as sponsors', 'sponsors.id', '=', 'free_products.sponsor_id')
			->leftJoin('users as receivers', 'receivers.id', '=', 'free_products.receiver_id')
			->select(
				'free_products.*',
				'pt.id as product_type_id',
				'pt.name as name',
				'pt.price_for_every_one as price_for_every_one',
				'pt.file_path as file_path',
				'sponsors.name as sponsor_name',
				'sponsors.is_anonymous_sponsor as is_anonymous_sponsor',
				'receivers.name as receiver_name'
			)
			->where('free_products.is_payment_sponsor', 1)
			->where('free_products.is_active', 1)
			->get();
		
		$page_data['products'] = $freeProducts;
		
		
		$page_data['view_path'] = 'frontend.marketplace.products';
		
		return view('frontend.index', $page_data);
	}
	
    public function productBuy(Request $request)
    {
      $productId = $request->id;
      $user = auth()->user();
      
      if ($productId && !empty($user)) {
        $product = FreeProduct::where('id', $productId)->first();
        
        $freeProduct = DataBase::table('free_products')
          ->join('product_types as pt', 'pt.id', '=', 'free_products.product_type_id')
          ->select(
            'free_products.*',
            'pt.id as product_type_id',
            'pt.name as name',
            'pt.price_for_every_one as price_for_every_one',
          )
          ->where('free_products.id', $productId)
          ->where('free_products.is_sold', 0)
          ->where('free_products.is_active', 1)
          ->first();
        
        $walletBalance = $user->wallet;
        $wallet = Wallet::where('id', $walletBalance->id)->first();
        $walletNewBalance = $wallet->balance - $freeProduct->price_for_every_one;
        
        if ($walletNewBalance >= 0) {
          $wallet->balance = $walletNewBalance;
          $wallet->save();
          
          $product->is_sold = 1;
          $product->receiver_id = $user->id;
          $product->save();
        }
        
        return redirect(route('myProducts'));
      }
      
      return redirect(route('allproducts'));
    }
  
	
	public function myProducts()
	{
		$user = auth()->user();

		$myProducts = DataBase::table('free_products')
			->join('product_types as pt', 'pt.id', '=', 'free_products.product_type_id')
			->join('users as sponsors', 'sponsors.id', '=', 'free_products.sponsor_id')
			->join('users as receivers', 'receivers.id', '=', 'free_products.receiver_id')
			->select(
				'free_products.*',
				'pt.id as product_type_id',
				'pt.name as name',
				'pt.price_for_every_one as price_for_every_one',
				'pt.file_path as file_path',
				'sponsors.name as sponsor_name',
				'sponsors.is_anonymous_sponsor as is_anonymous_sponsor',
				'receivers.name as receiver_name'
			)
			->where('free_products.is_payment_sponsor', 1)
			->where('free_products.is_active', 1)
			->where('receivers.id', $user->id)
			->get();
		
		$page_data['myProducts'] = $myProducts;
		
		
		$page_data['view_path'] = 'frontend.marketplace.my-products';
		
		//dd($myProducts);
		
		return view('frontend.index', $page_data);
		
	}
	
	public function allProductsForSponsor()
	{
		$sponsorProducts = ProductType::query()
			->select('product_types.*')
			->where('product_types.is_deleted', 0)
			->get();
		
		$page_data['products'] = $sponsorProducts;
		
		$page_data['view_path'] = 'frontend.marketplace.sponsor_products';
		
		return view('frontend.index', $page_data);
	}
	
	public function sponsorProductView($id) {
		$freeProduct = ProductType::query()
			->select('product_types.*')
			->where('product_types.id', $id)
			->where('product_types.is_deleted', 0)
			->first();
		
		$user = auth()->user();
		
		// @todo shu urlga tovarga to'lov qilinadigan joyga o'tib ketishi kerak
		$sponsorUrl = '#';
		
		if (!$user->address || !$user->social_links) {
			$sponsorUrl = route('profileEdit', ['id' => $user->id, 'sponsor' => 1]);
		}

		if ($freeProduct) {
			$page_data['product'] = $freeProduct;
			$page_data['sponsorUrl'] = $sponsorUrl;
			$page_data['product_image'] = Media_files::where('product_id',$id)->where('file_type','image')->get();
			$page_data['view_path'] = 'frontend.marketplace.sponsor_product_item';
			
			return view('frontend.index', $page_data);
		} else {
			
			return redirect(route('allProductsForSponsor'));
			if (isset($_GET['shared'])){
				$page_data['post'] = '';
				
				return view('frontend.marketplace.custom_shared_view', $page_data);
			} else{
				
				return redirect()->back()->with('error_message', 'This product is not available');
			}
		}
	}

    public function userproduct(){
        $products = Marketplace::where('user_id',auth()->user()->id)->orderBy('id','DESC')->get();
        $page_data['products'] = $products;
        $page_data['view_path'] = 'frontend.marketplace.user_products';
        return view('frontend.index', $page_data);
    }


    public function store(Request $request){
        $rules = array(
            'title' => 'required|max:255',
            'price' => 'required',
            'location' => 'required',
            // 'category' => 'required',
            'condition' => 'required',
            // 'status' => 'required',
            // 'brand' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return json_encode(array('validationError' => $validator->getMessageBag()->toArray()));
        }

        $marketplace = new Marketplace();
        $marketplace->user_id = auth()->user()->id;
        $marketplace->title = $request->title;
        $marketplace->currency_id = $request->currency;
        $marketplace->price = $request->price;
        $marketplace->location = $request->location;
        $marketplace->category = $request->category;
        $marketplace->condition = $request->condition;
        $marketplace->brand = $request->brand;
        $marketplace->buy_link = $request->buy_link;
        $marketplace->status = $request->status;
        $marketplace->description = $request->description;
        $marketplace->save();
        $product_id = $marketplace->id;
        if ($product_id) {
            if(is_array($request->multiple_files) && $request->multiple_files[0] != null){
                //Data validation
                $rules = array('multiple_files' => 'mimes:jpeg,jpg,png,gif');
                $validator = Validator::make($request->multiple_files, $rules);
                if ($validator->fails()){
                     return json_encode(array('validationError' => $validator->getMessageBag()->toArray()));
                }
    
                foreach ($request->multiple_files as $key => $media_file) {
                        
                    $file_name = FileUploader::upload($media_file,'public/storage/marketplace/thumbnail', 315);
                    FileUploader::upload($media_file,'public/storage/marketplace/coverphoto/'.$file_name, 315);

                    $file_type = 'image';

                    $productupdate = Marketplace::find($product_id);
                    $media_file_data = array('user_id' => auth()->user()->id, 'product_id' => $product_id, 'file_name' => $file_name, 'file_type' => $file_type);
                    $media_file_data['created_at'] = time();
                    $media_file_data['updated_at'] = $media_file_data['created_at'];
                    Media_files::create($media_file_data);
                    if($key=='0'){
                        $productupdate = Marketplace::find($product_id);
                        $productupdate->image = $file_name;
                        $productupdate->save();
                    }
                }
            }
            Session::flash('success_message', get_phrase('Marketplace Product Added Successfully'));
            return json_encode(array('reload' => 1));
        }
    }

    public function update(Request $request,$id){
        $rules = array(
            'title' => 'required|max:255',
            'price' => 'required',
            'location' => 'required',
            // 'category' => 'required',
            'condition' => 'required',
            'status' => 'required',
            // 'brand' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return json_encode(array('validationError' => $validator->getMessageBag()->toArray()));
        }

        $marketplace = Marketplace::find($id);
        $marketplace->user_id = auth()->user()->id;
        $marketplace->title = $request->title;
        $marketplace->currency_id = $request->currency;
        $marketplace->price = $request->price;
        $marketplace->location = $request->location;
        $marketplace->category = $request->category;
        $marketplace->condition = $request->condition;
        $marketplace->brand = $request->brand;
        $marketplace->status = $request->status;
        $marketplace->description = $request->description;
        $marketplace->save();
        $product_id = $id;
        if ($product_id) {
            if(is_array($request->multiple_files) && $request->multiple_files[0] != null){
                //Data validation
                $rules = array('multiple_files' => 'mimes:jpeg,jpg,png,gif');
                $validator = Validator::make($request->multiple_files, $rules);
                if ($validator->fails()){
                     return json_encode(array('validationError' => $validator->getMessageBag()->toArray()));
                }

                if(isset($request->multiple_files)){
                     // this for deleting previous data file 
                     $previousfile = Media_files::where('product_id',$id)->get();
                     foreach($previousfile as $previousfile){
                         $market = Media_files::find($previousfile->id);
                         // store image name for delete file operation 
                         $imagename = $market->banner;
                         $done = $market->delete();
                         if ($done) {
                             // just put the file name and folder name nothing more :) 
                             removeFile('marketplace', $imagename);
                         }
                     }
                 // end code sec 
                }
    
                foreach ($request->multiple_files as $key => $media_file) {
                    $file_name = FileUploader::upload($media_file,'public/storage/marketplace/thumbnail', 315);
                    FileUploader::upload($media_file,'public/storage/marketplace/coverphoto/'.$file_name, 315);
                    $file_type = 'image';
    
                    $productupdate = Marketplace::find($product_id);
                    $media_file_data = array('user_id' => auth()->user()->id, 'product_id' => $product_id, 'file_name' => $file_name, 'file_type' => $file_type);
                    $media_file_data['created_at'] = time();
                    $media_file_data['updated_at'] = $media_file_data['created_at'];
                    Media_files::create($media_file_data);
                    if($key=='0'){
                        $productupdate = Marketplace::find($product_id);
                        $productupdate->image = $file_name;
                        $productupdate->save();
                    }
                }
            }
            Session::flash('success_message', get_phrase('Marketplace Product Updated Successfully'));
            return json_encode(array('reload' => 1));
        }
    }

    public function product_delete(){
        $response = array();
        $market = Marketplace::find($_GET['product_id']);
        // store image name for delete file operation 
        $imagename = $market->banner;

        $done = $market->delete();
        if ($done) {
            $response = array('alertMessage' => get_phrase('Product Deleted Successfully'), 'fadeOutElem' => "#product-" . $_GET['product_id']);
            // just put the file name and folder name nothing more :) 
            removeFile('marketplace', $imagename);
        }
        return json_encode($response);
    }

    public function load_product_by_scrolling(Request $request){
        $products =  Marketplace::orderBy('id', 'DESC')->skip($request->offset)->take(6)->get();

        $page_data['products'] = $products;
        return view('frontend.marketplace.product-single', $page_data);
    }
	
	public function single_product($id) {
		$freeProduct = DataBase::table('free_products')
			->join('product_types as pt', 'pt.id', '=', 'free_products.product_type_id')
			->join('users as sponsors', 'sponsors.id', '=', 'free_products.sponsor_id')
			->leftJoin('users as receivers', 'receivers.id', '=', 'free_products.receiver_id')
			->select(
				'free_products.*',
				'pt.id as product_type_id',
				'pt.name as name',
				'pt.price_for_every_one as price_for_every_one',
				'pt.text_for_every_one as text_for_every_one',
				'pt.file_path as file_path',
				'sponsors.name as sponsor_name',
				'sponsors.is_anonymous_sponsor as is_anonymous_sponsor',
				'sponsors.social_links as sponsor_social_links',
				'receivers.name as receiver_name'
			)
			->where('free_products.id', $id)
			->where('free_products.is_payment_sponsor', 1)
			->where('free_products.is_active', 1)
			->first();
		
		if ($freeProduct) {
			$page_data['related_product'] = DataBase::table('free_products')
				->join('product_types as pt', 'pt.id', '=', 'free_products.product_type_id')
				->join('users as sponsors', 'sponsors.id', '=', 'free_products.sponsor_id')
				->leftJoin('users as receivers', 'receivers.id', '=', 'free_products.receiver_id')
				->select(
					'free_products.*',
					'pt.name as name',
					'pt.id as product_type_id',
					'pt.price_for_every_one as price_for_every_one',
					'pt.text_for_every_one as text_for_every_one',
					'pt.file_path as file_path',
					'sponsors.name as sponsor_name',
					'receivers.name as receiver_name'
				)
				->where('free_products.id', '!=', $id)
				->where('free_products.product_type_id', $freeProduct->product_type_id)
				->where('free_products.is_payment_sponsor', 1)
				->where('free_products.is_active', 1)
				->limit(10)
				->get();
			
			$user = auth()->user();
			
			// @todo Asilbek harid qilish url bo'lishi kerak
			$redirectEditProfileUrl = null;
			
			if (!$user->address || !$user->social_links) {
				$redirectEditProfileUrl = route('profileEdit', ['id' => $user->id, 'coast' => 1]);
				$redirectEditProfileUrl .= '#fullAddress';
			}
			
			$page_data['product'] = $freeProduct;
			$page_data['redirectEditProfileUrl'] = $redirectEditProfileUrl;
		//	$page_data['product_image'] = Media_files::where('product_id',$id)->where('file_type','image')->get();
			$page_data['view_path'] = 'frontend.marketplace.single_product';
			$page_data['product_comments'] = ProductComment::query()
				->select([
					'product_comments.*',
					'users.name as user_name'
				])
				->join('users', 'users.id', '=', 'product_comments.user_id')
				->where('product_comments.product_id', $freeProduct->id)
				->where('product_comments.is_deleted', 0)
				->get();
			
			return view('frontend.index', $page_data);
		}
		
		return redirect(route('allproducts'));
	}

    // on key up product search 
    public function filter(){
        $search =  $_GET['search'];
        // $category =  $_GET['category'];
        $condition =  $_GET['condition'];
        $min =  $_GET['min'];
        $max =  $_GET['max'];
        // $brand =  $_GET['brand'];
        $location =  $_GET['location'];

        $query = Marketplace::where('status', 1);

        if(isset($search) && !empty($search)){
            $query->where(function ($query) use ($search){
                $query->where('title', 'like', '%'. $search .'%')
                ->orWhere('description', 'like', '%'. $search .'%');
            });
        }

        if(isset($condition) && !empty($condition)){
            $query->where('condition', $condition);
        }

        // if(isset($category) && !empty($category)){
        //     $query->where('category', $category);
        // }

        if(isset($min) && !empty($min)){
            $query->where('price', '>=', $min);
        }

        if(isset($max) && !empty($max)){
            $query->where('price', '<=', $max);
        }

        // if(isset($brand) && !empty($brand)){
        //     $query->where('brand', $brand);
        // }

        if(isset($location) && !empty($location)){
            $query->where('location', 'like', '%'.$location.'%');
        }

        // if(!empty($search) || !empty($location)){
        //     $query->where(function($query) use($search, $location){
        //         if(!empty($search)){
        //             $query->where(function ($query) use ($search){
        //                 $query->where('title', 'like', '%'. $search .'%')
        //                 ->orWhere('description', 'like', '%'. $search .'%');
        //             });
        //         }
        //         if(!empty($location)){
        //             $query->orWhere('location', 'like', '%'.$location.'%');
        //         }
        //     });
        // }

        // $query->where(function($query) use($min, $max){
        //     $query->where('price', '>=', $min)->where('price', '<=', $max);
        // });

        // if(isset($condition) && !empty($condition)){
        //     $query->where('condition', $condition);
        // }

        // if(isset($category) && !empty($category)){
        //     $query->where('category', $category);
        // }

        // if(isset($brand) && !empty($brand)){
        //     $query->where('brand', $brand);
        // }

        $page_data['products'] = $query->get();
        $page_data['view_path'] = 'frontend.marketplace.products';
        return view('frontend.index', $page_data); 

    }

    public function saved_product(){
        $page_data['saved_products'] = SavedProduct::all();
        $page_data['view_path'] = 'frontend.marketplace.saved_product';
        return view('frontend.index', $page_data);
    }

    public function save_for_later($id){
        $saveproduct = new SavedProduct();
        $saveproduct->user_id = auth()->user()->id;
        $saveproduct->product_id = $id;
        $saveproduct->save();

        Session::flash('success_message', get_phrase('Saved Successfully'));
        $response = array('reload' => 1);
        return json_encode($response);
    }


    public function unsave_for_later($id){
        $done = SavedProduct::where('product_id',$id)->where('user_id',auth()->user()->id)->delete();
        if($done){
            Session::flash('success_message', get_phrase('Unsaved Successfully'));
            $response = array('reload' => 1);
            return json_encode($response);
        }
    }

    public function single_product_ifrane($id){
        $product = Marketplace::find($id);
        $page_data['product'] = $product;
        $page_data['product_image'] = Media_files::where('product_id',$id)->where('file_type','image')->get();

        if($product){

            if(isset($_GET['shared'])){
                return view('frontend.marketplace.single_product_iframe', $page_data);
            }else{
                return redirect(route('single.product', $id));
            }
        }else{

            if(isset($_GET['shared'])){
                $page_data['post'] = '';
                return view('frontend.main_content.custom_shared_view', $page_data);
            }else{
                $page_data['post'] = '';
                $page_data['view_path'] = 'frontend.main_content.custom_shared_view';
                return view('frontend.index', $page_data);
            }
        }
    }

    /**
     * Show checkout page for a product
     * 
     * @param int $id Product ID
     * @return View
     */
    public function checkout($id)
    {
        $product = Marketplace::find($id);
        
        if (!$product) {
            Session::flash('error_message', get_phrase('Product not found'));
            return redirect()->route('allproducts');
        }
        
        // Don't allow buying your own product
        if ($product->user_id == auth()->user()->id) {
            Session::flash('error_message', get_phrase('You cannot buy your own product'));
            return redirect()->route('single.product', $id);
        }
        
        // Get user wallet
        $wallet = auth()->user()->wallet;
        
        // If wallet doesn't exist, create it
        if (!$wallet) {
            try {
                $wallet = Wallet::create([
                    'user_id' => auth()->user()->id,
                    'balance' => 0
                ]);
            } catch (\Exception $e) {
                // Log the error but continue
                \Log::error('Failed to create wallet: ' . $e->getMessage());
            }
        }
        
        $page_data = [
            'product' => $product,
            'wallet' => $wallet,
            'product_image' => Media_files::where('product_id', $id)
                ->where('file_type', 'image')
                ->get(),
            'view_path' => 'frontend.marketplace.checkout'
        ];
        
        return view('frontend.index', $page_data);
    }
    
    /**
     * Process product purchase
     * 
     * @param Request $request
     * @param int $id Product ID
     * @return JsonResponse|RedirectResponse
     */
    public function purchase(Request $request, $id)
    {
        $product = Marketplace::find($id);
        
        if (!$product) {
            Session::flash('error_message', get_phrase('Product not found'));
            return redirect()->route('allproducts');
        }
        
        // Don't allow buying your own product
        if ($product->user_id == auth()->user()->id) {
            Session::flash('error_message', get_phrase('You cannot buy your own product'));
            return redirect()->route('single.product', $id);
        }
        
        // Get user wallet
        $wallet = auth()->user()->wallet;
        
        // If wallet doesn't exist or has insufficient balance
        if (!$wallet) {
            Session::flash('error_message', get_phrase('Wallet not found. Please contact support.'));
            return redirect()->route('marketplace.checkout', $id);
        }
        
        // Check if user has enough balance
        if ($wallet->balance < $product->price) {
            Session::flash('error_message', get_phrase('Insufficient balance'));
            return redirect()->route('marketplace.checkout', $id);
        }
        
        // Start DB transaction
        DB::beginTransaction();
        
        try {
            // Create order
            $order = new MarketplaceOrder();
            $order->buyer_id = auth()->user()->id;
            $order->seller_id = $product->user_id;
            $order->product_id = $product->id;
            $order->amount = $product->price;
            $order->status = 'completed';
            $order->shipping_address = $request->shipping_address;
            $order->notes = $request->notes;
            $order->completed_at = now();
            $order->save();
            
            // Withdraw money from buyer's wallet
            $wallet->withdraw(
                $product->price, 
                'product_purchase', 
                'Purchase of product: ' . $product->title, 
                [
                    'product_id' => $product->id,
                    'order_id' => $order->id
                ]
            );
            
            // Add money to seller's wallet
            $sellerWallet = Wallet::where('user_id', $product->user_id)->first();
            if (!$sellerWallet) {
                $sellerWallet = Wallet::create([
                    'user_id' => $product->user_id,
                    'balance' => 0
                ]);
            }
            
            $sellerWallet->deposit(
                $product->price, 
                'product_sale', 
                'Sale of product: ' . $product->title,
                [
                    'product_id' => $product->id,
                    'order_id' => $order->id
                ]
            );
            
            // Mark product as sold
            $product->status = 0; // Set to "Out of Stock"
            $product->save();
            
            DB::commit();
            
            Session::flash('success_message', get_phrase('Product purchased successfully'));
            return redirect()->route('marketplace.purchase_history');
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Purchase error: ' . $e->getMessage());
            Session::flash('error_message', get_phrase('An error occurred during purchase. Please try again.'));
            return redirect()->route('marketplace.checkout', $id);
        }
    }
    
    /**
     * Show purchase history for the user
     * 
     * @return View
     */
    public function purchaseHistory()
    {
        $orders = MarketplaceOrder::where('buyer_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->with(['product', 'seller'])
            ->paginate(10);
            
        $page_data = [
            'orders' => $orders,
            'view_path' => 'frontend.marketplace.purchase_history'
        ];
        
        return view('frontend.index', $page_data);
    }
    
    /**
     * Show sold items for the user
     * 
     * @return View
     */
    public function soldItems()
    {
        $orders = MarketplaceOrder::where('seller_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->with(['product', 'buyer'])
            ->paginate(10);
            
        $page_data = [
            'orders' => $orders,
            'view_path' => 'frontend.marketplace.sold_items'
        ];
        
        return view('frontend.index', $page_data);
    }
}
