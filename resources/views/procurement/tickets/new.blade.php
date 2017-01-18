@extends('layouts.app')

@section('title', trans('procurement_ticket.new_title'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h2>{{ trans('procurement_ticket.new_title') }}</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{ trans('procurement_ticket.ticket') }}
                    </div>

                    <div class="panel-body">
                        <div class="row">
                            <div v-if="errors.length > 0" class="col-md-12">
                                <div class="alert alert-warning">
                                    <ul v-for="error in errors">
                                        <li v-for="message in error">@{{ message }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <form class="form-inline">
                                    <div class="form-group">
                                        <label class="sr-only" for="new-item-url">{{ trans('procurement_ticket.field_url') }}</label>
                                        <input type="url" class="form-control" id="new-item-url" v-model="form.url" placeholder="{{ trans('procurement_ticket.field_url') }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only" for="new-item-name">{{ trans('procurement_ticket.field_product_name') }}</label>
                                        <input type="url" class="form-control" id="new-item-name" v-model="form.title" placeholder="{{ trans('procurement_ticket.field_product_name') }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only" for="new-item-note">{{ trans('procurement_ticket.field_note') }}</label>
                                        <input type="url" class="form-control" id="new-item-note" v-model="form.note" placeholder="{{ trans('procurement_ticket.field_note') }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only" for="new-item-price">{{ trans('procurement_ticket.field_price') }}</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">¥</div>
                                            <input type="url" class="form-control" id="new-item-price" v-model="form.price" placeholder="{{ trans('procurement_ticket.field_price') }}">
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary" v-on:click="pushItem">{{ trans('procurement_ticket.button_add') }}</button>
                                </form>
                            </div>

                            <div class="col-md-12">
                                <hr>
                            </div>

                            <div class="col-md-12">
                                <table class="table table-striped">
                                    <thead><tr>
                                        <td>#</td>
                                        <td>{{ trans('procurement_ticket.field_product') }}</td>
                                        <td class="text-right">{{ trans('procurement_ticket.field_price') }}</td>
                                        <td>{{ trans('procurement_ticket.field_note') }}</td>
                                        <td>{{ trans('procurement_ticket.field_extra_services') }}</td>
                                    </tr></thead>

                                    <tbody v-if="items.length > 0">
                                    <tr v-for="(item, index) in items">
                                        <td>@{{ index + 1 }}</td>
                                        <td>@{{ item.title }}<br>
                                            <small>
                                                <a v-bind:href="item.url" target="_blank">@{{ item.url }}</a>
                                            </small>
                                        </td>
                                        <td class="text-right">@{{ format(item.price) }}</td>
                                        <td>@{{ item.note }}</td>
                                        <td>
                                            @foreach ($extraServices as $service)
                                                <label>
                                                    <input type="checkbox" v-model="items[index].extraServices[{{ $service->id }}]">
                                                    {{ $service->name }} (NT${{$service->price}})
                                                </label>
                                                <br>
                                            @endforeach
                                        </td>
                                    </tr>
                                    </tbody>

                                    <tfoot v-if="items.length > 0"><tr>
                                        <td></td>
                                        <td class="text-right">{{ trans('procurement_ticket.field_total') }}</td>
                                        <td class="text-right">@{{ summary() }}</td>
                                        <td></td>
                                        <td></td>
                                    </tr></tfoot>
                                    <tfoot v-else><tr>
                                        <td colspan="5" class="text-center">{{ trans('procurement_ticket.new_no_item_text') }}</td>
                                    </tr></tfoot>
                                </table>
                            </div>

                            <div class="col-md-12">
                                <hr>
                            </div>

                            <div class="col-md-8">
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="ticket-contact-name">{{ trans('procurement_ticket.field_name') }}</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="ticket-contact-name" v-model="customer.name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="ticket-contact-email">{{ trans('procurement_ticket.field_email') }}</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="ticket-contact-email" v-model="customer.email">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="ticket-contact-contact">{{ trans('procurement_ticket.field_contact') }}</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="ticket-contact-contact" v-model="customer.contact">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="ticket-contact-shipment">{{ trans('procurement_ticket.field_shipment_method') }}</label>
                                        <div class="col-sm-10">
                                            <select id="ticket-contact-shipment" v-model="shipment" class="form-control">
                                                @foreach ($shipments as $shipment)
                                                    @if ($shipment->show)
                                                        <option value="{{ $shipment->id }}">{{ $shipment->name }}: NT${{ $shipment->price }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="ticket-contact-note">{{ trans('procurement_ticket.field_note') }}</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" id="ticket-contact-note" v-model="note"></textarea>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="col-md-4">

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 col-md-offset-8">
                                <button type="button" v-on:click="store()" class="btn btn-block btn-success">{{ trans('procurement_ticket.button_send') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script>
        var ExtraServices = {!! json_encode($extraServices) !!};
    </script>
    <script src="{{ elixir('js/procurement-tickets-new.js') }}"></script>
@endsection
