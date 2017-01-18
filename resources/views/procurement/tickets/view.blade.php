@extends('layouts.app')

@section('title', trans('procurement_ticket.ticket'))

@section('content')
    <div class="container">
        <div class="col-md-12">
            <h3><span class="label label-{{ $ticket_status[$ticket->status]['color'] }}">{{ $ticket_status[$ticket->status]['name'] }}</span> {{  trans('procurement_ticket.ticket') }}</h3>
        </div>

        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('procurement_ticket.field_items') }}
                </div>

                <div class="panel-body">
                    <table class="table table-striped">
                        <thead><tr class="active">
                            <th width="20" class="text-center">#</th>
                            <th width="60" class="text-center">{{ trans('procurement_ticket.field_category') }}</th>
                            <th>{{ trans('procurement_ticket.field_product') }}</th>
                            <th width="110" class="text-right">{{ trans('procurement_ticket.field_price_yen') }}</th>
                            <th width="110" class="text-right table-price">{{ trans('procurement_ticket.field_price_twd') }}</th>
                            <th width="240">{{ trans('procurement_ticket.field_note') }}</th>
                        </tr></thead>

                        <tbody>
                            @foreach ($ticket->items as $i => $item)
                                <tr>
                                    <td class="text-center">{{ $i + 1 }}</td>
                                    <td class="text-center">{{ $item->category->name }}</td>
                                    <td>
                                        <span class="label label-{{ $item_status[$item->status]['color'] }}">{{ $item_status[$item->status]['name'] }}</span>
                                        {{ $item->title }}<br>
                                        <small>
                                            <a href="{{ $item->url }}">{{ $item->url }}</a>
                                        </small>
                                    </td>
                                    <td class="text-right">¥{{ Format::number($item->price, 0, '.', ',') }}</td>
                                    <td class="text-right table-price">NT${{ Format::number($item->price * $ticket->rate, 2, '.', ',') }}</td>
                                    <td>{{ $item->note }}</td>
                                </tr>
                                @if ($item->extraServices)
                                    @foreach ($item->extraServices as $service)
                                        <tr class="table-extra-service">
                                            <td colspan="2"></td>
                                            <td colspan="2">- {{ $service->name }}</td>
                                            <td class="text-right table-price">NT${{ Format::number($service->price, 2, '.', ',') }}</td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach

                            {{-- Shipments --}}
                            <tr class="active">
                                <th></th>
                                <th colspan="2">{{ trans('procurement_ticket.field_japan_shipment') }}</th>
                                <th class="text-right">{{ trans('procurement_ticket.field_price_yen') }}</th>
                                <th class="text-right table-price">{{ trans('procurement_ticket.field_price_twd') }}</th>
                                <th>{{ trans('procurement_ticket.field_note') }}</th>
                            </tr>
                            @foreach ($ticket->japanShipments as $item)
                                <tr>
                                    <td></td>
                                    <td colspan="2">
                                        {{ $item->title }}
                                    </td>
                                    <td class="text-right">¥{{ Format::number($item->price, 0, '.', ',') }}</td>
                                    <td class="text-right table-price">NT${{ Format::number($item->price * $ticket->rate, 2, '.', ',') }}</td>
                                    <td>{{ $item->note }}</td>
                                </tr>
                            @endforeach

                            {{-- Total --}}
                            <tr class="table-total">
                                <td colspan="4"></td>
                                <td colspan="2" class="table-price"></td>
                            </tr>
                            @foreach ($ticket->totals as $item)
                                <?php if ($item->price == 0) continue; ?>
                                <tr>
                                    <td colspan="4" class="text-right">{{ $item['name'] }}</td>
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
                                <td colspan="4" class="text-right h4">
                                    <strong>
                                        {{ trans('procurement_ticket.field_total') }}
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
                    {{ trans('procurement_ticket.field_customer') }}
                </div>

                <div class="panel-body">
                    {{ $ticket->name }} &lt;{{ $ticket->email }}&gt;<br>
                    {{ $ticket->contact }}<br>
                </div>

                <div class="panel-heading">
                    {{ trans('procurement_ticket.field_note') }}
                </div>

                <div class="panel-body">
                    {{ $ticket->note }}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('procurement_ticket.field_ticket_information') }}
                </div>

                <div class="panel-body">
                    {{ trans('procurement_ticket.field_rate') }}: {{ $ticket->rate }} <br>
                    {{ trans('procurement_ticket.field_created_at') }}{{ $ticket->created_at }} <br>
                    {{ trans('procurement_ticket.field_updated_at') }}{{ $ticket->updated_at }} <br>
                </div>
            </div>
        </div>
    </div>
@endsection
