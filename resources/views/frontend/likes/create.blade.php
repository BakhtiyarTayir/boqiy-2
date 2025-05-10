<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ get_phrase('Create New Like Type') }}</h5>
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

                    <form action="{{ route('likes.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-group mb-3">
                            <label for="name">{{ get_phrase('Name') }}</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="price">{{ get_phrase('Price') }}</label>
                            <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}" step="0.01" min="0" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="animation">{{ get_phrase('Animation (GIF, MP4, WEBM)') }}</label>
                            <input type="file" class="form-control" id="animation" name="animation" accept=".gif,.mp4,.webm" required>
                            <small class="form-text text-muted">{{ get_phrase('Maximum file size: 5MB') }}</small>
                        </div>
                        
                        <div class="form-group text-end">
                            <a href="{{ route('likes.index') }}" class="btn btn-secondary">{{ get_phrase('Cancel') }}</a>
                            <button type="submit" class="btn btn-primary">{{ get_phrase('Create') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>