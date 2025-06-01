<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ get_phrase('Available Like Types') }}</h5>
                    @if(Auth::user()->user_role === 'admin')
                    <a href="{{ route('likes.create') }}" class="btn btn-primary btn-sm">{{ get_phrase('Add New Like Type') }}</a>
                    @endif
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row">
                        @forelse($likes as $like)
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $like->name }}</h5>
                                        <div class="my-3">
                                            @if(Str::endsWith($like->animation_path, '.gif') || Str::contains($like->animation_path, 'giphy.com'))
                                                <img src="{{ $like->image_url }}" alt="{{ $like->name }}" class="img-fluid" style="max-height: 100px;">
                                            @else
                                                <video autoplay loop muted class="img-fluid" style="max-height: 100px;">
                                                    <source src="{{ $like->image_url }}" type="video/mp4">
                                                    {{ get_phrase('Your browser does not support the video') }}
                                                </video>
                                            @endif
                                        </div>
                                        <p class="card-text">{{ get_phrase('Price') }}: {{ $like->price }}</p>
                                        
                                        @if(Auth::user()->user_role === 'admin')
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('likes.edit', $like->id) }}" class="btn btn-sm btn-primary">{{ get_phrase('Edit') }}</a>
                                                <form action="{{ route('likes.destroy', $like->id) }}" method="POST" onsubmit="return confirm('{{ get_phrase('Are you sure you want to delete this like type?') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">{{ get_phrase('Delete') }}</button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info">
                                    {{ get_phrase('No available like types') }}
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>