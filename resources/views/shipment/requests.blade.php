@extends('layouts.app')

@section('content')

    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                {{ trans('request.list_title') }}
            </div>

            <div class="panel-body">
                <button type="button" v-on:click="showSender()" class="btn btn-success">{{ trans('request.profile_btn') }}</button>
                <button type="button" v-on:click="showCreate()" class="btn btn-primary">{{ trans('request.create_btn') }}</button>
                <div class="clearfix"></div>
                <table class="table table-bordered">
                    <thead><tr>
                        <td>#</td>
                        <td>{{ trans('request.field_title') }}</td>
                        <td>{{ trans('request.field_token') }}</td>
                        <td>{{ trans('request.field_type') }}</td>
                        <td>{{ trans('request.field_responded?') }}</td>
                        <td>{{ trans('request.field_exported?') }}</td>
                        <td>{{ trans('request.field_actions') }}</td>
                    </tr></thead>
                    <tbody>
                        <tr v-for="(request, index) in requests">
                            <td>@{{ request.id }}</td>
                            <td>@{{ request.title }}</td>
                            <td>
                                <a v-bind:href="'{{ url('shipment/requests') }}/' + request.token" target="_blank">
                                    @{{ request.token }}
                                </a>
                            </td>
                            <td v-if="request.address_type == 'cvs'">{{ trans('request.type_cvs') }}</td>
                                <td v-if="request.address_type == 'standard'">{{ trans('request.type_standard') }}</td>
                            <td v-if="request.responded">{{ trans('request.status_yes') }}</td>
                                <td v-else>{{ trans('request.status_no') }}</td>
                            <td v-if="request.exported">{{ trans('request.status_yes') }}</td>
                                <td v-else>{{ trans('request.status_no') }}</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-primary" v-on:click="showRequest(index)">{{ trans('request.detail_btn') }}</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="request-modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <h4 class="modal-title">{{ trans('request.detail_title') }}</h4>
                </div>

                <div class="modal-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>{{ trans('request.field_id') }}</td>
                                <td>@{{ modalContent.id }}</td>
                            </tr>
                            <tr>
                                <td>{{ trans('request.field_title') }}</td>
                                <td>@{{ modalContent.title }}</td>
                            </tr>
                            <tr>
                                <td>{{ trans('request.field_description') }}</td>
                                <td>@{{ modalContent.description}}</td>
                            </tr>
                            <tr>
                                <td>{{ trans('request.field_responded?') }}</td>
                                <td v-if="modalContent.responded">{{ trans('request.status_yes') }}</td>
                                <td v-else>{{ trans('request.status_no') }}</td>
                            </tr>
                            <tr>
                                <td>{{ trans('request.field_exported?') }}</td>
                                <td v-if="modalContent.exportFormed">{{ trans('request.status_yes') }}</td>
                                    <td v-else>{{ trans('request.status_no') }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div v-if="modalContent.responded" class="well well-sm">
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

                    <div v-if="modalContent.loading">
                        <h3 class="text-center"><span class="loading dots"></span> {{ trans('request.loading') }}</h3>
                    </div>

                    <div v-if="modalContent.responded && !modalContent.loading">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <b>{{ trans('request.field_package') }}</b>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exportForm-product-name" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('request.field_product_name') }}</label>
                                <div class="col-sm-5">
                                    <textarea class="form-control" id="exportForm-product-name" v-model="exportForm.package.products" maxlength="60" required></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exportForm-amount" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('request.field_product_amount') }}</label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <div class="input-group-addon">NT$</div>
                                        <input type="number" class="form-control" id="exportForm-amount" v-model="exportForm.package.amount" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exportForm-collect" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('request.field_collect?') }}</label>
                                <div class="col-sm-5">
                                    <select class="form-control" id="exportForm-collect" v-model="exportForm.package.collect">
                                        <option value="false">{{ trans('request.status_no') }}</option>
                                        <option value="true">{{ trans('request.status_yes') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div v-if="modalContent.address_type == 'standard'">
                                <div class="form-group">
                                    <label for="exportForm-vendor" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('request.field_vendor') }}</label>
                                    <div class="col-sm-5">
                                        <select class="form-control" id="exportForm-vendor" v-model="exportForm.package.vendor">
                                            <option value="TCAT">{{ trans('request.vendor_tcat') }}</option>
                                            <option value="ECAN">{{ trans('request.vendor_ecan') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exportForm-temperature" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('request.field_temperature') }}</label>
                                    <div class="col-sm-5">
                                        <select class="form-control" id="exportForm-temperature" v-model="exportForm.package.temperature">
                                            <option value="normal">{{ trans('request.temperature_normal') }}</option>
                                            <option value="refrigeration">{{ trans('request.temperature_refrigeration') }}</option>
                                            <option value="freeze">{{ trans('request.temperature_freezing') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exportForm-distance" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('request.field_distance') }}</label>
                                    <div class="col-sm-5">
                                        <select class="form-control" id="exportForm-distance" v-model="exportForm.package.distance">
                                            <option value="other">{{ trans('request.distance_difference_county') }}</option>
                                            <option value="same">{{ trans('request.distance_local') }}</option>
                                            <option value="island">{{ trans('request.distance_outer_island') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exportForm-specification" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('request.field_specification') }}</label>
                                    <div class="col-sm-5">
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
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="exportForm-sender" v-model="exportForm.sender.name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exportForm-sender-phone" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('request.field_sender_phone') }}</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="exportForm-sender-phone" v-model="exportForm.sender.phone" required>
                                </div>
                            </div>

                            <div v-if="modalContent.address_type == 'standard'">
                                <div class="form-group">
                                    <label for="exportForm-sender-postcode" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('request.field_sender_postcode') }}</label>
                                    <div class="col-sm-5">
                                        <input type="number" class="form-control" id="exportForm-sender-postcode" max="999" v-model="exportForm.sender.postcode" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exportForm-sender-address" class="col-sm-2 col-sm-offset-1 control-label">{{ trans('request.field_sender_address') }}</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" id="exportForm-sender-address" name="sender_address" v-model="exportForm.sender.address" required>
                                    </div>
                                </div>
                            </div> <!-- /v-if(type == standard) -->

                            <div class="form-group">
                                <div class="col-sm-5 col-sm-offset-3">
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

@endsection

@section('footer')
    <script src="{{ elixir('js/backend-requests.js') }}"></script>
@endsection
