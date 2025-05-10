<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ get_phrase('Likes Balance') }}</h5>
                    <a href="{{ route('like_balance.topup') }}" class="btn btn-primary btn-sm">{{ get_phrase('Top Up Balance') }}</a>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <div class="text-center mb-4">
                        <h2>{{ $userLikeBalance ? number_format($userLikeBalance->balance, 2) : 0 }}</h2>
                        <p class="text-muted">{{ get_phrase('Current Likes Balance') }}</p>
                    </div>
                    
                    <div class="p-4 bg-light rounded shadow-sm text-center">
                        @if (!empty($todayUserTransactions) && !empty($todayAllAmount))
                            <h4>Bugun</h4>
                            <h5 class="text-success fw-bold mb-3">
                                {{ $todayUserTransactions }} ota-ona va ziyoli insonlar bolalarning bilimini qo‘llab-quvvatladi!
                            </h5>
                            <p class="fs-5 text-dark mb-2">
                                <strong>{{ $todayAllAmount }} so‘m</strong> like xarididan tushgan mablag‘ to‘liq
                                <span class="text-primary fw-semibold">bepul sovg‘alarga</span> sarflanadi.
                            </p>
                            <p class="fs-5 text-muted fst-italic">
                                Har bir like – bilimga qo‘shilgan qiymat!
                            </p>
                            <div class="mt-3">
                                <span class="btn2 text-success btn-lg">
                                    Siz ham qo‘shiling – farzandingiz bilimini rag‘batlantiring!
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ get_phrase('Transaction History') }}</h5>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>{{ get_phrase('Date') }}</th>
                                <th>{{ get_phrase('Type') }}</th>
                                <th>{{ get_phrase('Description') }}</th>
                                <th class="text-end">{{ get_phrase('Amount') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->created_at->format('d.m.Y H:i') }}</td>
                                    <td>
                                        @if($transaction->type == 'deposit')
                                            <span class="badge bg-success">{{ get_phrase('Deposit') }}</span>
                                        @elseif($transaction->type == 'like_purchase')
                                            <span class="badge bg-primary">{{ get_phrase('Like Purchase') }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $transaction->type }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $transaction->description }}</td>
                                    <td class="text-end {{ $transaction->amount > 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $transaction->amount > 0 ? '+' : '' }}{{ number_format($transaction->amount, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">{{ get_phrase('No transactions') }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-4">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>