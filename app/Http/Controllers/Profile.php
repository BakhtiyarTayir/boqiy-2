<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Query\JoinClause;

use App\Models\{Stories, Posts, Comments, Feeling_and_activities, CommonModels, Live_streamings, Users, Friendships, Media_files, Albums, Notification, User, FileUploader, Album_image, Follower};

use Session, Image;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;

use DB;

class Profile extends Controller
{
    private $user;
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth()->user();
            return $next($request);
        });
    }


    function profile()
    {

        //For my own profile
        $posts =  Posts::where(function ($query) {
            $query->whereJsonContains('posts.tagged_user_ids', [$this->user->id])
                ->where('posts.privacy', '!=', 'private')
                ->orWhere('posts.user_id', $this->user->id);
        })
            ->where('posts.publisher', 'post')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->select('posts.*', 'users.name', 'users.photo', 'users.friends', 'posts.created_at as created_at')
            ->take(5)->orderBy('posts.post_id', 'DESC')->get();

         // New
         $friendships = Friendships::where(function ($query) {
            $query->where('accepter', auth()->user()->id)
                ->orWhere('requester', auth()->user()->id);
        })
            ->where('is_accepted', 1)
            ->orderBy('friendships.importance', 'desc')->get();
        $page_data['friendships'] = $friendships;
      //new


        $page_data['posts'] = $posts;
        $page_data['user'] = $this->user;
        $page_data['view_path'] = 'frontend.profile.index';
        return view('frontend.index', $page_data);
    }
	
	public function profileEdit(Request $request)
	{
		$userId = $request->id;
		$currentUser = auth()->user();
		
		if ($userId && $userId == $currentUser->id) {
			$user_telegram = '';
			$user_instagram = '';
			$user_facebook = '';
			
			$user_youtobe = '';
			$user_site = '';
			$social_links = json_decode($currentUser->social_links, true);
			
			if ($social_links && count($social_links)) {
				$user_telegram = $social_links['telegram'] ?? '';
				$user_instagram = $social_links['instagram'] ?? '';
				$user_facebook = $social_links['facebook'] ?? '';
				$user_youtobe = $social_links['youtobe'] ?? '';
				$user_site = $social_links['site'] ?? '';
			}
			
			$page_data['user_info'] = $currentUser;
			$page_data['view_path'] = 'frontend.profile.index';
			$page_data['user_telegram'] = $user_telegram;
			$page_data['user_instagram'] = $user_instagram;
			$page_data['user_facebook'] = $user_facebook;
			$page_data['user_youtobe'] = $user_youtobe;
			$page_data['user_site'] = $user_site;
			return view('frontend.index', $page_data);
		}
		
		return redirect(route('timeline'));
	}
	
	public function profileUpdate(Request $request)
	{
		$rules = array(
			'name' => ['required', 'string', 'max:255'],
		);
		
		$validator = Validator::make($request->all(), $rules);
		
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}
		
		$userId = $request->id;
		$currentUser = auth()->user();
		
		if ($userId && $userId == $currentUser->id) {
			$currentUser->name = $request->name;
			$currentUser->date_of_birth = $request->date_of_birth;
			$currentUser->address = $request->address;
			$currentUser->regionId = $request->regionId;
			$currentUser->districtId = $request->districtId;
			$currentUser->profession = $request->profession;
			$currentUser->about = $request->about;
			
			$social_links = [
				'telegram' => $request->telegram,
				'instagram' => $request->instagram,
				'facebook' => $request->facebook,
				'youtobe' => $request->youtobe,
				'site' => $request->site,
			];
			
			$currentUser->social_links = json_encode($social_links);
			$photo = $request->file('photo');
			
			if ($photo) {
				$currentUser->cover_photo = $this->savePhotoFile($photo, $userId);
			}
			
			if (!$request->is_anonymous_sponsor) {
				$currentUser->is_anonymous_sponsor = 0;
			} else {
				$currentUser->is_anonymous_sponsor = $request->is_anonymous_sponsor;
			}
			
			$currentUser->save();
			
			if (!empty($request->isSponsor)) {
				return redirect(route('allProductsForSponsor'));
			}
			
			return redirect(route('profileEdit', ['id' => $userId]));
		}
		
		return redirect(route('timeline'));
	}

	public function showUserPhoto(Request $request)
	{
		$user = \App\Models\User::where('id', $request->id)->first();
		
		// Если пользователь не найден или у него нет фото, показать дефолтное изображение
		if (!$user || !$user->cover_photo) {
			$path = public_path('user/photo/20/1f8a46ad-cdec-4f11-a665-1fa1b38f9121/1745523297.jpeg');
		} else {
			$path = public_path($user->cover_photo);
		}
		
		if (!File::exists($path)) {
			// Если файл не найден, показать дефолтное изображение
			$path = public_path('user/photo/20/1f8a46ad-cdec-4f11-a665-1fa1b38f9121/1745523297.jpeg');
			
			// Если и дефолтное изображение не найдено
			if (!File::exists($path)) {
				abort(404, 'Изображение не найдено.');
			}
		}

		return Response::make(File::get($path), 200, [
			'Content-Type' => File::mimeType($path),
			'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
		]);
	}
	
	private function savePhotoFile($imageFile, $userId)
	{
		$uniqueId = Str::uuid()->toString();
		$fileName = time() . '.' . $imageFile->getClientOriginalExtension();
		$path = "user/photo/{$userId}/{$uniqueId}";

		if (!File::exists($path)) {
			File::makeDirectory($path, 0755, true);
		}

		$imageFile->move(public_path($path), $fileName);

		return $path . '/' . $fileName;
	}

    function load_post_by_scrolling(Request $request)
    {
        //For my own profile
        $posts =  Posts::where(function ($query) {
            $query->whereJsonContains('posts.tagged_user_ids', [$this->user->id])
                ->where('posts.privacy', '!=', 'private')
                ->orWhere('posts.user_id', $this->user->id);
        })
            ->where('posts.publisher', 'post')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->select('posts.*', 'users.name', 'users.photo', 'users.friends', 'posts.created_at as created_at')
            ->skip($request->offset)->take(3)->orderBy('posts.post_id', 'DESC')->get();

          // New
          $friendships = Friendships::where(function ($query) {
            $query->where('accepter', auth()->user()->id)
                ->orWhere('requester', auth()->user()->id);
        })
            ->where('is_accepted', 1)
            ->orderBy('friendships.importance', 'desc')
            ->get();

        $page_data['friendships'] = $friendships;
      //new   


        $page_data['posts'] = $posts;
        $page_data['user_info'] = $this->user;
        $page_data['type'] = 'user_post';
        return view('frontend.main_content.posts', $page_data);
    }

    function friends()
    {

        $friendships = Friendships::where(function ($query) {
            $query->where('accepter', $this->user->id)
                ->orWhere('requester', $this->user->id);
        })
            ->where('is_accepted', 1)
            ->orderBy('friendships.importance', 'desc')
            ->take(15)->get();

        $friend_requests = Friendships::where('accepter', $this->user->id)
            ->where('is_accepted', '!=', 1)
            ->take(15)->get();

            $userId = auth()->user()->id;
            $add_friend = User::whereNotIn('id', [$userId])->get();
            
            $page_data['add_friend'] = $add_friend;
            $page_data['info'] = auth()->user()->id;
            

        $page_data['friendships'] = $friendships;
        $page_data['friend_requests'] = $friend_requests;
        

        $page_data['user_info'] = $this->user;
        $page_data['view_path'] = 'frontend.profile.index';
        return view('frontend.index', $page_data);
    }

    function photos()
    {

        $all_photos = Media_files::where('user_id', $this->user->id)
            ->where('file_type', 'image')
            ->whereNull('story_id')
            ->whereNull('product_id')
            ->whereNull('page_id')
            ->whereNull('group_id')
            ->whereNull('chat_id')
            ->orderBy('id', 'DESC')->get();

        $all_albums = Albums::where('user_id', $this->user->id)
            ->whereNull('page_id')
            ->whereNull('group_id')
            ->take(6)->orderBy('id', 'DESC')->get();

        $page_data['all_photos'] = $all_photos;
        $page_data['all_albums'] = $all_albums;
        $page_data['user_info'] = $this->user;

        $page_data['page_identifire'] = 'profile';

        $page_data['view_path'] = 'frontend.profile.index';
        return view('frontend.index', $page_data);
    }

    function load_photos(Request $request)
    {
        $all_photos = Media_files::where('user_id', $this->user->id)
            ->where('file_type', 'image')
            ->whereNull('story_id')
            ->whereNull('product_id')
            ->whereNull('page_id')
            ->whereNull('group_id')
            ->whereNull('chat_id')
            ->skip($request->offset)->take(12)->orderBy('id', 'DESC')->get();

        $page_data['all_photos'] = $all_photos;
        $page_data['user_info'] = $this->user;
        return view('frontend.profile.photo_single', $page_data);
    }

    function album($action_type, Request $request)
    {
        // return $action_type;
        $error = null;

        if ($action_type == 'form') {
            return view('frontend.profile.album_create_form');
        } elseif ($action_type == 'delete') {
            DB::table('albums')->where('id', $request->album_id)->delete();
            DB::table('media_files')->where('album_id', $request->album_id)->delete();

            $response = array('alertMessage' => get_phrase('Album deleted successfully'), 'hideElem' => '#photoAlbum'.$request->album_id);
            return json_encode($response);

        } elseif ($action_type == 'store') {
            $album_show_on= 'profile';
            $page_data['page_identifire'] = 'profile';
            $rules = array('title' => 'required|max:255', 'privacy' => 'required', 'thumbnail' => 'image|nullable');
            $validator = Validator::make($request->all(), $rules);
            // Validate the input and return correct response
            if ($validator->fails()) {
                return json_encode(array('validationError' => $validator->getMessageBag()->toArray()));
            }



            $data['user_id'] = $this->user->id;
            $data['title'] = $request->title;
            $data['sub_title'] = $request->sub_title;
            $data['privacy'] = $request->privacy;
            if (isset($request->page_id) && !empty($request->page_id)) {
                $data['page_id'] = $request->page_id;
                $album_show_on= 'page';
                $page_data['page_identifire'] = 'page';
            }
            if (isset($request->group_id) && !empty($request->group_id)) {
                $data['group_id'] = $request->group_id;
                $album_show_on= 'group';
                $page_data['page_identifire'] = 'group';
            }
            $data['created_at'] = time();
            $data['updated_at'] = $data['created_at'];


            if ($request->thumbnail) {
                $file_name = FileUploader::upload($request->thumbnail,'public/storage/thumbnails/album', 800);

                $data['thumbnail'] = $file_name;
            }


            $album_id = Albums::insertGetId($data);
            $page_data['all_albums'] = Albums::where('id', $album_id)->get();
            
            $album_view = view('frontend.profile.album_single', $page_data)->render();
            $response = array('hideCustomModal' => 1, 'appendAfterElement' => '#'.$album_show_on.'-album-row .col-create-album:first-child', 'content' => $album_view);

            echo json_encode($response);
        }
    }

    function load_albums(Request $request)
    {
        $all_albums = Albums::where('user_id', $this->user->id)
            ->whereNull('page_id')
            ->whereNull('group_id')
            ->skip($request->offset)->take(20)->orderBy('id', 'DESC')->get();

        $page_data['all_albums'] = $all_albums;
        $page_data['user_info'] = $this->user;
        return view('frontend.profile.album_single', $page_data);
    }

    function videos()
    {

        $all_videos = Media_files::where('user_id', $this->user->id)
            ->where('file_type', 'video')
            ->take(24)->orderBy('id', 'DESC')->get();

        $page_data['all_videos'] = $all_videos;
        $page_data['user_info'] = $this->user;
        $page_data['view_path'] = 'frontend.profile.index';
        return view('frontend.index', $page_data);
    }

    function load_videos(Request $request)
    {
        $all_videos = Media_files::where('user_id', $this->user->id)
            ->where('file_type', 'video')
            ->skip($request->offset)->take(12)->orderBy('id', 'DESC')->get();

        $page_data['all_videos'] = $all_videos;
        $page_data['user_info'] = $this->user;
        return view('frontend.profile.video_single', $page_data);
    }

    function load_my_friends(Request $request)
    {
        $friendships = Friendships::where(function ($query) {
            $query->where('accepter', $this->user->id)
                ->orWhere('requester', $this->user->id);
        })
            ->where('is_accepted', 1)
            ->orderBy('friendships.importance', 'desc')
            ->skip($request->offset)->take(15)->get();

        $page_data['friendships'] = $friendships;
        $page_data['user_info'] = $this->user;
        return view('frontend.profile.friends_single_data', $page_data);
    }

    function load_my_friend_requests(Request $request)
    {
        $friend_requests = Friendships::where('accepter', $this->user->id)
            ->where('is_accepted', '!=', 1)
            ->skip($request->offset)->take(15)->get();

        $page_data['friend_requests'] = $friend_requests;
        $page_data['user_info'] = $this->user;
        return view('frontend.profile.friend_requests_single_data', $page_data);
    }

    function accept_friend_request(Request $request)
    {
        
        $response = array();
        $follwer = new Follower();
        $follwer->follow_id = $request->user_id;
        $follwer->user_id = auth()->user()->id;
        $follwer->save();

        $is_updated = Friendships::where('accepter', $this->user->id)
            ->where('requester', $request->user_id)
            ->update(['is_accepted' => 1]);


        if ($is_updated == 1) {
            //update my friends id to my friend list
            $my_friends = User::where('id', $this->user->id)->value('friends');
            $my_friends = json_decode($my_friends);
            if(is_array($my_friends)){
                array_push($my_friends, (int)$request->user_id);
            }else{
                $my_friends = [(int)$request->user_id];
            }
            $my_friends = json_encode($my_friends);

            User::where('id', $this->user->id)->update(['friends' => $my_friends]);


            //update my id to my friend list
            $my_friends_of_friends = User::where('id', $request->user_id)->value('friends');
            $my_friends_of_friends = json_decode($my_friends_of_friends);

            if(is_array($my_friends_of_friends)){
                array_push($my_friends_of_friends, (int)$this->user->id);
            }else{
                $my_friends_of_friends = [(int)$this->user->id];
            }
            $my_friends_of_friends = json_encode($my_friends_of_friends);

            User::where('id', $request->user_id)->update(['friends' => $my_friends_of_friends]);


            //Send notification
            Notification::where('sender_user_id',(int)$request->user_id)->where('reciver_user_id',$this->user->id)->update(['status'=>'1','view'=>'1']);
            $notify = new Notification();
            $notify->sender_user_id = auth()->user()->id;
            $notify->reciver_user_id = (int)$request->user_id;
            $notify->type = "friend_request_accept";
            $notify->save();

            $response = array('alertMessage' => get_phrase('Friend request accepted'), 'showElem' => "#friendRequestAcceptedBtn$request->user_id", 'hideElem' => "#friendRequestConfirmBtn$request->user_id");
        }
        

        return json_encode($response);
    }

    
    function delete_friend_request(Request $request)
    {
        $response = array();

        $row = Friendships::where('accepter', $this->user->id)
            ->where('requester', $request->user_id)
            ->where('is_accepted', '!=', 1);


        if ($row->get()->count() > 0) {
            Friendships::where('id', $row->value('id'))->delete();
            $response = array('alertMessage' => get_phrase('Friend request deleted'), 'fadeOutElem' => "#friendRequest$request->user_id");
        }

        return json_encode($response);
    }

    function about($action_type, Request $request)
    {
        $response = array();

        if ($action_type = 'update') {
            $data['about'] = $request->about;
            Users::where('id', $this->user->id)->update($data);
            $response = array('alertMessage' => get_phrase('Your bio updated'), 'hideElem' => '.edit-bio-form', 'showElem' => '.edit-bio-btn', 'elemSelector' => '.my-about', 'content' => htmlspecialchars(nl2br($request->about)));
        }

        return json_encode($response);
    }

    function my_info($action_type, Request $request)
    {
        $response = array();

        if ($action_type == 'edit') {
            $page_data['user_info'] = Users::where('id', $this->user->id)->first();
            return view('frontend.profile.my_info_edit', $page_data);
        } elseif ($action_type == 'update') {
            $data['job'] = $request->job;
            $data['studied_at'] = $request->studied_at;
            $data['address'] = $request->address;
            $data['gender'] = $request->gender;

            $is_updated = Users::where('id', $this->user->id)->update($data);

            $page_data['user_info'] = Users::where('id', $this->user->id)->first();
            $user_frofile_info = view('frontend.profile.my_info', $page_data)->render();
            $response = array('hideCustomModal' => 1, 'elemSelector' => '#my-profile-info', 'content' => $user_frofile_info, 'alertMessage' => get_phrase('Profile info updated'));
        }

        return json_encode($response);
    }

    function upload_photo($photo_type, Request $request)
    {
        if ($photo_type == 'cover_photo') {
            // Validate the input and return correct response
            $rules = array('cover_photo' => 'mimes:jpeg,jpg,png,gif|required');
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return json_encode(array('validationError' => $validator->getMessageBag()->toArray()));
            }

            $file_name = FileUploader::upload($request->cover_photo,'public/storage/cover_photo', 1120);

            //Update to database
            $data['cover_photo'] = $file_name;
            Users::where('id', $this->user->id)->update($data);

            //Ajax flush message
            Session::flash('success_message', get_phrase('Cover photo updated'));
            return json_encode(array('reload' => 1));
        } else {
            return json_encode(array('alertMessage' => json_encode($request->all())));
        }
    }

    function update_profile(Request $request)
    {

        $rules = array(
            'profile_photo' => 'mimes:jpeg,jpg,png,gif|nullable',
            'name' => 'max:255|required',
            'nickname' => 'max:255|nullable',
            'marital_status' => 'max:255|nullable',
            'phone' => 'max:20|nullable',
            'date_of_birth' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return json_encode(array('validationError' => $validator->getMessageBag()->toArray()));
        }

        if ($request->profile_photo && !empty($request->profile_photo)) {

            $file_name = FileUploader::upload($request->profile_photo,'public/storage/userimage', 800);

            //Create post for updating profile photo
            $this->create_profile_photo_post($request->profile_photo, $file_name);

            //Update to database
            $data['photo'] = $file_name;
        }

 
        $data['name'] = $request->name;
        $data['nickname'] = $request->nickname;
        $data['marital_status'] = $request->marital_status;
        $data['phone'] = $request->phone;
        $data['date_of_birth'] = strtotime($request->date_of_birth);
        Users::where('id', $this->user->id)->update($data);

        //Ajax flush message
        Session::flash('success_message', get_phrase('Profile updated successfully'));
        return json_encode(array('reload' => 1));
    }

    function create_profile_photo_post($image, $file_name)
    {

        FileUploader::upload($image,'public/storage/post/images/'.$file_name, 800);

        $data['user_id'] = $this->user->id;
        $data['privacy'] = 'public';
        $data['publisher'] = 'post';
        $data['publisher_id'] = $this->user->id;
        $data['post_type'] = 'profile_picture';
        $data['tagged_user_ids'] = json_encode(array());
        $data['activity_id'] = 0;
        $data['location'] = '';
        $data['description'] = '';
        $data['status'] = 'active';
        $data['user_reacts'] = json_encode(array());
        $data['created_at'] = time();
        $data['updated_at'] = $data['created_at'];
        $post_id = Posts::insertGetId($data);

        //Stored to media files table 
        $media_file_data = array('user_id' => $this->user->id, 'post_id' => $post_id, 'file_name' => $file_name, 'file_type' => 'image', 'privacy' => 'public');
        $media_file_data['created_at'] = time();
        $media_file_data['updated_at'] = $media_file_data['created_at'];
        Media_files::create($media_file_data);
    }

    function create_cover_photo_post($image, $file_name)
    {

        FileUploader::upload($image,'public/storage/post/images/'.$file_name, 800);

        $data['user_id'] = $this->user->id;
        $data['privacy'] = 'public';
        $data['publisher'] = 'post';
        $data['publisher_id'] = $this->user->id;
        $data['post_type'] = 'cover_photo';
        $data['tagged_user_ids'] = json_encode(array());
        $data['activity_id'] = 0;
        $data['location'] = '';
        $data['description'] = '';
        $data['status'] = 'active';
        $data['user_reacts'] = json_encode(array());
        $data['created_at'] = time();
        $data['updated_at'] = $data['created_at'];
        $post_id = Posts::insertGetId($data);

        //Stored to media files table 
        $media_file_data = array('user_id' => $this->user->id, 'post_id' => $post_id, 'file_name' => $file_name, 'file_type' => 'image', 'privacy' => 'public');
        $media_file_data['created_at'] = time();
        $media_file_data['updated_at'] = $media_file_data['created_at'];
        Media_files::create($media_file_data);
    }



