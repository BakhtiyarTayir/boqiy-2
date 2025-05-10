<div class="main_content">
    <div class="mainSection-title">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>{{ get_phrase('Product Details') }}</h4>
                    <div>
                        <a href="{{ route('admin.marketplace.products.edit', $product->id) }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil-square"></i> {{ get_phrase('Edit') }}
                        </a>
                        <a href="{{ route('admin.marketplace.products') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> {{ get_phrase('Back to Products') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-12">
            <div class="eSection-wrap">
                <div class="row">
                    <div class="col-md-8">
                        <table class="table eTable">
                            <tbody>
                                <tr>
                                    <th width="200">{{ get_phrase('ID') }}</th>
                                    <td>{{ $product->id }}</td>
                                </tr>
                                <tr>
                                    <th>{{ get_phrase('Title') }}</th>
                                    <td>{{ $product->title }}</td>
                                </tr>
                                <tr>
                                    <th>{{ get_phrase('Description') }}</th>
                                    <td>{!! nl2br(e($product->description)) !!}</td>
                                </tr>
                                <tr>
                                    <th>{{ get_phrase('Price') }}</th>
                                    <td>
                                        @if($product->getCurrency)
                                        {{ $product->getCurrency->symbol }} {{ number_format($product->price, 2) }}
                                        @else
                                        {{ number_format($product->price, 2) }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ get_phrase('Seller') }}</th>
                                    <td>
                                        @if($product->getUser)
                                        {{ $product->getUser->name }} (ID: {{ $product->getUser->id }})
                                        @else
                                        {{ get_phrase('N/A') }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ get_phrase('Location') }}</th>
                                    <td>{{ $product->location }}</td>
                                </tr>
                                <tr>
                                    <th>{{ get_phrase('Category') }}</th>
                                    <td>
                                        @if($product->getCategory)
                                        {{ $product->getCategory->name }}
                                        @else
                                        {{ get_phrase('N/A') }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ get_phrase('Brand') }}</th>
                                    <td>
                                        @if($product->getBrand)
                                        {{ $product->getBrand->name }}
                                        @else
                                        {{ get_phrase('N/A') }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ get_phrase('Condition') }}</th>
                                    <td>
                                        @if($product->condition == 'new')
                                        <span class="badge bg-primary">{{ get_phrase('New') }}</span>
                                        @else
                                        <span class="badge bg-secondary">{{ get_phrase('Used') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ get_phrase('Status') }}</th>
                                    <td>
                                        @if($product->status == 1)
                                        <span class="badge bg-success">{{ get_phrase('Active') }}</span>
                                        @else
                                        <span class="badge bg-danger">{{ get_phrase('Out of Stock') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ get_phrase('Buy Link') }}</th>
                                    <td>
                                        @if($product->buy_link)
                                        <a href="{{ $product->buy_link }}" target="_blank">{{ $product->buy_link }}</a>
                                        @else
                                        {{ get_phrase('N/A') }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ get_phrase('Created At') }}</th>
                                    <td>{{ date('d M, Y H:i', strtotime($product->created_at)) }}</td>
                                </tr>
                                <tr>
                                    <th>{{ get_phrase('Updated At') }}</th>
                                    <td>{{ date('d M, Y H:i', strtotime($product->updated_at)) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">{{ get_phrase('Product Images') }}</h5>
                            </div>
                            <div class="card-body">
                                @if(count($product_images) > 0)
                                <div class="product-images">
                                    <div id="product-images-carousel" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            @foreach($product_images as $key => $img)
                                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                                <img src="{{ asset('storage/marketplace/coverphoto/' . $img->file_name) }}" class="d-block w-100 img-fluid rounded" alt="{{ $product->title }}">
                                                @if($product->image == $img->file_name)
                                                <div class="carousel-caption d-none d-md-block">
                                                    <span class="badge bg-primary">{{ get_phrase('Main Image') }}</span>
                                                </div>
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>
                                        @if(count($product_images) > 1)
                                        <button class="carousel-control-prev" type="button" data-bs-target="#product-images-carousel" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">{{ get_phrase('Previous') }}</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#product-images-carousel" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">{{ get_phrase('Next') }}</span>
                                        </button>
                                        @endif
                                    </div>
                                    
                                    <div class="d-flex flex-wrap gap-2 mt-3">
                                        @foreach($product_images as $img)
                                        <div class="position-relative">
                                            <img src="{{ asset('storage/marketplace/thumbnail/' . $img->file_name) }}" alt="{{ $product->title }}" class="img-thumbnail" style="height: 60px; width: 60px; object-fit: cover; cursor: pointer;" data-bs-target="#product-images-carousel" data-bs-slide-to="{{ $loop->index }}">
                                            @if($product->image == $img->file_name)
                                            <span class="badge bg-primary position-absolute bottom-0 start-0" style="font-size: 8px;">{{ get_phrase('Main') }}</span>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @else
                                <div class="text-center">
                                    <img src="{{ asset('assets/images/placeholder.jpg') }}" alt="Placeholder" class="img-fluid rounded">
                                    <p class="mt-3">{{ get_phrase('No images available') }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="card-title">{{ get_phrase('Actions') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('admin.marketplace.products.edit', $product->id) }}" class="btn btn-primary">
                                        <i class="bi bi-pencil-square"></i> {{ get_phrase('Edit Product') }}
                                    </a>
                                    <a href="#" onclick="confirmModal('{{ route('admin.marketplace.products.destroy', $product->id) }}', 'undefined');" class="btn btn-danger">
                                        <i class="bi bi-trash"></i> {{ get_phrase('Delete Product') }}
                                    </a>
                                    @if($product->status == 1)
                                    <a href="{{ route('single.product', $product->id) }}" target="_blank" class="btn btn-outline-primary">
                                        <i class="bi bi-eye"></i> {{ get_phrase('View on Frontend') }}
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

