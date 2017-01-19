"use strict";

const app = new Vue({
    el: '#app',
    data: {
        items: [],
        form: {
            url: '',
            title: '',
            price: '',
            note: ''
        },
        rate: Rate,
        extraServices: ExtraServices,
        shipment: 0,
        note: '',
        customer: {
            name: '',
            email: '',
            contact: ''
        },
        edit: 0,
        errors: []
    },
    computed: {
        summary() {
            let total = 0;
            for (let i in this.items) {
                let item = this.items[i];
                total += item.price * this.rate;
                for (let j in item.extraServices) {
                    let service = item.extraServices[j];
                    if (!service) continue;
                    total += this.extraServices[j].price;
                }
            }
            return total;
        }
    },
    methods: {
        pushItem() {
            let extraServicesFields = {};
            for (let i in this.extraServices) {
                extraServicesFields[this.extraServices[i].id] = false;
            }
            this.items.push({
                url: this.form.url,
                title: this.form.title,
                price: parseInt(this.form.price),
                note: this.form.note,
                extraServices: extraServicesFields
            });
            this.form.url = '';
            this.form.title = '';
            this.form.price = '';
            this.form.note = '';
        },
        removeItem(index) {
            this.items.splice(index, 1);
        },
        format(currency = 'JPY', price, fraction = 0) {
            return moneyFormatter.format(currency, price, fraction);
        },
        toTwd(price) {
            return (price * this.rate).toFixed(2);
        },
        showEdit(index) {
            this.edit = index;
            $('#edit-modal').modal('show');
        },
        store() {
            let resource = this.$resource('/api/procurement/tickets');

            Splash.enable('windcatcher');
            return resource.save({
                name: this.customer.name,
                email: this.customer.email,
                contact: this.customer.contact,
                shipment: this.shipment,
                note: this.note,
                items: this.items
            }).then((response) => {
                return response.json();
            }, (response) => {
                return response.json();
            }).then((json) => {
                if (typeof json.token !== 'undefined') {
                    window.location.href = `/procurement/tickets/${json.token}`;
                    return json;
                }

                for (let i in json) {
                    this.errors.push(json[i]);
                }
                Splash.destroy();
                return json;
            });
        }
    }
});
