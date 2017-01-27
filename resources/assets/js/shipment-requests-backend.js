"use strict";

const app = new Vue({
    el: '#app',
    data: {
        modalContent: {},
        archive: 0,
        ecpayCodes: EcpayCodes,
        sender: {
            name: '',
            phone: '',
            postcode: '',
            address: ''
        },
        exportForm: {
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
        filter: {
            title: '',
            responded: '-1',
            exported: '-1',
            method: 'all'
        },
        createForm: {
            title: '',
            description: '',
            method: 'cvs'
        },
        requests: []
    },
    computed: {
        filteredRequests() {
            let filtered = [];
            for (let i in this.requests) {
                let request = this.requests[i];
                if (this.filter.title.length > 0 &&
                    request.title.match(this.filter.title) == null)
                    continue;

                if (this.filter.responded != '-1' &&
                    ((this.filter.responded == 'true' && !request.responded) ||
                    (this.filter.responded == 'false' && request.responded)))
                    continue;

                if (this.filter.exported != '-1' &&
                    ((this.filter.exported == 'true' && !request.exported) ||
                    (this.filter.exported == 'false' && request.exported)))
                    continue

                if (this.filter.method != 'all' &&
                    ((this.filter.method == 'cvs' && request.address_type != 'cvs') ||
                    (this.filter.method == 'standard' && request.address_type != 'standard')))
                    continue;

                filtered.push(request);
            }
            return filtered;
        },
    },
    methods: {
        fetchRequests: function () {
            let resource = this.$resource('/api/shipment/requests');

            Splash.enable('windcatcher');
            return resource.get().then((response) => {
                return response.json();
            }).then((json) => {
                this.$set(this, 'requests', json);
                Splash.destroy();
                return json;
            });
        },
        fetchSender: function () {
            let resource = this.$resource('/api/shipment/sender_profile');

            return resource.get().then((response) => {
                return response.json();
            }).then((json) => {
                this.$set(this, 'sender', extend({
                    name: '',
                    phone: '',
                    postcode: '',
                    address: ''
                }, json));
                return json;
            });
        },
        showRequest: function (index) {
            this.modalContent = extend({loading: false}, this.filteredRequests[index]);

            this.exportForm.loading = false;

            this.exportForm.sender.name = this.sender.name;
            this.exportForm.sender.phone = this.sender.phone;
            this.exportForm.sender.postcode = this.sender.postcode;
            this.exportForm.sender.address = this.sender.address;

            this.exportForm.package.products = '';
            this.exportForm.package.amount = 0;
            this.exportForm.package.collect = 'N';
            this.exportForm.package.vendor = 'TCAT';
            this.exportForm.package.temperature = '0001';
            this.exportForm.package.distance = '01';
            this.exportForm.package.specification = '0001';
            $('#request-modal').modal('show');
        },
        confirmArchive: function (index) {
            this.archive = index;

            $('#archive-modal').modal('show');
        },
        archiveRequest: function () {
            let resource = this.$resource('/api/shipment/requests{/token}/archive');

            Splash.enable('windcatcher');
            return resource.save({token: this.filteredRequests[this.archive].token}, {}).then((response) => {
                Splash.destroy();
                $('#archive-modal').modal('hide');

                this.requests.splice(this.archive, 1);

                return response;
            });
        },
        showSender: function () {
            $('#sender-modal').modal('show');
        },
        saveSender: function () {
            let resource = this.$resource('/api/shipment/sender_profile');

            Splash.enable('windcatcher');
            return resource.save(this.sender).then((response) => {
                Splash.destroy();
                return response;
            });
        },
        showCreate: function () {
            this.createForm.title = '';
            this.createForm.description = '';
            this.createForm.method = 'cvs';
            $('#create-modal').modal('show');
        },
        createRequest: function () {
            let resource = this.$resource('/api/shipment/requests');

            Splash.enable('windcatcher');
            return resource.save(this.createForm).then((response) => { // @TODO: not clear
                return this.fetchRequests().then((response) => {
                    Splash.destroy();
                    $('#create-modal').modal('hide');
                    return response;
                });
            });
        },
        exportTicket: function () {
            let resource = this.$resource('/api/shipment/requests{/token}/export');

            Splash.enable('windcatcher');

            return resource.save({token: this.modalContent.token}, this.exportForm).then((response) => {
                return response.json()
            }, (response) => {
                // @TODO: error handle
            }).then((json) => {
                this.fetchRequests().then(response => {
                    $('#request-modal').modal('hide');
                    Splash.destroy();
                });

                return json;
            });
        }
    }
});

app.fetchRequests();
app.fetchSender();