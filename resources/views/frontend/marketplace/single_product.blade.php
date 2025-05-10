<div class="product-details-wrap  p-3 radius-8 bg-white">
    <div class="product-header eProduct row">
        @php
            $userWallet = auth()->user()->wallet;
            $userWalletBalance = 0;

            if ($userWallet) {
				$userWalletBalance = $userWallet->balance;
            }
        @endphp
        @if ($product->price_for_every_one > $userWalletBalance)
            <div class="my-3">
                <p class="mx-3" style="color: #dc3545; font-weight: bold;">
                    <i class="fas fa-exclamation fa-lg me-1"></i>️ Afsuski, bu tovarni olish uchun  <i class="fas fa-wallet text-primary mx-2"></i> hamyoningizda mablag' yetarli emas.
                </p>
            </div>
        @endif
        <div class="col-lg-12">
            <img src="{{ route('showProductTypeFile', ['product_type_id' => $product->product_type_id]) }}" alt="{{ $product->name }}"
                 class="img-thumbnail"  style="max-width: 100%; height: auto;">
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
            
            <div class="row mt-3">
                <div class="col-lg-12">
                    <div class="eProduct_details">
                        <div class="product-info  np_info_pro">
                            <h1 class="product-title h4 fw-7 my-2">
                                {{ $product->name }}
                                @if ($product->is_sold)
                                    <span class="badge bg-warning text-dark ml-2">Sovg‘a topshirilgan</span>
                                @endif
                            </h1>
                            
                            <span class="product-title h4 fw-7 my-2">Narxi: </span>
                            <del><span class="pt-price  sub-title">{{ $product->price_for_every_one }} so'm</span></del>
                            <span style="color: firebrick" class="pt-price fa-lg sub-title ml-3">{{ 'O so\'m' }}</span>
                            
                            
                            <hr class="mt-3">
                            
                            @if (!$product->is_anonymous_sponsor)
                                <h1 class="product-title h4 fw-7 mb-2">Homiy</h1>
                                
                                <div class="pb-author align-items-center">
                                    <a href="{{ route('user.profile.view', $product->sponsor_id) }}">
                                        <img class="user_image_proifle_height" src="{{ route('showUserPhoto', $product->sponsor_id) }}" alt=""
                                             style="width: 30%; height: auto">
                                    </a>
                                    <div class="pb-info ms-2 mt-1">
                                        <a href="{{ route('user.profile.view', $product->sponsor_id) }}" class="h6">
                                            {{ $product->sponsor_name }}
                                        </a>
                                    </div>
                                </div>
                                
                                @php
                                    $social_links = json_decode($product->sponsor_social_links, true);

                                    $telegram = $social_links['telegram'] ?? '';
                                    $instagram = $social_links['instagram'] ?? '';
                                    $facebook = $social_links['facebook'] ?? '';
                                    $youtobe = $social_links['youtobe'] ?? '';
                                    $site = $social_links['site'] ?? '';
                                @endphp
                                
                                @if (!empty($telegram))
                                    <div class="my-2">
                                        <a href="{{ $telegram }}" target="_blank" >
                                            <i class="fab fa-telegram-plane fa-lg" style="color: #0088cc;"></i>
                                            Telegram
                                        </a>
                                    </div>
                                @endif
                                @if (!empty($instagram))
                                    <div class="my-2">
                                        <a href="{{ $instagram }}" target="_blank" >
                                            <i class="fab fa-instagram fa-lg" style="color: #E1306C;"></i>
                                            Instagram
                                        </a>
                                    </div>
                                @endif
                                @if (!empty($facebook))
                                    <div class="my">
                                        <a href="{{ $facebook }}" target="_blank" >
                                            <i class="fab fa-facebook fa-lg" style="color: #1877F2;"></i>
                                            Facebook
                                        </a>
                                    </div>
                                
                                @endif
                                @if (!empty($youtobe))
                                    <div class="my-2">
                                        <a href="{{ $youtobe }}" target="_blank" >
                                            <i class="fab fa-youtube fa-lg" style="color: #FF0000;"></i>
                                            You Tobe
                                        </a>
                                    </div>
                                @endif
                                @if (!empty($site))
                                    <div class="my-2">
                                        <a href="{{ $site }}" target="_blank" >
                                            <i class="fas fa-globe fa-lg" style="color: #0d6efd;"></i>
                                            Site
                                        </a>
                                    </div>
                                @endif
                                
                                <hr class="my-3">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="n_detals_p pt-details @if(isset($_GET['shared'])) hidden-on-shared-view @endif">
                    <h3 class="sub-title2">{{ get_phrase('Details') }}</h3>
                    
                    <div class="product-description p_des mt-20">
                        @php echo script_checker($product->text_for_every_one, false); @endphp
                    </div>
                </div>
            </div>
            
            <hr class="mt-2">
            
            <div class="row my-1 mt-2">
                <h4>Comments</h4>
                <div class="product-comments s_comment2 bg-white">
                    <ul class="comment-wrap p-3 pt-0 pb-0 list-unstyled product-comment-list">
                        <li class="comment-item comment-template n_comment_item mb-0 d-none">
                            <div class="d-flex justify-content-between mb-8">
                                <div class="d-flex">
                                    <!-- Avatar -->
                                    <div class="">
                                        <a href="#" class="h-39 mt-2">
                                            <span><i class="fas fa-user-circle fa-3x"></i></span>
                                        </a>
                                    </div>
                                    <div class="avatar-info ms-2">
                                        <div class="comment-details n_comment_details">
                                            <div class="comment-content bg-secondary">
                                                <h4 class="ava-nave comment-user-name mb-1"></h4>
                                                <p class="only-comment-text"></p>
                                                <p class="mt-2 comment-img" data-img-url="{{ route('showProductCommentFile', ['comment_id' => 'comment_id']) }}">
                                                    <img style="max-width: 300px;">
                                                </p>
                                                <p class="mt-3 text-with-img"></p>
                                            </div>
                                            <p class="f-10 ml-3 comment-date">
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @if (!empty($product_comments) && count($product_comments))
                            @foreach ($product_comments as $comment)
                                <li class="comment-item n_comment_item mb-0 @if ($loop->iteration > 3) hide-comment-item d-none @endif ">
                                    <div class="d-flex justify-content-between mb-8">
                                        <div class="d-flex">
                                            <!-- Avatar -->
                                            <div class="">
                                                <a href="#" class="h-39 mt-2">
                                                    <span><i class="fas fa-user-circle fa-3x"></i></span>
                                                </a>
                                            </div>
                                            <div class="avatar-info ms-2">
                                                <div class="comment-details n_comment_details">
                                                    <div class="comment-content bg-secondary">
                                                        <h4 class="ava-nave">{{ $comment->user_name }}</h4>
                                                        @if ($comment->type == \App\Models\ProductComment::TYPE_TEXT)
                                                            <p>{{ $comment->text }}</p>
                                                        @elseif ($comment->type == \App\Models\ProductComment::TYPE_FILE)
                                                            <p class="mt-2">
                                                                <img src="{{ route('showProductCommentFile', ['comment_id' => $comment->id]) }}" alt="Rasm" style="max-width: 300px;">
                                                            </p>
                                                            <p class="mt-3">{{ $comment->text }}</p>
                                                        @endif
                                                    </div>
                                                    <p class="f-10 ml-3">
                                                        {{ $comment->created_date }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                    
                    <a class="btn btn-view-more p-3 pt-0">View more</a>
                    <a class="btn btn-view-less d-none p-3 pt-0">View less </a>
                    
                    <div id="file-notification" class="mb-3" style="display: none;">
                        <p>Rasm tanlandi: <span id="file-name"></span>
                            <span style="cursor: pointer; color: red"> <i class="btn-remove-img fa fa-trash"></i></span>
                        </p>
                        <img id="image-preview" src="" alt="Rasmni oldindan ko'rish" style="max-width: 100px; max-height: 100px; margin-top: 10px;">
                    </div>
                    
                    <div class="d-flex align-items-center bg-secondary p-2 rounded-pill product-comment-input">
                        <!-- Image Upload Icon -->
                        <label for="image-upload" class="btn btn-sm btn-light
                        rounded-circle p-2 m-0 d-flex align-items-center
                        justify-content-center me-2">
                            <i class="fas fa-image text-primary fs-5"></i>
                        </label>
                        <input type="file" id="image-upload" class="d-none" accept="image/*">
                        
                        <!-- Text Input -->
                        <textarea
                            id="user-message"
                            class="form-control border-0 bg-transparent"
                            rows="1"
                            placeholder="Fikringizni yozing..."
                            style="resize: none; box-shadow: none;"
                        ></textarea>
                        
                        <!-- Send Button -->
                        <button type="button" id="send-message"
                                class="btn btn-sm btn-primary rounded-circle d-flex align-items-center justify-content-center ms-2"
                                style="width: 36px; height: 36px;"
                                data-url="{{  route('saveProductComment', ['product_id' => $product->id]) }}"
                        >
                            <i class="fas fa-paper-plane text-white"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        
        @if (!empty($related_product) && count($related_product))
            <div class="related-prodcut mb-14 mt-3 ">
                <h3 class="sub-title">{{get_phrase('Related Product')}}</h3>
            </div>
            <div class="rl-products owl-carousel">
                @foreach ($related_product as $related_product )
                    <div class="card m_product product">
                        <div class="product-figure position-relative mb-0">
                            <a href="{{ route('single.product', $related_product->id) }}">
                                <img src="{{ route('showProductTypeFile', ['product_type_id' => $related_product->product_type_id]) }}"
                                     alt="{{ $related_product->name }}" style="max-width: 100%; height: auto;">
                            
                            </a>
                            {{-- @if ($related_product->user_id!=auth()->user()->id)
                                <a class="message-trigger" href="{{ route('chat',['reciver'=>$related_product->user_id,'product'=>$related_product->id]) }}"><i class="fa fa-message"></i></a>
                            @endif --}}
                        </div>
                        <div class="p-8">
                            <h3 class="h6">
                                <a href="{{ route('single.product',$related_product->id) }}"> {{ ellipsis($related_product->name, 15) }}</a>
                                @if ($related_product->is_sold)
                                    <span class="badge bg-warning text-dark ml-2">Sovg‘a topshirilgan</span>
                                @endif
                            </h3>
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


