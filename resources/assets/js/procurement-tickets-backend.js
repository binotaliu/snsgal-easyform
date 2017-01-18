"use strict";

const app = new Vue({
    el: '#app',
    data: {
        configs: {},
        configModal: {
            'procurement.minimum_fee': 0
        },
        shipmentMethods: {
            japan: [],
            local: []
        },
        localShipmentModal: [],
        japanShipmentModal: [],
        extraServices: [],
        extraServiceModal: [],
        filter: {
            customerSearch: '',
            itemSearch: '',
            ticketStatus: 0,
            itemStatus: 0,
            allowEmptyItem: true,
            customer: ''
        },
        archive: 0,
        tickets: [],
        categories: [],
        categoryModal: [],
        edit: {
            id: 0,
            token: '',
            rate: 0,
            name: '',
            email: '',
            contact: '',
            note: '',
            status: 100,
            localShipmentSelect: 0,
            localShipment: {
                price: 0,
                method: ''
            },
            items: [],
            japanShipmentSelect: 0,
            japanShipments: [],
            price: 0,
            total: []
        },
        rate: Rate,
        status: {
            ticket: TicketStatus,
            item: ItemStatus,
        }
    },
    computed: {
        filteredTickets() {
            let retval = [];
            for (let i in this.tickets) {
                let ticket = this.tickets[i];

                if (this.filter.ticketStatus != '0' &&
                    (this.filter.ticketStatus != ticket.status))
                    continue;

                if (this.filter.customerSearch.trim().length > 0 &&
                    (ticket.name + ticket.email + ticket.contact).match(this.filter.customerSearch.trim()) == null)
                    continue;

                let items = [];
                for (let j in ticket.items) {
                    let item = ticket.items[j];

                    if (this.filter.itemStatus != '0' &&
                        (this.filter.itemStatus != item.status))
                        continue;

                    if (this.filter.itemSearch.trim().length > 0 &&
                        (item.title + item.url).match(this.filter.itemSearch.trim()) == null)
                        continue;

                    items.push(item);
                }

                if (!this.filter.allowEmptyItem && items.length <= 0)
                    continue;

                retval.push({
                    id: ticket.id,
                    status: ticket.status,
                    token: ticket.token,
                    name: ticket.name,
                    email: ticket.email,
                    contact: ticket.contact,
                    rate: ticket.rate,
                    local_shipment_method: ticket.local_shipment_method,
                    local_shipment_price: ticket.local_shipment_price,
                    note: ticket.note,
                    items: items,
                    japan_shipments: ticket.japan_shipments,
                    created_at: ticket.created_at,
                    updated_at: ticket.updated_at,
                });
            }

            console.log(retval);
            return retval;
        }
    },
    methods: {
        fetchTickets() {
            let resource = this.$resource('/api/procurement/tickets');

            return resource.get().then((response) => {
                return response.json();
            }).then((json) => {
                this.$set(this, 'tickets', json);
                return json;
            });
        },
        fetchCategories() {
            let resource = this.$resource('/api/procurement/item_categories');

            return resource.get().then((response) => {
                return response.json();
            }).then((json) => {
                this.$set(this, 'categories', json);
                return json;
            });
        },
        fetchConfigs() {
            let resource = this.$resource('/api/configs');

            return resource.get().then((response) => {
                return response.json();
            }).then((json) => {
                this.$set(this, 'configs', json);
                return json;
            });
        },
        fetchShipmentMethods(type) {
            let resource = this.$resource('/api/procurement/shipment_methods{/type}');

            return resource.get({type: type}).then((response) => {
                return response.json();
            }).then((json) => {
                this.shipmentMethods[type] = json;
                return json;
            });
        },
        fetchExtraServices() {
            let resource = this.$resource('/api/procurement/item_extra_services');

            return resource.get().then((response) => {
                return response.json();
            }).then((json) => {
                this.extraServices = json;
                return json;
            });
        },
        format(currency, price) {
            return moneyFormatter.format(currency, price);
        },
        editTicket(index) {
            this.edit.id = this.filteredTickets[index].id;
            this.edit.token = this.filteredTickets[index].token;
            this.edit.status = this.filteredTickets[index].status;
            this.edit.rate = this.filteredTickets[index].rate;
            this.edit.name = this.filteredTickets[index].name;
            this.edit.email = this.filteredTickets[index].email;
            this.edit.contact = this.filteredTickets[index].contact;
            this.edit.note = this.filteredTickets[index].note;
            this.edit.localShipment.price = this.filteredTickets[index].local_shipment_price;
            this.edit.localShipment.method = this.filteredTickets[index].local_shipment_method;
            this.edit.items = this.filteredTickets[index].items.slice(0);
            this.edit.japanShipments = this.filteredTickets[index].japan_shipments.slice(0);

            $('#ticket-modal').modal('show');
        },
        setEditModalLocalShipmentMethod() {
            this.edit.localShipment.price = this.shipmentMethods.local[this.edit.localShipmentSelect].price;
            this.edit.localShipment.method = this.shipmentMethods.local[this.edit.localShipmentSelect].name;
        },
        updateEditRate() {
            this.edit.rate = this.rate;
        },
        addEditItem() {
            this.edit.items.push({
                status: 200,
                title: '',
                url: '',
                price: 0,
                note: '',
                deleted: false
            });
        },
        addEditJapanShipment() {
            let title = this.shipmentMethods.japan[this.edit.japanShipmentSelect].name;
            let price = this.shipmentMethods.japan[this.edit.japanShipmentSelect].price;
            this.edit.japanShipments.push({
                title: title,
                price: price,
                deleted: false
            });
        },
        addEditItemExtraService(index) {
            this.edit.items[index].extra_services.push({
                name: '',
                price: 0,
                new: true
            });
        },
        saveEdit() {
            let resource = this.$resource('/api/procurement/tickets{/token}');

            Splash.enable('windcatcher');

            return resource.update({token: this.edit.token}, this.edit).then((response) => {
                this.fetchTickets().then((response) => {
                    Splash.destroy();

                    $('#ticket-modal').modal('hide');
                });
                // @TODO: Error handle (form validation)
                return response;
            });
        },
        showCategoryModal() {
            this.$set(this, 'categoryModal', this.categories.slice(0));
            $('#category-modal').modal('show');
        },
        addCategory() {
            this.categoryModal.push({
                name: '',
                value: 0,
                lower: 40,
                new: true
            });
        },
        removeCategory(index) {
            if (this.categoryModal[index].new) {
                this.categoryModal.splice(index, 1);
            } else {
                this.categoryModal[index].deleted_at = true;
            }
        },
        undoRemoveCategory(index) {
            this.categoryModal[index].deleted_at = null;
        },
        saveCategories() {
            let resource = this.$resource('/api/procurement/item_categories');

            Splash.enable('windcatcher');
            return resource.save({categories: this.categoryModal}).then((response) => {
                this.fetchCategories().then((response) => {
                    Splash.destroy();

                    $('#category-modal').modal('hide');
                });
                return response;
            });
        },
        showConfigModal() {
            this.configModal['procurement.minimum_fee'] = this.configs['procurement.minimum_fee'];
            $('#config-modal').modal('show');
        },
        saveConfigs() {
            let resource = this.$resource('/api/configs');

            Splash.enable('windcatcher');
            return resource.save({configs: this.configModal}).then((response) => {
                this.fetchConfigs().then((response) => {
                    Splash.destroy();

                    $('#config-modal').modal('hide');
                    return response;
                });
                return response;
            });
        },
        showJapanShipmentModal() {
            this.japanShipmentModal = this.shipmentMethods.japan.slice(0);
            $('#japan_shipment-modal').modal('show');
        },
        addJapanShipmentMethod() {
            this.japanShipmentModal.push({
                name: '',
                price: 0,
                new: true
            });
        },
        saveJapanShipment() {
            let resource = this.$resource('/api/procurement/shipment_methods/japan');

            Splash.enable('windcatcher');
            return resource.save({methods: this.japanShipmentModal}).then((response) => {
                this.fetchShipmentMethods('japan').then((response) => {
                    Splash.destroy();

                    $('#japan_shipment-modal').modal('hide');
                    return response;
                });
                return response;
            });
        },
        removeJapanShipment(index) {
            if (this.japanShipmentModal[index].new) {
                this.japanShipmentModal.splice(index, 1);
                return;
            }

            this.japanShipmentModal[index].deleted_at = true;
        },
        undoRemoveJapanShipment(index) {
            this.japanShipmentModal[index].deleted_at = null;
        },
        showLocalShipmentModal() {
            this.localShipmentModal = this.shipmentMethods.local.slice(0);
            $('#local-modal').modal('show');
        },
        addLocalShipmentMethod() {
            this.localShipmentModal.push({
                type: 'standard',
                name: '',
                price: 0,
                show: true,
                new: true
            });
        },
        saveLocalShipment() {
            let resource = this.$resource('/api/procurement/shipment_methods/local');

            Splash.enable('windcatcher');
            return resource.save({methods: this.localShipmentModal}).then((response) => {
                this.fetchShipmentMethods('local').then((response) => {
                    Splash.destroy();

                    $('#local-modal').modal('hide');
                    return response;
                });
                return response;
            });
        },
        removeLocalShipment(index) {
            if (this.localShipmentModal[index].new) {
                this.localShipmentModal.splice(index, 1);
                return;
            }

            this.localShipmentModal[index].deleted_at = true;
        },
        undoRemoveLocalShipment(index) {
            this.localShipmentModal[index].deleted_at = null;
        },
        archiveConfirm(index) {
            this.archive = index;

            $('#archive-modal').modal('show');
        },
        archiveTicket() {
            let resource = this.$resource('/api/procurement/tickets{/token}/archive');

            Splash.enable('windcatcher');
            return resource.save({token: this.filteredTickets[this.archive].token}, {}).then((response) => {
                this.fetchTickets().then((response) => {
                    Splash.destroy();

                    $('#archive-modal').modal('hide');
                    return response;
                });

                return response;
            });
        },
        showExtraServiceModal() {
            this.extraServiceModal = this.extraServices;

            $('#extra_service-modal').modal('show');
        },
        addExtraService() {
            this.extraServiceModal.push({
                name: '',
                price: 0,
                show: true,
                new: true
            });
        },
        removeExtraService(index) {
            if (this.extraServiceModal[index].new) {
                this.extraServiceModal.splice(index, 1);
                return;
            }

            this.extraServiceModal[index].deleted_at = true;
        },
        undoRemoveExtraService(index) {
            this.extraServiceModal[index].deleted_at = null;
        },
        saveExtraServices() {
            let resource = this.$resource('/api/procurement/item_extra_services');

            Splash.enable('windcatcher');
            return resource.save({services: this.extraServiceModal}).then((response) => {
                this.fetchExtraServices().then((response) => {
                    Splash.destroy();

                    $('#extra_service-modal').modal('hide');
                    return response;
                });

                return response;
            });
        }
    }

});

app.fetchTickets();
app.fetchCategories();
app.fetchConfigs();
app.fetchShipmentMethods('japan');
app.fetchShipmentMethods('local');
app.fetchExtraServices();
