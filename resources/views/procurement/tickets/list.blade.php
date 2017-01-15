@extends('layouts.app')

@section('title', 'Procurement Tickets')

@section('content')
    <div class="container">
        <div class="col-sm-4">
            <h3>{{ trans('procurement_ticket.tickets') }}</h3>
        </div>

        <div class="col-sm-8">
            <p class="h4"></p>
            <div class="text-right">
                <div class="btn-group">
                    <a href="{{ url('procurement/tickets/new') }}" class="btn btn-primary" target="_blank">{{ trans('procurement_ticket.button_new_ticket') }}</a>
                    <button type="button" class="btn btn-default" v-on:click="showCategoryModal()">{{ trans('procurement_ticket.button_item_categories') }}</button>
                    <button type="button" class="btn btn-default" v-on:click="showLocalShipmentModal()">{{ trans('procurement_ticket.button_local_shipment_methods') }}</button>
                    <button type="button" class="btn btn-default" v-on:click="showJapanShipmentModal()">{{ trans('procurement_ticket.button_japan_shipment_methods') }}</button>
                    <button type="button" class="btn btn-default" v-on:click="showConfigModal()">{{ trans('procurement_ticket.button_configs') }}</button>
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                   {{ trans('procurement_ticket.tickets') }}
                </div>

                <div class="panel-body">
                    <div id="procurement-ticket-filter">
                        <div class="col-sm-3 form-group">
                            <label class="control-label">{{ trans('procurement_ticket.filter_ticket_status') }} </label>
                            <select v-model="filter.ticketStatus" class="form-control">
                                <option value="0">{{ trans('procurement_ticket.filter_all_status') }}</option>
                                <option v-for="(text, code) in status.ticket" v-bind:value="code">@{{ text }}</option>
                            </select>
                        </div>
                        <div class="col-sm-3 form-group">
                            <label class="control-label">{{ trans('procurement_ticket.filter_item_status') }} </label>
                            <select v-model="filter.itemStatus" class="form-control">
                                <option value="0">{{ trans('procurement_ticket.filter_all_status') }}</option>
                                <option v-for="(text, code) in status.item" v-bind:value="code">@{{ text }}</option>
                            </select>
                        </div>
                    </div>

                    <table class="table">
                        <thead><tr>
                            <td>#</td>
                            <td colspan="6">{{ trans('procurement_ticket.field_status') }}</td>
                            <td>{{ trans('procurement_ticket.field_customer') }}</td>
                            <td>{{ trans('procurement_ticket.field_rate') }}</td>
                            <td>{{ trans('procurement_ticket.field_actions') }}</td>
                        </tr></thead>

                        <tbody>
                            <template v-for="(ticket, index) in tickets">
                                <template v-if="(filter.ticketStatus == '0' || ticket.status == filter.ticketStatus) &&
                                                (filter.itemStatus == '0' || checkItemsStatus(ticket.items))">
                                    <tr class="active">
                                        <td>@{{ ticket.id }}</td>
                                        <td colspan="6">@{{ status.ticket[ticket.status] }}<br>
                                            <small>
                                                @{{ ticket.updated_at }}
                                            </small>
                                        </td>
                                        <td>
                                            @{{ ticket.name }} &lt;@{{ ticket.email }}&gt; @{{ ticket.contact }}<br>
                                            <small>
                                                <a v-bind:href="'/procurement/tickets/' + ticket.token" target="_blank">@{{ ticket.token }}</a>
                                            </small>
                                        </td>
                                        <td>@{{ ticket.rate }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a v-bind:href="'/procurement/tickets/' + ticket.token" target="_blank" class="btn btn-default">
                                                    {{ trans('procurement_ticket.button_view') }}
                                                </a>
                                                <button type="button" class="btn btn-primary" v-on:click="editTicket(index)">
                                                    {{ trans('procurement_ticket.button_edit') }}
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <template v-for="(item, index) in ticket.items">
                                        <template v-if="filter.itemStatus == '0' || filter.itemStatus == item.status">
                                            <tr>
                                                <td></td>
                                                <td colspan="1">@{{ index + 1 }}</td>
                                                <td colspan="5">
                                                    @{{ status.item[item.status] }}<br>
                                                    <small>
                                                        @{{ item.updated_at }}
                                                    </small>
                                                </td>
                                                <td>@{{ item.title }}<br>
                                                    <small>
                                                        <a v-bind:href="item.url" target="_blank">@{{ item.url }}</a>
                                                    </small>
                                                </td>
                                                <td>@{{ format('JPY', item.price) }}</td>
                                                <td></td>
                                            </tr>
                                        </template> {{-- /v-if itemStatus --}}
                                    </template> {{-- /v-for items --}}
                                </template> {{-- /v-if ticketStatus --}}
                            </template>
                        </tbody>
                    </table>
                </div> {{-- /.panel-body --}}

            </div> {{-- /.panel --}}
        </div>

    </div> {{-- /.container --}}


    <div id="ticket-modal" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    {{ trans('procurement_ticket.edit_title') }}
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div id="ticket-modal-ticket">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#ticket-modal-ticket-configure" aria-controls="ticket-modal-ticket-configure" role="tab" data-toggle="tab">
                                    {{ trans('procurement_ticket.edit_tab_ticket_configure') }}
                                </a>
                            </li>

                            <li role="presentation">
                                <a href="#ticket-modal-ticket-items" aria-controls="ticket-modal-ticket-items" role="tab" data-toggle="tab">
                                    {{ trans('procurement_ticket.edit_tab_items') }}
                                </a>
                            </li>

                            <li role="presentation">
                                <a href="#ticket-modal-ticket-japan_shipments" aria-controls="ticket-modal-ticket-japan_shipments" role="tab" data-toggle="tab">
                                    {{ trans('procurement_ticket.edit_tab_japan_shipments') }}
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div id="ticket-modal-ticket-configure" class="tab-pane active">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ticket-modal-ticket-configure-name" class="control-label">{{ trans('procurement_ticket.field_customer_name') }}</label>
                                            <input type="text" v-model="edit.name" id="ticket-modal-ticket-configure-name" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="ticket-modal-ticket-configure-email" class="control-label">{{ trans('procurement_ticket.field_customer_email') }}</label>
                                            <input type="text" v-model="edit.email" id="ticket-modal-ticket-configure-email" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="ticket-modal-ticket-configure-contact" class="control-label">{{ trans('procuremen_ticket.field_contact') }}</label>
                                            <input type="text" v-model="edit.contact" id="ticket-modal-ticket-configure-contact" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="ticket-modal-ticket-configure-rate" class="control-label">
                                                {{ trans('procurement_ticket.field_currency_rate') }}
                                                ({{ trans('procurement_ticket.field_latest_rate') }} @{{ rate }}, <a href="#" v-on:click="updateEditRate()">{{ trans('procurement_ticket.field_update_rate') }}</a>)
                                            </label>
                                            <input type="text" v-model="edit.rate" id="ticket-modal-ticket-configure-rate" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ticket-modal-ticket-configure-note" class="control-label">{{ trans('procurement_ticket.field_note') }}</label>
                                            <textarea v-model="edit.note" id="ticket-modal-ticket-configure-note" class="form-control" rows="7"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="ticket-modal-ticket-configure-status" class="control-label">{{ trans('procurement_ticket.field_ticket_status') }}</label>
                                            <select v-model="edit.status" class="form-control">
                                                <option v-for="(text, code) in status.ticket" v-bind:value="code">@{{ text }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ticket-modal-ticket-configure-local_shipment-method" class="control-label">{{ trans('procurement_ticket.field_shipment_method') }}</label>
                                            <input type="text" v-model="edit.localShipment.method" id="ticket-modal-ticket-configure-local_shipment-method" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <select v-model="edit.localShipmentSelect" class="form-control">
                                                <option v-for="(method, index) in shipmentMethods.local" v-bind:value="index">@{{ method.name + ': NT$' + method.price }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ticket-modal-ticket-configure-local_shipment-price" class="control-label">{{ trans('procurement_ticket.field_shipment_price') }}</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">NT$</div>
                                                <input type="text" v-model="edit.localShipment.price" id="ticket-modal-ticket-configure-local_shipment-price" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary" v-on:click="setEditModalLocalShipmentMethod()">{{ trans('procurement_ticket.button_set') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div> {{-- /#ticket-modal-ticket-configure --}}

                            <div id="ticket-modal-ticket-items" class="tab-pane">
                                <div class="row">
                                    <div class="col-sm-12 text-right">
                                        <button type="button" class="btn btn-success" v-on:click="addEditItem()">{{ trans('procurement_ticket.button_add_item') }}</button>
                                    </div>
                                </div>
                                <template v-for="(item, index) in edit.items">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <hr>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3 col-md-offset-1">
                                            <div class="form-group">
                                                <select v-model="edit.items[index].status" class="form-control">
                                                    <option v-for="(text, code) in status.item" v-bind:value="code">@{{ text }}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <input type="text" v-model="edit.items[index].title" class="form-control" placeholder="{{ trans('procurement_ticket.field_product_name') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-1 text-center">
                                            @{{ index + 1 }} <br>
                                            <label><input type="checkbox" v-model="edit.items[index].deleted"> {{ trans('procurement_ticket.field_del') }}</label>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" v-model="edit.items[index].url" class="form-control" placeholder="{{ trans('procurement_ticket.field_url') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="number" v-model="edit.items[index].price" class="form-control" placeholder="{{ trans('procurement_ticket.field_price') }}">
                                                    <div class="input-group-addon">¥</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <input type="text" v-model="edit.items[index].note" class="form-control" placeholder="{{ trans('procurement_ticket.field_note') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-2 col-md-offset-1">
                                            <select v-model="edit.items[index].category_id" class="form-control">
                                                <option v-for="category in categories" v-bind:value="category.id">@{{ category.name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <div id="ticket-modal-ticket-japan_shipments" class="tab-pane">
                                <div class="row">
                                    <div class="col-sm-5 col-sm-offset-4 text-right">
                                        <div class="form-group">
                                            <select v-model="edit.japanShipmentSelect" class="form-control">
                                                <option v-for="(method, index) in shipmentMethods.japan" v-bind:value="index">@{{ method.name + ': ¥' + method.price }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 text-right">
                                        <button type="button" class="btn btn-success" v-on:click="addEditJapanShipment()">{{ trans('procurement_ticket.button_add_japan_shipment') }}</button>
                                    </div>
                                </div>
                                <template v-for="(japanShipment, index) in edit.japanShipments">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <hr>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-1 text-center">
                                            @{{ index + 1 }} <br>
                                            <label><input type="checkbox" v-model="edit.japanShipments[index].deleted"> {{ trans('procurement_ticket.field_del') }}</label>
                                        </div>

                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <input type="text" v-model="edit.japanShipments[index].title" class="form-control" placeholder="{{ trans('procurement_ticket.field_shipment_method') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input type="number" v-model="edit.japanShipments[index].price" class="form-control" placeholder="{{ trans('procurement_ticket.field_shipment_price') }}">
                                                    <div class="input-group-addon">¥</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div> {{-- /.tab-content --}}
                    </div> {{-- /#ticket-modal-ticket --}}

                    <div class="clearfix"></div>
                </div> {{-- /.modal-body --}}

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('procurement_ticket.button_close') }}</button>
                    <button type="button" class="btn btn-primary" v-on:click="saveEdit()">{{ trans('procurement_ticket.button_save') }}</button>
                </div> {{-- /.modal-footer --}}
            </div> {{-- /.modal-content --}}
        </div> {{-- /.modal-dialog --}}
    </div> {{-- /#ticket-modal --}}

    <div id="category-modal" class="modal fade">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    {{ trans('procurement_ticket.title_edit_categories') }}
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="col-sm-12">
                        <div class="text-right">
                            <button type="button" class="btn btn-success" v-on:click="addCategory()">{{ trans('procurement_ticket.button_add_category') }}</button>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <hr>
                    </div>

                    <div class="col-sm-12">
                        <table class="table">
                            <thead><tr>
                                <th>#</th>
                                <th>{{ trans('procurement_ticket.field_category_name') }}</th>
                                <th>{{ trans('procurement_ticket.field_category_value') }}</th>
                                <th>{{ trans('procurement_ticket.field_category_minimum') }}</th>
                                <th>{{ trans('procurement_ticket.field_actions') }}</th>
                            </tr></thead>
                            <tbody>
                                <tr v-for="(category, index) in categoryModal" v-bind:class="category.deleted_at ? 'danger' : ''">
                                    <td>@{{ index + 1 }}</td>
                                    <td><input type="text" v-model="categoryModal[index].name" class="form-control"></td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" v-model="categoryModal[index].value" class="form-control">
                                            <div class="input-group-addon">%</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-addon">NT$</div>
                                            <input type="number" v-model="categoryModal[index].lower" class="form-control">
                                        </div>
                                    </td>
                                    <td v-if="category.deleted_at == true">
                                        <button type="button" class="btn btn-warning" v-on:click="undoRemoveCategory(index)">{{ trans('procurement_ticket.button_undo') }}</button>
                                    </td>
                                    <td v-else>
                                        <button type="button" class="btn btn-danger" v-on:click="removeCategory(index)">{{ trans('procurement_ticket.button_delete') }}</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="clearfix"></div>
                </div> {{-- /.modal-body --}}

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('procurement_ticket.button_close') }}</button>
                    <button type="button" class="btn btn-primary" v-on:click="saveCategories()">{{ trans('procurement_ticket.button_save') }}</button>
                </div> {{-- /.modal-footer --}}
            </div> {{-- /.modal-content --}}
        </div> {{-- /.modal-dialog --}}
    </div> {{-- /#category-modal --}}

    <div id="config-modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    {{ trans('procurement_ticket.title_edit_configs') }}
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div> {{-- /.modal-header --}}

                <div class="modal-body">
                    <table class="table">
                        <thead><tr>
                            <th>{{ trans('procurement_ticket.field_config_name') }}</th>
                            <th>{{ trans('procurement_ticket.field_config_value') }}</th>
                        </tr></thead>

                        <tbody>
                            <tr>
                                <td>{{ trans('configs.procurement.minimum_fee') }}</td>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-addon">NT$</div>
                                        <input type="number" v-model="configModal['procurement.minimum_fee']" class="form-control">
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div> {{-- /.modal-body --}}

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('procurement_ticket.button_close') }}</button>
                    <button type="button" class="btn btn-primary" v-on:click="saveConfigs()">{{ trans('procurement_ticket.button_save') }}</button>
                </div> {{-- /.modal-footer --}}
            </div> {{-- /.modal-content --}}
        </div> {{-- /.modal-dialog --}}
    </div> {{-- /#config-modal --}}

    <div id="japan_shipment-modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    {{ trans('procurement_ticket.title_edit_japan_shipment_methods') }}
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div> {{-- /.modal-header --}}

                <div class="modal-body">
                    <div class="col-sm-12">
                        <div class="text-right">
                            <button type="button" class="btn btn-success" v-on:click="addJapanShipmentMethod()">{{ trans('procurement_ticket.button_add_method') }}</button>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <hr>
                    </div>

                    <div class="col-sm-12">
                        <table class="table">
                            <thead><tr>
                                <th>#</th>
                                <th>{{ trans('procurement_ticket.field_japan_shipment_name') }}</th>
                                <th>{{ trans('procurement_ticket.field_japan_shipment_price') }}</th>
                                <th>{{ trans('procurement_ticket.field_actions') }}</th>
                            </tr></thead>
                            <tbody>
                            <tr v-for="(method, index) in japanShipmentModal" v-bind:class="method.deleted_at ? 'danger' : ''">
                                <td>@{{ index + 1 }}</td>
                                <td>
                                    <input type="text" v-model="japanShipmentModal[index].name" class="form-control" placeholder="{{ trans('procurement_ticket.field_method_name') }}">
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="number" v-model="japanShipmentModal[index].price" class="form-control" placeholder="{{ trans('procurement_ticket.field_method_price') }}">
                                        <div class="input-group-addon">¥</div>
                                    </div>
                                </td>
                                <td>
                                    <button v-if="method.deleted_at == null" type="button" class="btn btn-danger" v-on:click="removeJapanShipment(index)">{{ trans('procurement_ticket.button_save') }}</button>
                                    <button v-else type="button" class="btn btn-warning" v-on:click="undoRemoveJapanShipment(index)">{{ trans('procurement_ticket.button_undo') }}</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="clearfix"></div>
                </div> {{-- /.modal-body --}}

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('procurement_ticket.button_close') }}</button>
                    <button type="button" class="btn btn-primary" v-on:click="saveJapanShipment()">{{ trans('procurement_ticket.button_save') }}</button>
                </div> {{-- /.modal-footer --}}
            </div> {{-- /.modal-header --}}
        </div> {{-- /.modal-dialog --}}
    </div> {{-- /#japan_shipment-modal --}}

    <div id="local-modal" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    {{ trans('procurement_ticket.title_edit_local_shipment_methods') }}
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div> {{-- /.modal-header --}}

                <div class="modal-body">
                    <div class="col-sm-12">
                        <div class="text-right">
                            <button type="button" class="btn btn-success" v-on:click="addLocalShipmentMethod()">{{ trans('procurement_ticket.button_add_method') }}</button>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <hr>
                    </div>

                    <div class="col-sm-12">
                        <table class="table">
                            <thead><tr>
                                <th>#</th>
                                <th>{{ trans('procurement_ticket.field_local_shipment_type') }}</th>
                                <th>{{ trans('procurement_ticket.field_local_shipment_name') }}</th>
                                <th>{{ trans('procurement_ticket.field_local_shipment_price') }}</th>
                                <th>{{ trans('procurement_ticket.field_local_shipment_show') }}</th>
                                <th>{{ trans('procurement_ticket.field_actions') }}</th>
                            </tr></thead>
                            <tbody>
                                <tr v-for="(method, index) in localShipmentModal" v-bind:class="method.deleted_at ? 'danger' : ''">
                                    <td>@{{ index + 1 }}</td>
                                    <td>
                                        <select class="form-control" v-model="localShipmentModal[index].type">
                                            <option value="cvs">{{ trans('procurement_ticket.field_local_shipment_cvs') }}</option>
                                            <option value="standard">{{ trans('procurement_ticket.field_local_shipment_standard') }}</option>
                                            <option value="none">{{ trans('procurement_ticket.field_local_shipment_none') }}</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" v-model="localShipmentModal[index].name" class="form-control" placeholder="{{ trans('procurement_ticket.field_method_name') }}">
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-addon">NT$</div>
                                            <input type="number" v-model="localShipmentModal[index].price" class="form-control" placeholder="{{ trans('procurement_ticket.field_method_price') }}">
                                        </div>
                                    </td>
                                    <td>
                                        <input type="checkbox" v-model="localShipmentModal[index].show" class="form-control">
                                    </td>
                                    <td>
                                        <button v-if="method.deleted_at == null" type="button" class="btn btn-danger" v-on:click="removeLocalShipment(index)">{{ trans('procurement_ticket.button_delete') }}</button>
                                        <button v-else type="button" class="btn btn-warning" v-on:click="undoRemoveLocalShipment(index)">{{ trans('procurement_ticket.button_undo') }}</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="clearfix"></div>
                </div> {{-- /.modal-body --}}

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('procurement_ticket.button_close') }}</button>
                    <button type="button" class="btn btn-primary" v-on:click="saveLocalShipment()">{{ trans('procurement_ticket.button_save') }}</button>
                </div> {{-- /.modal-footer --}}
            </div> {{-- /.modal-header --}}
        </div> {{-- /.modal-dialog --}}
    </div> {{-- /#japan_shipment-modal --}}

@endsection

@section('footer')
    <script>
        const TicketStatus = {!! json_encode($ticket_status) !!};
        const ItemStatus = {!! json_encode($item_status) !!};
        const Rate = {{ $rate }};
    </script>
    <script src="{{ elixir('js/procurement-tickets-backend.js') }}"></script>
@endsection
