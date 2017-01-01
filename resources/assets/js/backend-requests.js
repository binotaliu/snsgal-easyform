const app = new Vue({
    el: '#app',
    data: {
        modalContent: {},
        sender: {
            name: '',
            phone: '',
            postcode: '',
            address: ''
        },
        exportForm: {
            loading: false,
            sender: {
                name: '',
                phone: '',
                postcode: '',
                address: ''
            },
            package: {
                products: '',
                amount: 0,
                collect: 'N',

                vendor: 'TCAT',
                temperature: '0001',
                distance: '01',
                specification: '0001'
            }
        },
        requests: []
    },
    methods: {
        fetchRequests: function () {
            let resource = this.$resource('/api/shipment/requests');

            resource.get().then((response) => {
                return response.json();
            }).then((json) => {
                this.$set(this, 'requests', json);
            });
        },
        fetchSender: function () {
            let resource = this.$resource('/api/shipment/sender_profile');

            resource.get().then((response) => {
                return response.json();
            }).then((json) => {
                this.$set(this, 'sender', extend({
                    name: '',
                    phone: '',
                    postcode: '',
                    address: ''
                }, json));
            });
        },
        showRequest: function (index) {
            this.modalContent = extend({loading: false}, this.requests[index]);

            this.exportForm.loading = false;

            this.exportForm.sender.name = this.sender.name;
            this.exportForm.sender.phone = this.sender.phone;
            this.exportForm.sender.postcode = this.sender.postcode;
            this.exportForm.sender.address = this.sender.address;

            this.exportForm.package.products = '';
            this.exportForm.package.amount = 0;
            this.exportForm.package.collect = false;
            this.exportForm.package.vendor = 'TCAT';
            this.exportForm.package.temperature = 'normal';
            this.exportForm.package.distance = 'other';
            this.exportForm.package.specification = '60';
            $('#request-modal').modal('show');
        },
        showSender: function () {
            $('#sender-modal').modal('show');
        },
        saveSender: function () {
            let resource = this.$resource('/api/shipment/sender_profile');
            resource.save(this.sender);
        },
        exportTicket: function () {
            let resource = this.$resource('/api/shipment/request{/token}/export');

            this.modalContent.loading = true;

            resource.save({token: this.modalContent.token}, this.exportForm).then((response) => {
                return response.json()
            }, (response) => {
                // @TODO: error handle
            }).then((json) => {
                let index = this.requests.findIndex((i) => {
                    return (i.token == this.modalContent.token)
                });

                this.requests[index].exported = json.AllPayLogisticsID;
                $('#request-modal').modal('hide');
                this.modalContent.loading = false;
            });
        }
    }
});

app.fetchRequests();
app.fetchSender();
