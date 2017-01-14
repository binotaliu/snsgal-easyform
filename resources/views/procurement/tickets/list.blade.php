@extends('layouts.app')

@section('title', 'Procurement Tickets')

@section('content')
    <div class="container">
        <div class="col-sm-6">
            <h3>Procurement Tickets</h3>
        </div>

        <div class="col-sm-6">
            <p class="h4"></p>
            <div class="text-right">
                <div class="btn-group">
                    <a href="{{ url('procurement/tickets/new') }}" class="btn btn-default" target="_blank">New Ticket</a>
                    <button type="button" class="btn btn-default" v-on:click="showCategoryModal()">Item Categories</button>
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Tickets
                </div>

                <div class="panel-body">
                    <div id="procurement-ticket-filter">
                        <div class="col-sm-3 form-group">
                            <label class="control-label">Ticket Status: </label>
                            <select v-model="filter.ticketStatus" class="form-control">
                                <option value="0">All Status</option>
                                <option v-for="(text, code) in status.ticket" v-bind:value="code">@{{ text }}</option>
                            </select>
                        </div>
                        <div class="col-sm-3 form-group">
                            <label class="control-label">Item Status: </label>
                            <select v-model="filter.itemStatus" class="form-control">
                                <option value="0">All Status</option>
                                <option v-for="(text, code) in status.item" v-bind:value="code">@{{ text }}</option>
                            </select>
                        </div>
                    </div>

                    <table class="table">
                        <thead><tr>
                            <td>#</td>
                            <td colspan="6">Status</td>
                            <td>Customer</td>
                            <td>Rate</td>
                            <td>Actions</td>
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
                                                    View
                                                </a>
                                                <button type="button" class="btn btn-primary" v-on:click="editTicket(index)">
                                                    Edit
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
                    Edit Ticket
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div id="ticket-modal-ticket">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#ticket-modal-ticket-configure" aria-controls="ticket-modal-ticket-configure" role="tab" data-toggle="tab">
                                    Ticket Configure
                                </a>
                            </li>

                            <li role="presentation">
                                <a href="#ticket-modal-ticket-items" aria-controls="ticket-modal-ticket-items" role="tab" data-toggle="tab">
                                    Items
                                </a>
                            </li>

                            <li role="presentation">
                                <a href="#ticket-modal-ticket-japan_shipments" aria-controls="ticket-modal-ticket-japan_shipments" role="tab" data-toggle="tab">
                                    In-Japan Shipments
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div id="ticket-modal-ticket-configure" class="tab-pane active">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ticket-modal-ticket-configure-name" class="control-label">Customer Name:</label>
                                            <input type="text" v-model="edit.name" id="ticket-modal-ticket-configure-name" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="ticket-modal-ticket-configure-email" class="control-label">Customer E-Mail:</label>
                                            <input type="text" v-model="edit.email" id="ticket-modal-ticket-configure-email" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="ticket-modal-ticket-configure-contact" class="control-label">Contact:</label>
                                            <input type="text" v-model="edit.contact" id="ticket-modal-ticket-configure-contact" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="ticket-modal-ticket-configure-rate" class="control-label">
                                                Currency Rate: (Latest Rate: @{{ rate }}, <a href="#" v-on:click="updateEditRate()">Click to Update</a>)
                                            </label>
                                            <input type="text" v-model="edit.rate" id="ticket-modal-ticket-configure-rate" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ticket-modal-ticket-configure-note" class="control-label">Ticket Note:</label>
                                            <textarea v-model="edit.note" id="ticket-modal-ticket-configure-note" class="form-control" rows="7"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="ticket-modal-ticket-configure-status" class="control-label">Ticket Status:</label>
                                            <select v-model="edit.status" class="form-control">
                                                <option v-for="(text, code) in status.ticket" v-bind:value="code">@{{ text }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ticket-modal-ticket-configure-local_shipment-method" class="control-label">Local Shipment Method:</label>
                                            <input type="text" v-model="edit.localShipment.method" id="ticket-modal-ticket-configure-local_shipment-method" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ticket-modal-ticket-configure-local_shipment-price" class="control-label">Local Shipment Price:</label>
                                            <input type="text" v-model="edit.localShipment.price" id="ticket-modal-ticket-configure-local_shipment-price" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div> {{-- /#ticket-modal-ticket-configure --}}

                            <div id="ticket-modal-ticket-items" class="tab-pane">
                                <div class="row">
                                    <div class="col-sm-12 text-right">
                                        <button type="button" class="btn btn-success" v-on:click="addEditItem()">Add Item</button>
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
                                                <input type="text" v-model="edit.items[index].title" class="form-control" placeholder="title">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-1 text-center">
                                            @{{ index + 1 }} <br>
                                            <label><input type="checkbox" v-model="edit.items[index].deleted"> Del</label>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" v-model="edit.items[index].url" class="form-control" placeholder="URL">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" v-model="edit.items[index].price" class="form-control" placeholder="Price">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <input type="text" v-model="edit.items[index].note" class="form-control" placeholder="Item Note">
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <div id="ticket-modal-ticket-japan_shipments" class="tab-pane">
                                <div class="row">
                                    <div class="col-sm-12 text-right">
                                        <button type="button" class="btn btn-success" v-on:click="addEditJapanShipment()">Add In-Japan Shipment</button>
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
                                            <label><input type="checkbox" v-model="edit.japanShipments[index].deleted"> Del</label>
                                        </div>

                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <input type="text" v-model="edit.japanShipments[index].title" class="form-control" placeholder="Shipment Method">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input type="number" v-model="edit.japanShipments[index].price" class="form-control" placeholder="Shipment Price">
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
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" v-on:click="saveEdit()">Save</button>
                </div> {{-- /.modal-footer --}}
            </div> {{-- /.modal-content --}}
        </div> {{-- /.modal-dialog --}}
    </div> {{-- /#ticket-modal --}}

    <div id="category-modal" class="modal fade">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    Edit Categories
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="col-sm-12">
                        <div class="text-right">
                            <button type="button" class="btn btn-success" v-on:click="addCategory()">Add Category</button>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <hr>
                    </div>

                    <div class="col-sm-12">
                        <table class="table">
                            <thead><tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Value</th>
                                <th>Lower</th>
                                <th>Actions</th>
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
                                        <button type="button" class="btn btn-warning" v-on:click="undoRemoveCategory(index)">Undo</button>
                                    </td>
                                    <td v-else>
                                        <button type="button" class="btn btn-danger" v-on:click="removeCategory(index)">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="clearfix"></div>
                </div> {{-- /.modal-body --}}

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" v-on:click="saveCategories()">Save</button>
                </div> {{-- /.modal-footer --}}
            </div> {{-- /.modal-content --}}
        </div> {{-- /.modal-dialog --}}
    </div> {{-- /#category-modal --}}

@endsection

@section('footer')
    <script>
        const TicketStatus = {!! json_encode($ticket_status) !!};
        const ItemStatus = {!! json_encode($item_status) !!};
        const Rate = {{ $rate }};
    </script>
    <script src="{{ elixir('js/procurement-tickets-backend.js') }}"></script>
@endsection
