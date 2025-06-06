@foreach ($vidoes as $video )
        @php
            $post = DB::table('posts')->where('privacy', '!=', 'private')
            ->where('publisher', 'video_and_shorts')
            ->where('publisher_id', $video->id)
            ->first();
        @endphp
        @php
        
        $total_comments = DB::table('comments')->where('comments.is_type', 'post')->where('comments.id_of_type', $post->post_id)->where('comments.parent_id', 0)->get()->count();


        $comments = DB::table('comments')
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->where('comments.is_type', 'post')
            ->where('comments.id_of_type', $post->post_id)
            ->where('comments.parent_id', 0)
            ->select('comments.*', 'users.name', 'users.photo')
            ->orderBy('comment_id', 'DESC')->take(1)->get();


        $tagged_user_ids = json_decode($post->tagged_user_ids);
        

    @endphp
    @php $user_reacts = json_decode($post->user_reacts, true); @endphp

    <div class="single-entry single-item-countable" id="video-{{ $video->id }}"> 
        <div class="entry-inner">
            <div class="entry-header d-flex justify-content-between">
                <div class="ava-info d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <img src="{{ get_user_image($video->getUser->photo,'optimized') }}" class="rounded rounded-circle user_image_show_on_modal" alt="...">
                    
                    </div>
                    <div class="ava-desc ms-2">
                        <h3 class="mb-0">{{ $video->getUser->name }} 
                            @php
                                $follow = \App\Models\Follower::where('user_id',$video->getUser->id)->where('follow_id',$video->getUser->id)->count();
                            @endphp
                            @if ($follow>0)
                                <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('user.unfollow',$video->getUser->id); ?>')">{{ get_phrase('Unfollow') }}</a> 
                            @else
                                <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('user.follow',$video->getUser->id); ?>')">{{ get_phrase('Follow') }}</a> 
                            @endif
                            
                        </h3>
                        <span class="meta-time text-muted">{{ $video->created_at->timezone(Auth::user()->timezone)->format("M d") }} at {{ date('H:i A', strtotime($video->created_at)); }}</span>
                        @if ($video->privacy=='public')
                            <span class="meta-privacy text-muted"><i
                                class="fa-solid fa-earth-americas"></i></span>
                        @endif
                    </div>
                </div>
                <div class="post-controls dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li>
                            @php
                                $saved = \App\Models\Saveforlater::where('video_id',$video->id)->where('user_id',auth()->user()->id)->count();
                            @endphp
                            @if ($saved>0)
                            <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('unsave.video.later',$video->id); ?>')" class="dropdown-item btn btn-primary btn-sm"> <img src="{{ asset('assets/frontend/images/save.png') }}" alt=""> {{get_phrase('Unsave Video')}}</a>
                            @else
                            <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('save.video.later',$video->id); ?>')" class="dropdown-item btn btn-primary btn-sm"> <img src="{{ asset('assets/frontend/images/save.png') }}" alt=""> {{get_phrase('Save Video')}}</a>
                            @endif
                        </li>
                        @if ($video->user_id==auth()->user()->id)
                            <li>
                                <a href="javascript:void(0)" onclick="confirmAction('<?php echo route('video.delete', ['video_id' => $video->id]); ?>', true)" class="dropdown-item btn btn-primary btn-sm"><i class="fa fa-trash me-1"></i> {{get_phrase('Delete Video')}}</a>
                            </li>
                        @endif
                        
                    </ul>
                </div>
            </div>
            <div class="entry-content pt-2">
                <video class="plyr-js w-100" onplay="pauseOtherVideos(this)" controls src="{{asset('storage/videos/'.$video->file)}}">
            </div>
           
            <div class="e_comment">
                <div class="entry-footer">
                    <div class="footer-share d-flex justify-content-around">
                        <span class="entry-react post-react">

                            <a href="javascript:void(0)" onclick="myReact('post', 'like', 'toggle', {{$post->post_id}})" id="my_post_reacts<?php echo $post->post_id; ?>">
                                @include('frontend.main_content.post_reacts', ['my_react' => true,'user_info'=>$user_info])
                            </a>

                            <ul class="react-list">
                                <li><a href="javascript:void(0)" onclick="myReact('post', 'like', 'update', {{$post->post_id}})"><img src="{{asset('storage/images/like.svg')}}" alt="Like" style="margin-right: 1px;"></a>
                                </li>
                                <li><a href="javascript:void(0)" onclick="myReact('post', 'love', 'update', {{$post->post_id}})"><img src="{{asset('storage/images/love.svg')}}" alt="Love" style="width: 30px; margin-top: 2px;"></a>
                                </li>
                                <li><a href="javascript:void(0)" onclick="myReact('post', 'haha', 'update', {{$post->post_id}})"><img src="{{asset('storage/images/haha.svg')}}" alt="Haha"></a>
                                </li>
                                <li><a href="javascript:void(0)" onclick="myReact('post', 'sad', 'update', {{$post->post_id}})"><img src="{{asset('storage/images/sad.svg')}}" class="mx-1" alt="Sad"></a>
                                </li>
                                <li><a href="javascript:void(0)" onclick="myReact('post', 'angry', 'update', {{$post->post_id}})"><img src="{{asset('storage/images/angry.svg')}}" alt="Angry"></a>
                                </li>
                            </ul>
                        </span>
                        <span class="entry-react"><a href="javascript:void(0)" onclick="$('#user-comments-{{$post->post_id}}').toggle();"><img width="19px" src="{{ asset('storage/images/comment2.svg') }}">{{get_phrase('Comments')}}</a></span>
                        <span class="entry-react" data-bs-toggle="modal" data-bs-target="#exampleModal"><a
                                href="javascript:void(0)" onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.main_content.share_post_modal', 'post_id' => $post->post_id] )}}', '{{get_phrase('Share post')}}');"><img width="19px" src="{{ asset('storage/images/share2.svg') }}">{{get_phrase('Share')}}</a></span>
                        <!-- Post share modal -->
                    </div>
                      @php
                          $user_info = \App\Models\User::find($video->getUser->id);
                      @endphp
                    <div class="entry-meta py-4 d-flex border-bottom justify-content-between align-items-center" >
                        <a href="javascript:void(0)" id="post_reacts<?php echo $post->post_id; ?>">
                            @include('frontend.main_content.post_reacts', ['post_react' => true,'user_info'=>$user_info])
                        </a>
        
                        <div class="post-comment">
                            <ul>
                                <li><a href="javascript:void(0)"> <span id="post_comment_count{{ $post->post_id }}">{{$total_comments}}</span>  {{get_phrase('Comments')}}</a></li>
                                @php $sharecount = \App\Models\Post_share::where('post_id',$post->post_id)->count(); @endphp
                                <li><a href="javascript:void(0)"><span> {{ $sharecount }} </span>{{get_phrase('Share')}}</a></li>
                            </ul>
                        </div>
                    </div>
                </div> <!-- Entry Footer End -->
           </div>
        </div>
        <!-- Comment Start -->
        <div class="user-comments s_comment  d-hidden bg-white" id="user-comments-{{$post->post_id}}">
            <div class="comment-form d-flex mb-1">
                <img src="{{get_user_image(Auth()->user()->photo, 'optimized')}}" alt="" class="h-39 rounded-circle img-fluid" width="40px">
                <form action="javascript:void(0)" class="w-100 ms-2" method="post">
                    <input class="form-control py-3" onkeypress="postComment(this, 0, {{$post->post_id}}, 0,'post');" rows="1" placeholder="{{ get_phrase('Write Comments') }}">
                </form>
            </div>
            <ul class="comment-wrap p-3 pb-0 list-unstyled" id="comments{{$post->post_id}}">
                @include('frontend.main_content.comments',['comments'=>$comments,'post_id'=>$post->post_id,'type'=>"post"])
            </ul>

            @if($comments->count() < $total_comments)
                <a class="btn p-3 pt-0" onclick="loadMoreComments(this, {{$post->post_id}}, 0, {{$total_comments}},'post')">{{get_phrase('View more')}}</a>
            @endif
        </div>
    </div>
    @endforeach


    @include('frontend.initialize')