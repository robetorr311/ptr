<template src="./owner-transport-edit.html"></template>

<script>
    import DatePicker from 'vue2-datepicker'
    export default {
        components: { DatePicker },
        data() {
            return {
                form: {
                    departure_date: '',
                    arrival_date: '',
                    arrival_type: 'On',
                    departure_type: 'Before'
                }
            }
        },
        props: [
            'transport',
            'transportEndpoint'        
        ],
        created(){
            // console.log(this.transport);

            // this.form.departure_date = this.transport.departure_date;
            // this.form.arrival_date = this.transport.arrival_date;
            // this.form.arrival_type = this.transport.arrival_type;
            // this.form.departure_type = this.transport.departure_type;
        },
        mounted(){
            $('#startDate').datepicker({
                minDate: new Date(),
                onSelect: ()=> {
                    this.form.departure_date = $('#startDate').datepicker().val();
                }
            });
            $('#endDate').datepicker({
                minDate: new Date(),
                onSelect: ()=> {
                    this.form.arrival_date = $('#endDate').datepicker().val();
                }
            });
            // this.form.departure_date = moment().format('MM/DD/YYYY');
            // this.form.arrival_date = moment().format('MM/DD/YYYY');
            this.form.departure_date = moment(this.transport.departure_date).format('MM/DD/YYYY');

            this.form.arrival_date = moment(this.transport.arrival_date).format('MM/DD/YYYY');
        },
        filters: {
            day(date) {
                return moment(date, 'MM-DD-YYYY').format('DD');
            },
            month(date) {
                return moment(date, 'MM-DD-YYYY').format('MMM');
            },
            year(date) {
                return moment(date, 'MM-DD-YYYY').format('YYYY');
            }
        },
        methods: {
            initDates() {
                this.form.departure_date = this.transport.departure_date;
                this.form.arrival_date = this.transport.arrival_date;
            },
            initAutocomplete() {
                // Create the autocomplete object, restricting the search to geographical
                // location types.
                this.autocomplete1 = new google.maps.places.Autocomplete(
                    /** @type {!HTMLInputElement} */(document.getElementById('autocomplete1')),
                    {types: ['geocode']});

                // When the user selects an address from the dropdown, populate the address
                // fields in the form.
                this.autocomplete1.addListener('place_changed', this.fillInAddress1);

                this.autocomplete2 = new google.maps.places.Autocomplete(
                    /** @type {!HTMLInputElement} */(document.getElementById('autocomplete2')),
                    {types: ['geocode']});

                // When the user selects an address from the dropdown, populate the address
                // fields in the form.
                this.autocomplete2.addListener('place_changed', this.fillInAddress2);
            },
            openView(view) {
                this.$emit('open-view', view);
            },
            validateForm: function (scope) {
                this.$validator.validateAll(scope).then(isValid => {
                    if (isValid) {
                        if (scope === 'petForm') this.addNewPet();
                    }
                });
            },
            showErrors(errors) {
                for (let property in errors) {
                    if (errors.hasOwnProperty(property)) {

                        toastr.error(errors[property][0]);

                    }
                }
            },
            resetPetForm() {
                for(let property in this.pet) {
                    if (this.pet.hasOwnProperty(property)) {
                        this.pet[property] = '';
                    }
                }
            },
            submitForm() {
                showLoader();

                axios.put(this.transportEndpoint, this.form)
                    .then(response => {
                        toastr.success('Transport created');

                        hideLoader();

                        swal({
                            title: 'Success!',
                            text: "Transport edited successfully!",
                            type: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            window.location.replace('/owner/transports/' + response.data.id);
                        })

                    })
                    .catch(error => {
                        this.showErrors(error.response.data.errors);

                        hideLoader();
                    });
            }
        }
    }
</script>