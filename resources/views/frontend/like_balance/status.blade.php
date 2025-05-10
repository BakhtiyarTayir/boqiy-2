<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ get_phrase('Payment Status') }}</h5>
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
                        @if($transaction->status == 'completed')
                            <div class="mb-3">
                                <i class="fas fa-check-circle text-success" style="font-size: 48px;"></i>
                            </div>
                            <h4 class="text-success">{{ get_phrase('Payment Successful') }}</h4>
                            <p>{{ get_phrase('Your balance has been topped up successfully!') }}</p>
                        @elseif($transaction->status == 'pending')
                            <div class="mb-3">
                                <i class="fas fa-clock text-warning" style="font-size: 48px;"></i>
                            </div>
                            <h4 class="text-warning">{{ get_phrase('Payment Pending') }}</h4>
                            <p>{{ get_phrase('Your payment is being processed. Please check back later.') }}</p>
                        @else
                            <div class="mb-3">
                                <i class="fas fa-times-circle text-danger" style="font-size: 48px;"></i>
                            </div>
                            <h4 class="text-danger">{{ get_phrase('Payment Failed') }}</h4>
                            <p>{{ get_phrase('Your payment could not be processed. Please try again.') }}</p>
                        @endif
                    </div>

                    <div class="transaction-details">
                        <h6>{{ get_phrase('Transaction Details') }}</h6>
                        <table class="table">
                            <tr>
                                <th>{{ get_phrase('Order ID') }}</th>
                                <td>{{ $transaction->order_id }}</td>
                            </tr>
                            <tr>
                                <th>{{ get_phrase('Amount') }}</th>
                                <td>{{ number_format($transaction->amount, 0) }} {{ get_phrase('sum') }}</td>
                            </tr>
                            <tr>
                                <th>{{ get_phrase('Date') }}</th>
                                <td>{{ date('d M Y, H:i', strtotime($transaction->created_at)) }}</td>
                            </tr>
                            <tr>
                                <th>{{ get_phrase('Status') }}</th>
                                <td>
                                    @if($transaction->status == 'completed')
                                        <span class="badge bg-success">{{ get_phrase('Completed') }}</span>
                                    @elseif($transaction->status == 'pending')
                                        <span class="badge bg-warning">{{ get_phrase('Pending') }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ get_phrase('Failed') }}</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('like_balance.index') }}" class="btn btn-primary">{{ get_phrase('Back to Balance') }}</a>
                        @if($transaction->status != 'completed')
                            <a href="{{ route('like_balance.topup') }}" class="btn btn-outline-primary ms-2">{{ get_phrase('Try Again') }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 