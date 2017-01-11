@extends('layouts.app')

@section('title', 'Procurement Tickets')

@section('content')
    <div class="container">
        <div class="col-sm-12">
            <h3>Procurement Tickets</h3>
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
                                                <a v-bind:href="'/procurement/tickets/' + ticket.token" target="_blank" class="btn btn-primary" v-on:click="viewTicket(index)">
                                                    View
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
                </div>

            </div>
        </div>

    </div>


@endsection

@section('footer')
    <script>
        var TicketStatus = {!! json_encode($ticket_status) !!};
        var ItemStatus = {!! json_encode($item_status) !!};
    </script>
    <script src="{{ elixir('js/procurement-tickets-backend.js') }}"></script>
@endsection
