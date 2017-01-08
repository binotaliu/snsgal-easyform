@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-sm-12">
            <h2>New Procurement Ticket</h2>
        </div>

        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Procurement Ticket
                </div>

                <div class="panel-body">
                    <div v-if="errors.length > 0" class="col-md-12">
                        <div class="alert alert-warning">
                            <ul v-for="error in errors">
                                <li v-for="message in error">@{{ message }}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <form class="form-inline">
                            <div class="form-group">
                                <label class="sr-only" for="new-item-url">URL</label>
                                <input type="url" class="form-control" id="new-item-url" v-model="form.url" placeholder="URL">
                            </div>
                            <div class="form-group">
                                <label class="sr-only" for="new-item-name">Product Name</label>
                                <input type="url" class="form-control" id="new-item-name" v-model="form.title" placeholder="Product Name">
                            </div>
                            <div class="form-group">
                                <label class="sr-only" for="new-item-note">Note</label>
                                <input type="url" class="form-control" id="new-item-note" v-model="form.note" placeholder="Note">
                            </div>
                            <div class="form-group">
                                <label class="sr-only" for="new-item-price">Price</label>
                                <div class="input-group">
                                    <div class="input-group-addon">¥</div>
                                    <input type="url" class="form-control" id="new-item-price" v-model="form.price" placeholder="Price">
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary" v-on:click="pushItem">Add</button>
                        </form>

                        <table class="table table-striped">
                            <thead><tr>
                                <td>#</td>
                                <td>Product</td>
                                <td class="text-right">Price</td>
                                <td>Note</td>
                                <td>Extra Services</td>
                            </tr></thead>

                            <tbody v-if="items.length > 0">
                                <tr v-for="(item, index) in items">
                                    <td>@{{ index + 1 }}</td>
                                    <td>@{{ item.title }}<br>
                                        <small>
                                            <a v-bind:href="item.url" target="_blank">@{{ item.url }}</a>
                                        </small>
                                    </td>
                                    <td class="text-right">@{{ format(item.price) }}</tdc>
                                    <td>@{{ item.note }}</td>
                                    <td></td>
                                </tr>
                            </tbody>

                            <tfoot v-if="items.length > 0"><tr>
                                <td></td>
                                <td class="text-right">Summary</td>
                                <td class="text-right">@{{ summary() }}</td>
                                <td></td>
                                <td></td>
                            </tr></tfoot>
                            <tfoot v-else><tr>
                                <td colspan="5" class="text-center">Items will show here once you add an item</td>
                            </tr></tfoot>
                        </table>

                    </div>

                    <div class="col-md-8">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="ticket-contact-name">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="ticket-contact-name" v-model="customer.name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="ticket-contact-email">E-Mail</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="ticket-contact-email" v-model="customer.email">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="ticket-contact-contact">Contact</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="ticket-contact-contact" v-model="customer.contact">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="ticket-contact-note">Ticket Note</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="ticket-contact-note" v-model="note"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-4">

                    </div>

                    <div class="col-md-4 col-md-offset-8">
                        <button type="button" v-on:click="store()" class="btn btn-block btn-success">Send</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script src="{{ elixir('js/tickets-new.js') }}"></script>
@endsection
