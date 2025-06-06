<link rel="stylesheet" href="{{ asset('assets/frontend/css/nice-select.css') }}">
@php
    $page = \App\Models\Page::find($page_id);
@endphp
<form class="ajaxForm ng_form_entry" action="{{ route('page.update',$page->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="#">{{ get_phrase('Page Name') }}</label>
        <input type="text" class="border-0 bg-secondary" name="name" value="{{ $page->title }}" placeholder="Enter your page Name">
    </div>

    <div class="form-group">
        <label for="#">{{ get_phrase('Page BIO') }}</label>
        <textarea class="border-0 bg-secondary content" name="description" id="description" rows="5" placeholder="Description">@php echo script_checker($page->description, false); @endphp</textarea>
    </div>
    <div>
        <label for="">{{ get_phrase('Previous Profile Photo') }}</label> <br>
        <img src="{{ get_page_logo($page->logo, 'logo') }}" alt="">
    </div>
    <div class="form-group">
        <label for="#">{{ get_phrase('Update Profile Photo') }}</label>
        <input type="file" name="image" id="image" class="form-control border-0 bg-secondary">
    </div>
    <div class="form-group gt_groups">
        <label for="#">{{ get_phrase('Page Category') }}</label>
        <select name="category" id="category" class="form-control select border-0 bg-secondary">
            <option value="" selected>{{ get_phrase('Select Category') }}</option>
            @foreach (\App\Models\Pagecategory::all() as $category )
                <option value="{{ $category->id }}" {{ $category->id == $page->category_id ? "selected":"" }}>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="mt-12 w-100 btn common_btn">{{ get_phrase('Edit Page') }}</button>
</form>
@include('frontend.initialize')
<script src="{{ asset('assets/frontend/js/jquery.nice-select.min.js') }}"></script>
<script>
    $('document').ready(function(){
        $(".select").niceSelect();
    });
</script>