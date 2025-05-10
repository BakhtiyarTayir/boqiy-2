<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ get_phrase('Edit Like Type') }}</h5>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('likes.update', $like->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group mb-3">
                            <label for="name">{{ get_phrase('Name') }}</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $like->name) }}" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="price">{{ get_phrase('Price') }}</label>
                            <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $like->price) }}" step="0.01" min="0" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label>{{ get_phrase('Current Animation') }}</label>
                            <div class="my-2">
                                @if(Str::endsWith($like->animation_path, '.gif') || Str::contains($like->animation_path, 'giphy.com'))
                                    <img src="{{ $like->animation_path }}" alt="{{ $like->name }}" class="img-fluid" style="max-height: 100px;">
                                @else
                                    <video autoplay loop muted class="img-fluid" style="max-height: 100px;">
                                        <source src="{{ $like->animation_path }}" type="video/mp4">
                                        {{ get_phrase('Your browser does not support the video') }}
                                    </video>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="animation">{{ get_phrase('New Animation (GIF, MP4, WEBM)') }}</label>
                            <input type="file" class="form-control" id="animation" name="animation" accept=".gif,.mp4,.webm">
                            <small class="form-text text-muted">{{ get_phrase('Leave empty if you don\'t want to change the animation. Maximum file size: 5MB') }}</small>
                        </div>
                        
                        <div class="form-group mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" {{ $like->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    {{ get_phrase('Active') }}
                                </label>
                            </div>
                        </div>
                        
                        <div class="form-group text-end">
                            <a href="{{ route('likes.index') }}" class="btn btn-secondary">{{ get_phrase('Cancel') }}</a>
                            <button type="submit" class="btn btn-primary">{{ get_phrase('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>