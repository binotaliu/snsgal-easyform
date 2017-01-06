
const app = new Vue({
    el: '#app',
    data: {
        items: [],
        form: {
            url: '',
            title: '',
            price: '',
            note: '',
            extraService: {}
        },
        note: '',
        customer: {
            name: '',
            email: '',
            contact: ''
        }
    },
    methods: {
        pushItem() {
            this.items.push({
                url: this.form.url,
                title: this.form.title,
                price: parseInt(this.form.price),
                note: this.form.note,
                extraService: {}
            });
            this.form.url = '';
            this.form.title = '';
            this.form.price = '';
            this.form.note = '';
        },
        format(price) {
            return moneyFormatter.format('JPY', price);
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