// Album
    public function single_post2($id)
    {

        $album_image = Album_image::where('id', $id)->first();
        $page_data['album_image'] = $album_image;
    
        $page_data['view_path'] = 'frontend.profile.test';
        return view('frontend.index', $page_data);
    }


    public function savePostList(Request $request)
    {
        $user = auth()->user();

        $savedPostsJson = $user->save_post;
        $save_posts_ids = json_decode($savedPostsJson, true) ?? [];

        if (!is_array($save_posts_ids)) {
            $save_posts_ids = [];
        }

        $posts = Posts::whereIn('post_id', $save_posts_ids)
            ->orderBy('created_at', 'desc')
            ->get();

        $friendships = Friendships::where(function ($query) {
            $query->where('accepter', auth()->user()->id)
                ->orWhere('requester', auth()->user()->id);
        })
            ->where('is_accepted', 1)
            ->orderBy('friendships.importance', 'desc')
            ->get();

        $page_data['friendships'] = $friendships;
        $page_data['user_info'] = $this->user;
        $page_data['posts'] = $posts;
        $page_data['type'] = 'user_post';
        $page_data['view_path'] = 'frontend.profile.index';
        
        return view('frontend.index', $page_data);
    }

    public function profileLock()
    {
        $id = auth()->user()->id;

        $user = User::findOrFail($id);
        $user->profile_status = 'lock';
        $user->save();

        Posts::where('user_id', $id)
            ->where('privacy', 'public')
            ->update(['privacy' => 'friends']);

        Session::flash('success_message', get_phrase('Profile locked successfully and privacy updated.'));
        return redirect()->back();
    }

    public function profileUnlock()
    {
        $id = auth()->user()->id;

        $profile_status = User::findOrFail($id);
    
        $profile_status->profile_status = 'unlock';

        $profile_status->save();

        Session::flash('success_message', get_phrase('Profile Unlocked Successfully'));
        return redirect()->back();
    }

    public function checkinsView()
    {

        $user = auth()->user();

        $posts = Posts::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $friendships = Friendships::where(function ($query) {
            $query->where('accepter', auth()->user()->id)
                ->orWhere('requester', auth()->user()->id);
        })
            ->where('is_accepted', 1)
            ->orderBy('friendships.importance', 'desc')
            ->get();

        $page_data['friendships'] = $friendships;
        $page_data['user_info'] = $this->user;
        $page_data['posts'] = $posts;
        $page_data['type'] = 'user_post';
        $page_data['view_path'] = 'frontend.profile.index';
        return view('frontend.index', $page_data);
    }

}
