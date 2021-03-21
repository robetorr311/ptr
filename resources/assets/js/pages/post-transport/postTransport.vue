<template src="./postTransport.html"></template>

<script>
    import DatePicker from 'vue2-datepicker'
    export default {
        components: { DatePicker },
        data() {
            return {
                map: null,
                numOfPets: 0,
                startMarker: null,
                destinationMarker: null,
                geocoder: null,
                startAddress: null,
                destinationAddress: null,
                autocomplete1: null,
                autocomplete2: null,
                pet: {
                    name: '',
                    type: '',
                    weight: '',
                    size: ''
                },
                pets: [],
                form: {
                    departure_date: '',
                    arrival_date: '',
                    arrival_type: 'On',
                    departure_type: 'Before',
                    biddable: false,
                    first_bid_buy: false,
                    departure_address: '',
                    departure_lat: '',
                    departure_lng: '',
                    destination_address: '',
                    destination_lat: '',
                    destination_lng: '',
                    pet_ids: [],
                    budget: ''
                }
            }
        },
        props: [
            'addPetEndpoint',
            'userPets',
            'transportEndpoint'
        ],
        created(){},
        mounted(){
            this.initDates();
            this.initMap();
            this.initAutocomplete();
            this.pets = this.userPets;
            this.selectedPet();
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
            this.form.departure_date = moment().format('MM/DD/YYYY');
            this.form.arrival_date = moment().format('MM/DD/YYYY');
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
                this.form.departure_date = moment().format('MM-DD-YYYY');
                this.form.arrival_date = moment().add(7, 'days').format('MM-DD-YYYY');
            },
            initMap() {
                let uluru = {lat: 40.712, lng: -74.006};
                this.map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 10,
                    center: uluru
                });

                google.maps.event.addListener(this.map, 'click', event => {
                    this.placeMarker(event.latLng);
                });

                this.geocoder = new google.maps.Geocoder();
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
            fillInAddress1() {
                this.startAddress = this.autocomplete1.getPlace().formatted_address;

                if (this.startMarker) {
                    this.startMarker.setPosition(this.autocomplete1.getPlace().geometry.location);
                } else {
                    this.startMarker = this.createMarker(this.autocomplete1.getPlace().geometry.location);
                }

                this.getAddressFromMarker(this.startMarker.getPosition(), true);
                google.maps.event.addListener(this.startMarker, "dragend", () => {
                    let position = this.startMarker.getPosition();
                    this.getAddressFromMarker(position, true);
                });

                this.map.setCenter(this.startMarker.getPosition());
            },
            fillInAddress2() {
                this.destinationAddress = this.autocomplete2.getPlace().formatted_address;

                if (this.destinationMarker) {
                    this.destinationMarker.setPosition(this.autocomplete2.getPlace().geometry.location);
                } else {
                    this.destinationMarker = this.createMarker(this.autocomplete2.getPlace().geometry.location);
                }

                this.getAddressFromMarker(this.destinationMarker.getPosition(), false);
                google.maps.event.addListener(this.destinationMarker, "dragend", () => {
                    let position = this.destinationMarker.getPosition();
                    this.getAddressFromMarker(position, false);
                });

                this.map.setCenter(this.destinationMarker.getPosition());
            },
            placeMarker(location) {
                if (this.startMarker === null) {
                    this.startMarker = this.createMarker(location);

                    this.getAddressFromMarker(this.startMarker.getPosition(), true);
                    google.maps.event.addListener(this.startMarker, "dragend", () => {
                        let position = this.startMarker.getPosition();
                        this.getAddressFromMarker(position, true);
                    });

                } else if (this.destinationMarker === null) {
                    this.destinationMarker = this.createMarker(location);

                    this.getAddressFromMarker(this.destinationMarker.getPosition(), false);
                    google.maps.event.addListener(this.destinationMarker, "dragend", () => {
                        let position = this.destinationMarker.getPosition();
                        this.getAddressFromMarker(position, false);
                    });
                }
            },
            createMarker(location) {
                 return new google.maps.Marker({
                    position: location,
                    draggable:true,
                    map: this.map,
                    icon: '/img/marker.png'
                });
            },
            getAddressFromMarker(pos, check) {

                this.geocoder.geocode({
                    latLng: pos
                }, responses => {
                    if (responses && responses.length > 0) {
                        if (check === true) {
                            this.startAddress = responses[0].formatted_address;
                        } else {
                            this.destinationAddress = responses[0].formatted_address;
                        }
                    } else {
                        if (check  === true) {
                            this.startAddress = 'Cannot determine address at this location.';
                        } else {
                            this.destinationAddress = 'Cannot determine address at this location.';
                        }
                    }
                });
            },
            openView(view) {
                this.$emit('open-view', view);
            },
            openPetModal() {
                $('#addPetModal').modal('show');
            },
            closePetModal() {
                $('#addPetModal').modal('hide');
            },
            validateForm: function (scope) {
                this.$validator.validateAll(scope).then(isValid => {
                    if (isValid) {
                        if (scope === 'petForm') this.addNewPet();
                    }
                });
            },
            addNewPet() {
                axios.post(this.addPetEndpoint, this.pet)
                    .then(response => {

                        this.insertPet(response.data);


                        this.closePetModal();

                        toastr.success('Pet added');
                        Vue.set(this.pet, 'selected', true);
                    })
                    .catch(error => {
                        this.showErrors(error.response.data.errors);
                    });
            },
            showErrors(errors) {
                for (let property in errors) {
                    if (errors.hasOwnProperty(property)) {

                        toastr.error(errors[property][0]);

                    }
                }
            },
            insertPet(pet) {
                this.pets.push(pet);
            },
            resetPetForm() {
                for(let property in this.pet) {
                    if (this.pet.hasOwnProperty(property)) {
                        this.pet[property] = '';
                    }
                }
            },
            unselectPet(pet) {
                Vue.set(pet, 'selected', false);
            },
            selectedPet(){
                if (this.pets.length > 0){
                  Vue.set(this.pets[0], 'selected', true);
                  this.numOfPets = this.numOfPets + 1;
                }              
            },
            selectPet(pet) {
                if (pet.selected === true && pet.selected) {
                    this.numOfPets = this.numOfPets - 1;
                    this.unselectPet(pet);
                } else {
                    Vue.set(pet, 'selected', true);
                    this.numOfPets = this.numOfPets + 1;
                }
            },
            prepareForm() {
                if (this.startMarker) {
                    this.form.departure_lat = this.startMarker.getPosition().lat();
                    this.form.departure_lng = this.startMarker.getPosition().lng();
                }

                if (this.destinationMarker) {
                    this.form.destination_lat = this.destinationMarker.getPosition().lat();
                    this.form.destination_lng = this.destinationMarker.getPosition().lng();
                }

                this.form.departure_address = this.startAddress;
                this.form.destination_address = this.destinationAddress;

                this.form.pet_ids = [];
                for (let i = 0; i < this.pets.length; i++) {
                    if (this.pets[i].selected) this.form.pet_ids.push(this.pets[i].id);
                }         
            },
            firstBidBuy() {
                if (this.form.biddable || !this.form.first_bid_buy) {
                    this.form.first_bid_buy = !this.form.first_bid_buy;
                    this.form.biddable = false;
                }
            },
            biddable() {
                if(this.form.first_bid_buy || !this.form.biddable) {
                    this.form.biddable = !this.form.biddable;
                    this.form.first_bid_buy = false;
                    this.form.budget=0;
                }

            },
            submitForm() {
                showLoader();

                this.prepareForm();

                axios.post(this.transportEndpoint, this.form)
                    .then(response => {
                        toastr.success('Transport created');

                        hideLoader();

                        swal({
                            title: 'Success!',
                            text: "Transport posted successfully!",
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