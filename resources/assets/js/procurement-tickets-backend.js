const app = new Vue({
    el: '#app',
    data: {
        filter: {
            ticketStatus: 0,
            itemStatus: 0,
            customer: ''
        },
        tickets: [],
        edit: {
            id: 0,
            token: '',
            rate: 0,
            name: '',
            email: '',
            contact: '',
            note: '',
            status: 100,
            localShipment: {
                price: 0,
                method: ''
            },
            items: [],
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
        checkItemsStatus(items) {
            for (let i in items) {
                if (items[i].status == this.filter.itemStatus) return true;
            }

            return false;
        },
        format(currency, price) {
            return moneyFormatter.format(currency, price);
        },
        editTicket(index) {
            this.edit.id = this.tickets[index].id;
            this.edit.token = this.tickets[index].token;
            this.edit.status = this.tickets[index].status;
            this.edit.rate = this.tickets[index].rate;
            this.edit.name = this.tickets[index].name;
            this.edit.email = this.tickets[index].email;
            this.edit.contact = this.tickets[index].contact;
            this.edit.note = this.tickets[index].note;
            this.edit.localShipment.price = this.tickets[index].local_shipment_price;
            this.edit.localShipment.method = this.tickets[index].local_shipment_method;
            this.edit.items = this.tickets[index].items.slice(0);
            this.edit.japanShipments = this.tickets[index].japan_shipments.slice(0);

            $('#ticket-modal').modal('show');
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
            this.edit.japanShipments.push({
                title: '',
                price: 0,
                deleted: false
            });
        },
        saveEdit() {
            //@TODO: implement saveEdit method
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
        }
    }
});

app.fetchTickets();
