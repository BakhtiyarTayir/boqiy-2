<div class="profile-wrap">
    <div class="profile-cover bg-white">
        <div class="profile-header" style="background-image: url('{{ get_cover_photo($user->cover_photo) }}');">
           <div class="cover-btn-group">
                <button
                onclick="showCustomModal('{{ route('load_modal_content', ['view_path' => 'frontend.profile.edit_profile']) }}', '{{ get_phrase('Edit your profile') }}');"
                class="edit-cover btn " data-bs-toggle="modal" data-bs-target="#edit-profile"><i
                    class="fa fa-pencil"></i>{{ get_phrase('Edit Profile') }}</button>
                <button  onclick="showCustomModal('{{ route('load_modal_content', ['view_path' => 'frontend.profile.edit_cover_photo']) }}', '{{ get_phrase('Update your cover photo') }}');"
                    class="edit-cover btn n_edit"><i class="fa fa-camera"></i>{{ get_phrase('Edit Cover Photo') }}</button>
            </div>
        </div>
            <div class="n_profile_tab">
                 <div class="n_main_tab">
                    <div class="profile-avatar d-flex align-items-center">
                        <div class="avatar avatar-xl"><img class="rounded-circle"
                                src="{{ get_user_image($user->photo, 'optimized') }}" alt=""></div>
                        <div class="avatar-details">
                            <h3 class="n_font">{{ $user->name }}</h3>
                            @if($user->profile_status == 'lock')
                            <span class="lock_shield"><i class="fa-solid fa-shield"></i> {{get_phrase('You locked your profile')}}</span>
                            @endif
                        </div>
                    </div>
                    <div class="n_tab_follow ">
                        @php
                        $friends = DB::table('friendships')
                            ->where(function ($query) {
                                $query->where('accepter', Auth()->user()->id)->orWhere('requester', Auth()->user()->id);
                            })
                            ->where('is_accepted', 1)
                            ->orderBy('friendships.importance', 'desc');
                        @endphp
                    </div>
                 </div>
            </div>
    </div>
    
    <div class="profile-content mt-3">
        <div class="row gx-3">
            <div class="col-lg-12 col-sm-12">
                <!-- Wallet Card -->
                <div class="card wallet-card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-wallet mr-2"></i> {{ get_phrase('My Wallet') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="wallet-balance text-center mb-4">
                            <h2 class="balance-value">{{ number_format($wallet->balance, 2) }}</h2>
                            <p class="text-muted">{{ get_phrase('Wallet Balance') }}</p>
                        </div>
                        
                        <div class="wallet-info mb-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-card bg-light p-3 rounded">
                                        <i class="fas fa-coins text-warning"></i>
                                        <h5>{{ get_phrase('How to earn') }}</h5>
                                        <p>{{ get_phrase('Get credits for creating posts in the social network') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-card bg-light p-3 rounded">
                                        <i class="fas fa-shopping-cart text-success"></i>
                                        <h5>{{ get_phrase('How to use') }}</h5>
                                        <p>{{ get_phrase('Get products with credits') }}</p>
                                        <a href="{{ route('allproducts') }}" class="btn btn-primary">{{ get_phrase('Marketplace') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="recent-transactions">
                            <h5 class="mb-3"><i class="fas fa-history mr-2"></i> {{ get_phrase('Recent Transactions') }}</h5>
                            
                            @if(count($transactions) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>{{ get_phrase('Date') }}</th>
                                            <th>{{ get_phrase('Type') }}</th>
                                            <th>{{ get_phrase('Description') }}</th>
                                            <th class="text-right">{{ get_phrase('Amount') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transactions as $transaction)
                                        <tr>
                                            <td>{{ $transaction->created_at->format('d.m.Y H:i') }}</td>
                                            <td>
                                                @if($transaction->type == 'post_creation')
                                                    <span class="badge badge-success">{{ get_phrase('Post Creation') }}</span>
                                                @elseif($transaction->type == 'deposit')
                                                    <span class="badge badge-primary">{{ get_phrase('Deposit') }}</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ $transaction->type }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $transaction->description }}</td>
                                            <td class="text-right {{ $transaction->amount > 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $transaction->amount > 0 ? '+' : '' }}{{ number_format($transaction->amount, 2) }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="d-flex justify-content-center mt-4">
                                {{ $transactions->links() }}
                            </div>
                            
                            <div class="text-center mt-3">
                                <a href="{{ route('wallet.transactions') }}" class="btn btn-outline-primary">{{ get_phrase('View All Transactions') }}</a>
                            </div>
                            @else
                            <div class="alert alert-info">
                                {{ get_phrase('You have no transactions yet. Create a post to earn credits!') }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- End Wallet Card -->
                
               

                @include('frontend.main_content.scripts')
            </div>
        </div>
    </div>
</div>

@include('frontend.profile.scripts')

<style>
.wallet-card {
    border-radius: 15px;
    overflow: hidden;
}
.wallet-balance {
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 10px;
}
.balance-value {
    font-size: 3rem;
    font-weight: bold;
    color: #28a745;
}
.info-card { 
    height: 100%;
    border-radius: 10px;
    transition: all 0.3s;
}
.info-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.info-card i {
    font-size: 2rem;
    margin-bottom: 10px;
}
</style>

