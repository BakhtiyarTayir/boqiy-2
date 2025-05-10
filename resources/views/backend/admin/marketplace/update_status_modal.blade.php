@php
    $order = \App\Models\MarketplaceOrder::find($order_id);
@endphp

<!-- Order Status Update Modal -->
<form action="{{ route('admin.marketplace.order.update_status', $order_id) }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="status" class="form-label">{{ get_phrase('Order Status') }}</label>
        <select class="form-select" name="status" id="status" required>
            <option value="">{{ get_phrase('Select Status') }}</option>
            <option value="pending" @if(isset($order) && $order->status == 'pending') selected @endif>{{ get_phrase('Pending') }}</option>
            <option value="completed" @if(isset($order) && $order->status == 'completed') selected @endif>{{ get_phrase('Completed') }}</option>
            <option value="cancelled" @if(isset($order) && $order->status == 'cancelled') selected @endif>{{ get_phrase('Cancelled') }}</option>
        </select>
    </div>
    
    <div class="text-center">
        <button type="submit" class="btn btn-primary">{{ get_phrase('Update Status') }}</button>
    </div>
</form> 