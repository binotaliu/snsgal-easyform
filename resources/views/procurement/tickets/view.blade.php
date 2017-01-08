@extends('layouts.app')

@section('title', 'View Procurement Ticket')

@section('content')
    <div class="container">
        <div class="col-sm-12">
            <h3>View Procurement Ticket</h3>
        </div>

        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Items
                </div>

                <div class="panel-body">
                    <table class="table table-striped table-bordered">
                        <thead><tr>
                            <td>#</td>
                            <td>Product</td>
                            <td>Price (Yen)</td>
                            <td>Price (TWD)</td>
                            <td>Note</td>
                            <td>Extra Services</td>
                        </tr></thead>

                        <tbody>
                            @foreach ($ticket->items as $i => $item)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $item->title }}<br>
                                        <small><a href="{{ $item->url }}">{{ $item->url }}</a></small></td>
                                    <td>¥{{ Format::number($item->price, 0, '.', ',') }}</td>
                                    <td>NT${{ Format::number($item->price * $rate, 2, '.', ',') }}</td>
                                    <td>{{ $item->note }}</td>
                                    <td></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Customer
                </div>

                <div class="panel-body">
                    {{ $ticket->name }} &lt;{{ $ticket->email }}&gt;<br>
                    {{ $ticket->contact }}<br>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Note
                </div>

                <div class="panel-body">
                    {{ $ticket->note }}
                </div>
            </div>
        </div>
    </div>
@endsection
