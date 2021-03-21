<template src="./searchBase.html"></template>

<script>
    import Paginator from '../../../helpers/paginator.js';
    import DatePicker from 'vue2-datepicker'
    export default {
        components: { DatePicker },
        data() {
            return {
                time1: '',
                extraSearchVisible: false,
                form: {
                    biddable: '',
                    start_address: '',
                    destination_address: '',
                    pet_type: '',
                    pet_weight: '',
                    start_price: '',
                    end_price: ''
                },
                paginator: false,
                timeOut: null,
                bid: {
                    amount: '',
                    transport_id: ''
                }
           }
        },
        props: [
            'transports',
            'dataEndpoint',
            'geoEndpoint',
            'bidEndpoint'
        ],
        created(){
            this.paginator = new Paginator(this.transports);
        },
        mounted(){},
        methods: {
            enableFilters: function () {
                this.extraSearchVisible = true;
            },
            disableFilters: function () {
                this.extraSearchVisible = false;
            },
            submitForm() {
                /* If timeout already running clear it first */
                clearTimeout(this.timeOut);

                /* Add timeout so when user change search param we dont send imidetly req to server */
                this.timeOut = setTimeout(() => {
                    axios.get(this.dataEndpoint, {
                        params: this.prepareForm()
                    })
                        .then(response => {
                            this.paginator = new Paginator(response.data);
                            console.log(response.data);
                        })
                        .catch(error => {
                            console.log(error.response.data);
                        });
                }, 700);
            },
            prepareForm() {
                let formData = {};

                for (let property in this.form) {
                    if (this.form.hasOwnProperty(property)) {
                        if (this.form[property] !== '') {
                            formData[property] = this.form[property];
                        }
                    }
                }

                return formData;
            },
            validateForm() {
                this.closeBidModal();

                this.$validator.validateAll().then(isValid => {
                    if (isValid) {
                        showLoader();
                        axios.post(this.bidEndpoint + this.bid.transport_id, this.bid)
                            .then(response => {
                                toastr.success(response.data);

                                hideLoader();
                            })
                            .catch(error => {
                                console.log(error.response.data);

                                hideLoader();
                            })
                    }
                });
            },
            openBidModal(transport_id) {
                this.bid.transport_id = transport_id;
                $('#bidModal').modal('show');
            },
            closeBidModal() {
                $('#bidModal').modal('hide');
            }
        }
    }
</script>