<link rel="stylesheet" href="{{ asset('assets/frontend/css/nice-select.css') }}">
<form class="ajaxForm  ng_form_entry" action="{{ route('page.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="#">{{ get_phrase('Page Name') }}</label>
        <input type="text" class="border-0 bg-secondary" name="name" required placeholder="Enter your page Name">
    </div>
   
    <div class="form-group">
        <label for="#">{{ get_phrase('Page BIO') }}</label>
        <textarea  name="description" class="border-0 bg-secondary content" id="description" rows="5" placeholder="Description"></textarea>
    </div>
   
    <div class="form-group">
        <label for="#">{{ get_phrase('Profile Photo') }}</label>
        <input type="file" name="image" class="border-0 bg-secondary" id="image" class="form-control">
    </div>
    <div class="form-group gt_groups">
        <label for="#" >{{ get_phrase('Page Category') }}</label>
        <select name="category" id="category" class="form-control select border-0 bg-secondary" required>
            <option value="">{{ get_phrase('Select Category') }}</option>
            @foreach (\App\Models\Pagecategory::all() as $category )
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="w-100 mt-12 btn common_btn">{{ get_phrase('Create Page') }}</button>
</form>


@include('frontend.initialize')
<script src="{{ asset('assets/frontend/js/jquery.nice-select.min.js') }}"></script>
<script>
    $('document').ready(function(){
        $(".select").niceSelect();
    });
</script>