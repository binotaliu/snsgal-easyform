@extends('layouts.app')

@section('title', trans('request.list_title'))

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <h2>{{ trans('request.list_title') }}</h2>
            </div>
            <div class="col-sm-6">
                <p class="h3"></p>
                <div class="text-right">
                    <div class="btn-group">
                        <button type="button" v-on:click="showCreate()" class="btn btn-primary">
                            <i class="fa fa-plus"></i> {{ trans('request.create_btn') }}
                        </button>
                        <button type="button" v-on:click="showSender()" class="btn btn-default">
                            <i class="fa fa-address-card"></i> {{ trans('request.profile_btn') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{ trans('request.list_title') }}
                    </div>

                    <div class="panel-body">
                        <div class="clearfix"></div>

                        <div class="row">
                            <div class="col-sm-3 form-group">
                                <label class="control-label">{{ trans('request.filter_title') }}</label>
                                <input type="text" class="form-control" v-model="filter.title" placeholder="{{ trans('request.filter_title_placeholder') }}">
                            </div>
                            <div class="col-sm-3 form-group">
                                <label class="control-label">{{ trans('request.filter_method') }}</label>
                                <select class="form-control" v-model="filter.method">
                                    <option value="all">{{ trans('request.filter_method_all') }}</option>
                                    <option value="cvs">{{ trans('request.filter_method_cvs') }}</option>
                                    <option value="standard">{{ trans('request.filter_method_standard') }}</option>
                                </select>
                            </div>
                            <div class="col-sm-3 form-group">
                                <label class="control-label">{{ trans('request.filter_responded?') }}</label>
                                <select class="form-control" v-model="filter.responded">
                                    <option value="-1">{{ trans('request.filter_responded_all') }}</option>
                                    <option value="true">{{ trans('request.filter_responded_true') }}</option>
                                    <option value="false">{{ trans('request.filter_responded_false') }}</option>
                                </select>
                            </div>
                            <div class="col-sm-3 form-group">
                                <label class="control-label">{{ trans('request.filter_exported?') }}</label>
                                <select class="form-control" v-model="filter.exported">
                                    <option value="-1">{{ trans('request.filter_exported_all') }}</option>
                                    <option value="true">{{ trans('request.filter_exported_true') }}</option>
                                    <option value="false">{{ trans('request.filter_exported_false') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-striped">
                                    <thead><tr>
                                        <th width="50" class="text-center">#</th>
                                        <th width=160">{{ trans('request.field_created_updated_time') }}</th>
                                        <th>{{ trans('request.field_title') }}</th>
                                        <th width="60" class="text-center">{{ trans('request.field_type') }}</th>
                                        <th width="80" class="text-center">{{ trans('request.field_responded?') }}</th>
                                        <th width="80" class="text-center">{{ trans('request.field_exported?') }}</th>
                                        <th width="240">{{ trans('request.field_shipping_status') }}</th>
                                        <th width="110">{{ trans('request.field_actions') }}</th>
                                    </tr></thead>
                                    <tbody>
                                    <tr v-for="(request, index) in filteredRequests">
                                        <td class="text-center">@{{ request.id }}</td>
                                        <td>@{{ request.created_at }}<br>
                                            @{{ request.updated_at }}</td>
                                        <td>@{{ request.title }}<br>
                                            <small>
                                                <a v-bind:href="'{{ url('shipment/requests') }}/' + request.token" target="_blank">
                                                    @{{ request.token }}
                                                </a>
                                            </small>
                                        </td>
                                        <td v-if="request.address_type == 'cvs'" class="text-center">
                                            {{ trans('request.type_cvs') }}
                                        </td>
                                        <td v-if="request.address_type == 'standard'" class="text-center">
                                            {{ trans('request.type_standard') }}
                                        </td>

                                        <td v-if="request.responded" class="text-center">
                                            <span class="label label-success"><i class="fa fa-check"></i></span>
                                        </td>
                                        <td v-else class="text-center">
                                            <span class="label label-warning"><i class="fa fa-close"></i></span>
                                        </td>

                                        <td v-if="request.exported" class="text-center">
                                            <span class="label label-success"><i class="fa fa-check"></i></span> <br>
                                            <small>(@{{ request.exported }})</small></td>
                                        <td v-else class="text-center">
                                            <span class="label label-warning"><i class="fa fa-close"></i></span>
                                        </td>

                                        <td v-if="request.exported">ID: @{{ request.shipment_ticket_id }}<br>
                                            <small>@{{ request.shipment_status }} @{{ ecpayCodes[request.shipment_status].message }}</small></td>
                                        <td v-else>{{ trans('request.field_na') }}</td>

                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-default" v-on:click="showRequest(index)">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                <button class="btn btn-warning" v-on:click="confirmArchive(index)">
                                                    <i class="fa fa-archive"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="request-modal" class="modal fade" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <h4 class="modal-title">{{ trans('request.detail_title') }}</h4>
                </div>

                <div class="modal-body">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th width="140" class="text-right">{{ trans('request.field_id') }}</th>
                                <td>@{{ modalContent.id }}</td>
                            </tr>
                            <tr>
                                <th class="text-right">{{ trans('request.field_title') }}</th>
                                <td>@{{ modalContent.title }}</td>
                            </tr>
                            <tr>
                                <th class="text-right">{{ trans('request.field_description') }}</th>
                                <td>@{{ modalContent.description}}</td>
                            </tr>
                            <tr>
                                <th class="text-right">{{ trans('request.field_responded?') }}</th>
                                <td v-if="modalContent.responded">
                                    <span class="label label-success"><i class="fa fa-check"></i></span>
                                </td>
                                <td v-else>
                                    <span class="label label-warning"><i class="fa fa-close"></i></span>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-right">{{ trans('request.field_exported?') }}</th>
                                <td v-if="modalContent.exported">
                                    <span class="label label-success"><i class="fa fa-check"></i></span>
                                    (@{{ modalContent.exported }})
                                </td>
                                <td v-else>
                                    <span class="label label-warning"><i class="fa fa-close"></i></span>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-right">{{ trans('request.field_shipping_status') }}</th>
                                <td v-if="modalContent.exported">
                                    @{{ modalContent.shipment_status }} @{{ ecpayCodes[modalContent.shipment_status].message }}<br>
                                    @{{ ecpayCodes[modalContent.shipment_status].description }}
                                </td>
                                <td v-else>{{ trans('request.field_na') }}</td>
                            </tr>
                            <tr>
                                <th class="text-right">{{ trans('request.field_shipment_ticket_id') }}</th>
                                <td v-if="modalContent.exported">
                                    @{{ modalContent.shipment_ticket_id }}
                                </td>
                                <td v-else>{{ trans('request.field_na') }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div v-if="modalContent.responded" class="alert alert-info">
                        <div v-if="modalContent.address_type == 'cvs'">
                            @{{ modalContent.cvs_address.receiver }} @{{ modalContent.cvs_address.phone }} <br>
                            @{{ modalContent.cvs_address.vendor }} @{{ modalContent.cvs_address.store }} <br>
                        </div>
                        <div v-if="modalContent.address_type == 'standard'">
                            @{{ modalContent.standard_address.receiver }} @{{ modalContent.standard_address.phone }} <br>
                            @{{ modalContent.standard_address.postcode }}
                            @{{ modalContent.standard_address.county }}
                            @{{ modalContent.standard_address.city }}
                            @{{ modalContent.standard_address.address1 }} <br>
                            @{{ modalContent.standard_address.address2 }} <br>
                        </div>
                    </div>

                    <div v-if="modalContent.responded && !modalContent.exported">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <b>{{ trans('request.field_package') }}</b>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exportForm-product-name" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('request.field_product_name') }}</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="exportForm-product-name" v-model="exportForm.package.products" maxlength="60" required></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exportForm-amount" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('request.field_product_amount') }}</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-addon">NT$</div>
                                        <input type="number" class="form-control" id="exportForm-amount" v-model="exportForm.package.amount" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exportForm-collect" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('request.field_collect?') }}</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="exportForm-collect" v-model="exportForm.package.collect">
                                        <option value="false">{{ trans('request.status_no') }}</option>
                                        <option value="true">{{ trans('request.status_yes') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div v-if="modalContent.address_type == 'standard'">
                                <div class="form-group">
                                    <label for="exportForm-vendor" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('request.field_vendor') }}</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="exportForm-vendor" v-model="exportForm.package.vendor">
                                            <option value="TCAT">{{ trans('request.vendor_tcat') }}</option>
                                            <option value="ECAN">{{ trans('request.vendor_ecan') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exportForm-temperature" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('request.field_temperature') }}</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="exportForm-temperature" v-model="exportForm.package.temperature">
                                            <option value="normal">{{ trans('request.temperature_normal') }}</option>
                                            <option value="refrigeration">{{ trans('request.temperature_refrigeration') }}</option>
                                            <option value="freeze">{{ trans('request.temperature_freezing') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exportForm-distance" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('request.field_distance') }}</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="exportForm-distance" v-model="exportForm.package.distance">
                                            <option value="other">{{ trans('request.distance_difference_county') }}</option>
                                            <option value="same">{{ trans('request.distance_local') }}</option>
                                            <option value="island">{{ trans('request.distance_outer_island') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exportForm-specification" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('request.field_specification') }}</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="exportForm-specification" v-model="exportForm.package.specification">
                                            <option value="60">60cm</option>
                                            <option value="90">90cm</option>
                                            <option value="120">120cm</option>
                                            <option value="150">150cm</option>
                                        </select>
                                    </div>
                                </div>
                            </div> <!-- /v-if(type == standard) -->

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <b>{{ trans('request.field_common') }}</b>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exportForm-sender" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('request.field_sender_name') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="exportForm-sender" v-model="exportForm.sender.name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exportForm-sender-phone" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('request.field_sender_phone') }}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="exportForm-sender-phone" v-model="exportForm.sender.phone" required>
                                </div>
                            </div>

                            <div v-if="modalContent.address_type == 'standard'">
                                <div class="form-group">
                                    <label for="exportForm-sender-postcode" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('request.field_sender_postcode') }}</label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" id="exportForm-sender-postcode" max="999" v-model="exportForm.sender.postcode" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exportForm-sender-address" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('request.field_sender_address') }}</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="exportForm-sender-address" name="sender_address" v-model="exportForm.sender.address" required>
                                    </div>
                                </div>
                            </div> <!-- /v-if(type == standard) -->

                            <div class="form-group">
                                <div class="col-sm-9 col-sm-offset-3">
                                    <button type="button" class="btn btn-md btn-primary" v-on:click="exportTicket()">{{ trans('request.export_btn') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="sender-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <h4 class="modal-title">{{ trans('request.profile_title') }}</h4>
                </div>

                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label for="profile-name" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('request.field_sender_name') }}</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" id="profile-name" v-model="sender.name" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="profile-phone" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('request.field_sender_phone') }}</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" id="profile-phone" v-model="sender.phone" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="profile-postcode" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('request.field_sender_postcode') }}</label>
                            <div class="col-sm-5">
                                <input type="number" class="form-control" id="profile-postcode" max="999" v-model="sender.postcode" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="profile-address" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('request.field_sender_address') }}</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" id="profile-address" v-model="sender.address" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-5 col-sm-offset-2">
                                <button type="button" class="btn btn-primary" v-on:click="saveSender()">{{ trans('request.submit') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="create-modal" class="modal fade" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <h4 class="modal-title">{{ trans('request.create_title') }}</h4>
                </div>

                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label for="create-title" class="col-sm-2 control-label">{{ trans('request.field_title') }}</label>
                            <div class="col-sm-10">
                                <input id="create-title" v-model="createForm.title" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="create-description" class="col-sm-2 control-label">{{ trans('request.field_description') }}</label>
                            <div class="col-sm-10">
                                <textarea id="create-description" v-model="createForm.description" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="create-method" class="col-sm-2 control-label">{{ trans('request.field_shipping') }}</label>
                            <div class="col-sm-10">
                                <select id="create-method" v-model="createForm.method" class="form-control">
                                    <option value="standard">{{ trans('request.shipping_standard') }}</option>
                                    <option value="cvs">{{ trans('request.shipping_cvs') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                                <button type="button" v-on:click="createRequest()" class="btn btn-primary">{{ trans('request.submit') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="archive-modal" class="modal fade" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <h4 class="modal-title">{{ trans('request.archive_title') }}</h4>
                </div>

                <div v-if="requests[archive]" class="modal-body">
                    {{ trans('request.archive_confirm_text') }}<br>
                    @{{ requests[archive].title }}<br>
                    <br>
                    <p v-if="!requests[archive].exported" class="text-danger">
                        {{ trans('request.archive_not_exported_text') }}
                    </p>
                </div>

                <div class="modal-footer">
                    <div class="btn-group pull-right">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('request.cancel') }}</button>
                        <button type="button" class="btn btn-warning" v-on:click="archiveRequest">{{ trans('request.archive_btn') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('footer')
    <script>
        var EcpayCodes = {!! json_encode($ecpay_codes) !!};
    </script>
    <script src="{{ elixir('js/backend-requests.js') }}"></script>
@endsection
