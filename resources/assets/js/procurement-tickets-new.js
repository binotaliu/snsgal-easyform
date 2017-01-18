
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
        extraServices: ExtraServices,
        shipment: 0,
        note: '',
        customer: {
            name: '',
            email: '',
            contact: ''
        },
        errors: []
    },
    methods: {
        pushItem() {
            let extraServicesFields = {};
            for (let i in this.extraServices) {
                extraServicesFields[this.extraServices[i].id] = false;
            }
            console.log(extraServicesFields);
            this.items.push({
                url: this.form.url,
                title: this.form.title,
                price: parseInt(this.form.price),
                note: this.form.note,
                extraServices: extraServicesFields
            });
            console.log(this.items);
            this.form.url = '';
            this.form.title = '';
            this.form.price = '';
            this.form.note = '';
        },
        format(price) {
            return moneyFormatter.format('JPY', price);
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
        },
        summary() {
            let total = 0;
            for (let i in this.items) {
                total += this.items[i].price;
            }
            return this.format(total);
        }
    }
});