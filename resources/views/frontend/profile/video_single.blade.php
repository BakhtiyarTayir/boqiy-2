@foreach($all_videos as $video)
    <div class="single-item-countable single-photo n_video">
            <video onclick="$(location).prop('href', '{{route('single.post', $video->post_id)}}')" class="w-100 b-4 cursor-pointer" muted src="{{get_post_video($video->file_name)}}">

        </video>
        <a href="{{route('single.post', $video->post_id)}}" class="play_v_icon"><i class="fa-solid fa-play"></i></a>
        <a href="javascript:void(0)" onclick="confirmAction('<?php echo route('delete.mediafile', $video->id); ?>', true)" class="del_v_icon"><i class="fa-solid fa-trash"></i></a>
        {{-- <div class="post-controls dropdown dotted">
            <a class="nav-link dropdown-toggle" href="#"
                id="navbarDropdown" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">
            </a>
            <ul class="dropdown-menu"
                aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="{{route('single.post', $video->post_id)}}" >{{ get_phrase('Watch video') }}</a></li>
                <li><a class="dropdown-item" href="javascript:void(0)" onclick="confirmAction('<?php echo route('delete.mediafile', $video->id); ?>', true)">{{ get_phrase('Delete Video') }}</a></li>
            </ul>
        </div> --}}
    </div>
@endforeach
