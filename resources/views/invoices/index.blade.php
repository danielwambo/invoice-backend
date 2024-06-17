@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Invoices</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Amount</th>
                <th>Payment Status</th>
                <th>Transaction ID</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
            <tr>
                <td>{{ $invoice->description }}</td>
                <td>{{ $invoice->amount }}</td>
                <td>{{ $invoice->payment_status }}</td>
                <td>{{ $invoice->transaction_id }}</td>
                <td>
                    <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-primary">View</a>
                </td>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
