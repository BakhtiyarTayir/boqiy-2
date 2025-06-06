<div class="main_content">
    <div class="mainSection-title">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>{{ get_phrase('Marketplace Products') }}</h4>
    
                    <a href="{{ route('admin.marketplace.free.products.run.deadline') }}" class="btn btn-success">
                        <i class="bi bi-plus"></i> Run Deadline
                    </a>

                    <a href="{{ route('admin.marketplace.free.products.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus"></i> {{ get_phrase('Add New Product') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="eSection-wrap">
                <div class="row mb-3">
                    <div class="col-md-4">
                    
                    </div>
                    <div class="col-md-8">
                        <form action="{{ route('admin.marketplace.free.products') }}" method="GET" class="d-flex justify-content-end">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="{{ get_phrase('Search by title or description') }}" value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                @if(count($freeProducts) > 0)
                <div class="table-responsive">
                    <table class="table eTable">
                        <thead>
                            <tr>
                                <th width="60px" class="text-center">#</th>
                                <th width="100px" class="text-center">{{ get_phrase('Image') }}</th>
                                <th style="width: 120px">{{ get_phrase('Nomi') }}</th>
                                <th width="120px">Sponsor FIO</th>
                                <th width="120px">Qabul qiluvchi</th>
                                <th width="120px">{{ get_phrase('Created') }}</th>
                                <th width="120px">Topshirilgan Sana</th>
                                <th width="150px" class="text-center">{{ get_phrase('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($freeProducts as $freeProduct)
                            <tr>
                                <td class="text-center fa-2x">{{ $loop->iteration }}</td>
                                <td class="text-center">
                                    <div class="product-image">
                                        <img src="{{ route('showProductTypeFile', ['product_type_id' => $freeProduct->product_type_id]) }}" alt="{{ $freeProduct->product_name }}"
                                             width="80" height="80" class="img-thumbnail"  style="object-fit: cover;">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-dark">{{ $freeProduct->product_name }}
                                            @if (!$freeProduct->is_active)
                                                <span class="badge bg-danger text-white ml-2">Sovg'a active emas</span>
                                            @elseif ($freeProduct->is_ordered)
                                                <span class="badge bg-success text-dark ml-2">Sovg‘a topshirilgan</span>
                                            @elseif ($freeProduct->is_sold)
                                                <span class="badge bg-warning text-dark ml-2">Sovg‘a xarid qilingan</span>
                                            @endif
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-dark">{{ $freeProduct->sponsor_name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-dark">{{ $freeProduct->receiver_name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-dark">{{ $freeProduct->created_date }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-dark">{{ $freeProduct->delivered_date }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.marketplace.free.products.edit', $freeProduct->id) }}" class="btn btn-sm btn-primary" title="{{ get_phrase('Edit') }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="{{ route('admin.marketplace.free.products.destroy', $freeProduct->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('{{ get_phrase('Are you sure you want to delete this product?') }}')" title="{{ get_phrase('Delete') }}">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <div class="empty_box center">
                        <img class="mb-3" width="150px" src="{{ asset('public/assets/images/empty_box.png') }}" alt="">
                        <br>
                        <span class="">{{ get_phrase('No products found') }}</span>
                        <br>
                        <a href="{{ route('admin.marketplace.free.products.create') }}" class="btn btn-primary mt-3">
                            <i class="bi bi-plus-circle"></i> {{ get_phrase('Add First Product') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">{{ get_phrase('Confirm Delete') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ get_phrase('Are you sure you want to delete this product?') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ get_phrase('Cancel') }}</button>
                <a href="#" id="deleteConfirmBtn" class="btn btn-danger">{{ get_phrase('Delete') }}</a>
            </div>
        </div>
    </div>
</div>

@section('styles')
<style>
    .product-image {
        position: relative;
        overflow: hidden;
        border-radius: 6px;
        transition: transform 0.3s;
    }
    
    .product-image:hover {
        transform: scale(1.05);
    }
    
    .btn-group .btn {
        margin: 0 2px;
    }
    
    .img-thumbnail {
        border: 2px solid #eee;
    }
</style>
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        // Delete product handling
        $('.delete-product').on('click', function() {
            var deleteUrl = $(this).data('url');
            $('#deleteConfirmBtn').attr('href', deleteUrl);
            $('#deleteConfirmationModal').modal('show');
        });
    });
</script>
@endsection