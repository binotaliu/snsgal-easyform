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
                collect: 'Y',

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
        createBatchForm: {
            data: '',
            method: 'cvs',
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
            Splash.enable('circular');
            return axios.get('/api/shipment/requests').then(({ data }) => {
                this.$set(this, 'requests', data);
                Splash.destroy();
            });
        },
        fetchSender: function () {
            return axios.get('/api/shipment/sender_profile').then(({ data }) => {
                this.$set(this, 'sender', Object.assign({}, {
                    name: '',
                    phone: '',
                    postcode: '',
                    address: ''
                }, data));
            });
        },
        showRequest: function (index) {
            this.modalContent = Object.assign({}, { loading: false }, this.filteredRequests[index]);

            this.exportForm.loading = false;

            this.exportForm.sender.name = this.sender.name;
            this.exportForm.sender.phone = this.sender.phone;
            this.exportForm.sender.postcode = this.sender.postcode;
            this.exportForm.sender.address = this.sender.address;

            this.exportForm.package.products = '';
            this.exportForm.package.amount = 0;
            this.exportForm.package.collect = 'Y';
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

            Splash.enable('circular');
            return axios.post(`/api/shipment/requests/${this.filteredRequests[this.archive].token}/archive`)
                .then(() => {
                    Splash.destroy();
                    $('#archive-modal').modal('hide');

                    this.requests.splice(this.archive, 1);
                });
        },
        showSender: function () {
            $('#sender-modal').modal('show');
        },
        saveSender: function () {
            Splash.enable('circular');
            return axios.post('/api/shipment/sender_profile', this.sender).then(() => {
                Splash.destroy();
            });
        },
        showCreate: function () {
            this.createForm.title = '';
            this.createForm.description = '';
            this.createForm.method = 'cvs';
            $('#create-modal').modal('show');
        },
        showCreateBatch: function () {
            this.createBatchForm.title = '';
            this.createBatchForm.method = 'cvs';
            $('#create-batch-modal').modal('show');
        },
        createRequest: function () {
            Splash.enable('circular');
            return axios.post('/api/shipment/requests', this.createForm).then(() => { // @TODO: not clear
                return this.fetchRequests().then(() => {
                    Splash.destroy();
                    $('#create-modal').modal('hide');
                });
            });
        },
        createRequests: function () {
            Splash.enable('circular');
            return axios.post('/api/shipment/requests/batch', this.createBatchForm).then(() => { // @TODO: not clear
                return this.fetchRequests().then(() => {
                    Splash.destroy();
                    $('#create-batch-modal').modal('hide');
                });
            });
        },
        exportTicket: function () {
            Splash.enable('circular');

            return axios
                .post(`/api/shipment/requests/${this.modalContent.token}/export`, this.exportForm)
                .then(({ data }) => {
                    this.fetchRequests().then(response => {
                        $('#request-modal').modal('hide');
                        Splash.destroy();
                    });

                    return data;
                });
        }
    }
});

app.fetchRequests();
app.fetchSender();
