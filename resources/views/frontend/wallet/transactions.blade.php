<div class="profile-wrap">
    <div class="profile-content mt-3">
        <div class="row gx-3">
            <div class="col-lg-12 col-sm-12">
                <!-- Transactions Card -->
                <div class="card wallet-card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fas fa-history mr-2"></i> {{ get_phrase('Wallet Transactions') }}</h4>
                        <a href="{{ route('wallet.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> {{ get_phrase('Back to Wallet') }}
                        </a>
                    </div>
                    <div class="card-body">
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
                                            @if($transaction->type == 'video_post_creation')
                                                <span class="badge badge-success">{{ get_phrase('Video Post') }}</span>
                                            @elseif($transaction->type == 'post_creation')
                                                <span class="badge badge-info">{{ get_phrase('Post Creation') }}</span>
                                            @elseif($transaction->type == 'deposit')
                                                <span class="badge badge-primary">{{ get_phrase('Deposit') }}</span>
                                            @elseif($transaction->type == 'withdrawal')
                                                <span class="badge badge-warning">{{ get_phrase('Withdrawal') }}</span>
                                            @else
                                                <span class="badge badge-secondary">{{ ucfirst(str_replace('_', ' ', $transaction->type)) }}</span>
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
                        @else
                        <div class="alert alert-info">
                            {{ get_phrase('You have no transactions yet. Create a post to earn credits!') }}
                        </div>
                        @endif
                    </div>
                </div>
                <!-- End Transactions Card -->
            </div>
        </div>
    </div>
</div>

<style>
.wallet-card {
    border-radius: 15px;
    overflow: hidden;
}
.badge {
    padding: 0.5em 0.75em;
    font-size: 85%;
}
.badge-success {
    background-color: #28a745;
    color: white;
}
.badge-info {
    background-color: #17a2b8;
    color: white;
}
.badge-primary {
    background-color: #007bff;
    color: white;
}
.badge-warning {
    background-color: #ffc107;
    color: #212529;
}
.badge-secondary {
    background-color: #6c757d;
    color: white;
}
.text-success {
    color: #28a745 !important;
}
.text-danger {
    color: #dc3545 !important;
}
</style> 