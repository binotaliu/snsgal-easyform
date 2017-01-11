const app = new Vue({
    el: '#app',
    data: {
        filter: {
            ticketStatus: 0,
            itemStatus: 0,
            customer: ''
        },
        tickets: [],
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
        }
    }
});

app.fetchTickets();
