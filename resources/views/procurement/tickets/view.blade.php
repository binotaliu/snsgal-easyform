@extends('layouts.app')

@section('title', 'Procurement Ticket')

@section('content')
    <div class="container">
        <div class="col-md-12">
            <h3><span class="label label-primary">{{ $ticket_status[$ticket->status] }}</span> Procurement Ticket</h3>
        </div>

        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Items
                </div>

                <div class="panel-body">
                    <table class="table table-striped">
                        <thead><tr class="active">
                            <th width="20" class="text-center">#</th>
                            <th>Category</th>
                            <th>Product</th>
                            <th width="110">Extra Services</th>
                            <th width="100" class="text-right">Price (Yen)</th>
                            <th width="110" class="text-right table-price">Price (TWD)</th>
                            <th width="240">Note</th>
                        </tr></thead>

                        <tbody>
                            @foreach ($ticket->items as $i => $item)
                                <tr>
                                    <td class="text-center">{{ $i + 1 }}</td>
                                    <td>{{ $item->category->name }}</td>
                                    <td>
                                        <span class="label label-primary">{{ $item_status[$item->status] }}</span>
                                        {{ $item->title }}<br>
                                        <small>
                                            <a href="{{ $item->url }}">{{ $item->url }}</a>
                                        </small>
                                    </td>
                                    <td>None</td>
                                    <td class="text-right">¥{{ Format::number($item->price, 0, '.', ',') }}</td>
                                    <td class="text-right table-price">NT${{ Format::number($item->price * $ticket->rate, 2, '.', ',') }}</td>
                                    <td>{{ $item->note }}</td>
                                </tr>
                            @endforeach

                            {{-- Shipments --}}
                            <tr class="active">
                                <th></th>
                                <th colspan="3">In-Japan Shipment</th>
                                <th class="text-right">Price (Yen)</th>
                                <th class="text-right table-price">Price (TWD)</th>
                                <th>Note</th>
                            </tr>
                            @foreach ($ticket->japanShipments as $item)
                                <tr>
                                    <td></td>
                                    <td colspan="3">
                                        {{ $item->title }}<br>
                                        <small>
                                            <a href="{{ $item->url }}">{{ $item->url }}</a>
                                        </small>
                                    </td>
                                    <td class="text-right">¥{{ Format::number($item->price, 0, '.', ',') }}</td>
                                    <td class="text-right table-price">NT${{ Format::number($item->price * $ticket->rate, 2, '.', ',') }}</td>
                                    <td>{{ $item->note }}</td>
                                </tr>
                            @endforeach

                            {{-- Total --}}
                            <tr class="active">
                                <th></th>
                                <th colspan="6">Total</th>
                            </tr>
                            @foreach ($ticket->totals as $item)
                                <tr>
                                    <td colspan="5" class="text-right">{{ $item['name'] }}</td>
                                    <td class="table-price text-right">
                                            NT${{ Format::number($item['price'], 2, '.', ',') }}
                                    </td>
                                    <td>
                                        <small>
                                            {{ $item['note'] }}
                                        </small>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="5" class="text-right h4">
                                    <strong>
                                        Total
                                    </strong>
                                </td>
                                <td class="table-price text-right h4">
                                    <strong>NT${{ Format::number($ticket->total, 0, '.', ',') }}</strong>
                                </td>
                                <td></td>
                            </tr>
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

                <div class="panel-heading">
                    Note
                </div>

                <div class="panel-body">
                    {{ $ticket->note }}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Ticket Information
                </div>

                <div class="panel-body">
                    Rate: {{ $ticket->rate }} <br>
                    Created at {{ $ticket->created_at }} <br>
                    Last update at {{ $ticket->updated_at }} <br>
                </div>
            </div>
        </div>
    </div>
@endsection
