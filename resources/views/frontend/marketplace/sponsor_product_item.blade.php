<div class="product-details-wrap  p-3 radius-8 bg-white">
    <div class="product-header eProduct row">
        <div class="col-lg-12">
            <img src="{{ route('showProductTypeFile', ['product_type_id' => $product->id]) }}" alt="{{ $product->name }}" style="max-width: 350px;">
            @if (!empty($product_image) && count($product_image))
                <div id="carouselExampleIndicators" class="carousel np_carousel slide product-slider"
                    data-bs-ride="false">
                    
                     {{-- <div class="carousel-indicators">
                        @foreach ($product_image as $image )
                            <button type="button" data-bs-target="#carouselExampleIndicators"
                            data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->index=='0'? "active":"" }}" aria-current="true"
                            aria-label="Slide {{ $loop->index+1 }}"><img class="w-77 custome-height-59 ob_cover" src="{{ get_product_image($image->file_name,"thumbnail") }}" alt=""></button> --}}
                             {{-- indicator images  need  here  --}}
                        {{-- @endforeach
                    </div>  --}}
                    <div class="carousel-inner">
                        @foreach ($product_image as $image )
                            <div class="cursor_pointer carousel-item {{ $loop->index=='0'? "active":"" }}"  onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.marketplace.load_image', 'image' => $image->file_name])}}', '');">
                                <img class="rounded w-100" src="{{ get_product_image($image->file_name,"coverphoto") }}" alt="">
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button"
                        data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">{{get_phrase('Previous')}}</span>
                    </button>
                    <button class="carousel-control-next" type="button"
                        data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">{{get_phrase('Next')}}</span>
                    </button>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-12">
                    <div class="eProduct_details">
                        <div class="product-info  np_info_pro">
                            <h1 class="product-title h4 fw-7">Nomi: {{ $product->name }}</h1>
                            <span class="pt-price  sub-title">Narxi: {{ $product->price_for_sponsor }} so'm</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="n_detals_p pt-details @if(isset($_GET['shared'])) hidden-on-shared-view @endif">
                    <h3 class="sub-title">{{ get_phrase('Details') }}</h3>

                    <div class="product-description p_des mt-2">
                        @php echo script_checker($product->text_for_sponsor, false); @endphp
                    </div>
                </div>
            </div>
    
            <div class="row mt-4">
                <a href="#" class="btn btn-primary">
                    Sponsor bo'lish
                </a>
            </div>
        </div>


        @if(isset($related_product))
            <div class="related-prodcut mb-14 mt-3 ">
                <h3 class="sub-title">{{get_phrase('Related Product')}}</h3>
            </div>
            <div class="rl-products owl-carousel">
                @foreach ($related_product as $related_product )
                    <div class="card m_product product">
                        <div class="product-figure position-relative mb-0">
                            <a href="{{ route('single.product',$related_product->id) }}">
                                <div class="thumbnail-196-196" style="background-image: url('{{get_product_image($related_product->file_path,'coverphoto')}}');"></div>
                            </a>
                            {{-- @if ($related_product->user_id!=auth()->user()->id)
                                <a class="message-trigger" href="{{ route('chat',['reciver'=>$related_product->user_id,'product'=>$related_product->id]) }}"><i class="fa fa-message"></i></a>
                            @endif --}}
                        </div>
                         <div class="p-8">
                            <h3 class="h6"><a href="{{ route('single.product',$related_product->id) }}"> {{ ellipsis($related_product->name, 15) }}</a></h3>
                             <h5 class="my-3">Sponsor: {{ $related_product->sponsor_name }}</h5>
                            <a href="{{ route('single.product',$related_product->id) }}" class="btn common_btn d-block">
                                {{ $related_product->price_for_every_one }}
                                
                            </a>
                         </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div> <!-- row end -->
</div>

<script>
    $(document).ready(function () {
        $('#send-message').on('click', function () {
            let $btn = $(this);
            $btn.prop('disabled', true);

            let url = $(this).data('url');

            var formData = new FormData();
            var message = $('#user-message').val();
            var imageFile = $('#image-upload')[0].files[0];
            
            // Append data to FormData
            if (message.trim() !== '') {
                formData.append('text', message);
            }
            if (imageFile) {
                formData.append('image', imageFile);
            }
            
            // Perform AJAX request to send data
            $.ajax({
                url: url,  // Replace with your server's endpoint
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    $btn.removeAttr('disabled');

                    if (response.status === 'ok') {
                        let $commentTemplate = $('.product-comments .comment-template');
                        let $newComment = $commentTemplate.clone();
                        let productComment = response.product_comment;
    
                        $newComment.removeClass('d-none');
                        $newComment.find('.comment-user-name').text(response.user_name);
                        $newComment.find('.comment-date').text(productComment.created_date);
                        
                        let isRemoveLastLi = false;
    
                        if (productComment.type === 1) {
                            $newComment.find('.only-comment-text').text(productComment.text);
                            $newComment.find('.text-with-img').remove();
                            $newComment.find('.comment-img').remove();
                        } else {
                                $newComment.find('.only-comment-text').remove();
                            $newComment.find('.text-with-img').text(productComment.text);
        
                            let srcUrl = $newComment.find('.comment-img').data('img-url').replace('comment_id', productComment.id);
        
                            $newComment.find('.comment-img img').attr('src', srcUrl);
                            
                            isRemoveLastLi = true;
                        }
    
                        $('.product-comment-list').append($newComment);
                        
                        if (isRemoveLastLi) {
                            let $lastLi =  $('.product-comment-list li:last-child');
                            
                            if (!$lastLi.find('img').length) {
                                $lastLi.remove();
                            }
                        }
    
                        $('#user-message').val('');
                        $('#image-upload').val('');
                        $('#file-notification').hide();
                        $('#image-preview').attr('src', '');
                        $('#file-name').text('');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });
    
        $('#image-upload').on('change', function (event) {
            var file = event.target.files[0];
        
            if (file) {
                // Fayl nomi va preview rasmni ko‘rsatish
                $('#file-name').text(file.name);
                $('#file-notification').show();
            
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#image-preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            } else {
                // Fayl tanlanmagan bo‘lsa, hammasini yashirish
                $('#file-notification').hide();
                $('#image-preview').attr('src', '');
            }
        });

        $(document).on('click', '.btn-remove-img', function () {
            $('#image-upload').val(''); // Fayl inputni tozalash
            $('#file-notification').hide(); // Xabarni yashirish
            $('#image-preview').attr('src', ''); // Previewni tozalash
            $('#file-name').text(''); // Fayl nomini o‘chirish
        });
        
        let $btnViewMore = $('.btn-view-more');
        let $btnViewLess = $('.btn-view-less');

        $btnViewMore.on('click', function (e) {
            $btnViewLess.removeClass('d-none');
            $btnViewMore.addClass('d-none');
            
            $('.product-comments .hide-comment-item').removeClass('d-none');
        })
    
        $btnViewLess.on('click', function (e) {
            $btnViewMore.removeClass('d-none');
            $btnViewLess.addClass('d-none');
    
            $('.product-comments .hide-comment-item').addClass('d-none');
        })
    });

</script>


