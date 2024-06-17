@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Invoice Details</h1>
    <table class="table">
        <tr>
            <th>Description:</th>
            <td>{{ $invoice->description }}</td>
        </tr>
        <tr>
            <th>Amount:</th>
            <td>{{ $invoice->amount }}</td>
        </tr>
        <tr>
            <th>Payment Status:</th>
            <td>{{ $invoice->payment_status }}</td>
        </tr>
        <tr>
            <th>Transaction ID:</th>
            <td>{{ $invoice->transaction_id }}</td>
        </tr>
    </table>
    <form action="{{ route('invoices.pay', $invoice->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="text" name="phone" id="phone" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Initiate Payment</button>
    </form>
    <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Back to Invoices</a>
</div>
@endsection
