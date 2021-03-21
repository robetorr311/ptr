<template src="./searchGeo.html"></template>

<script>
    import DatePicker from 'vue2-datepicker'
    import VueSlider from 'vue-slider-component'
    export default {
        components: {
            VueSlider,
            DatePicker
        },
        data () {
            return {
                map: null,
                marker: null,
                time1: null,
                value: 50,
                options: {
                    eventType: 'auto',
                    width: 'auto',
                    height: 6,
                    dotSize: 16,
                    dotHeight: null,
                    dotWidth: null,
                    min: 0,
                    max: 100,
                    interval: 1,
                    show: true,
                    speed: 0.5,
                    disabled: false,
                    piecewise: false,
                    piecewiseStyle: false,
                    piecewiseLabel: false,
                    tooltip: 'none',
                    tooltipDir: 'top',
                    reverse: false,
                    data: null,
                    clickable: true,
                    realTime: false,
                    lazy: false,
                    formatter: null,
                    bgStyle: {"background-color":"darkgreen"},
                    sliderStyle: {"background-color":"white"},
                    processStyle: {"background-color":"white"},
                    piecewiseActiveStyle: null,
                    piecewiseStyle: null,
                    tooltipStyle: null,
                    labelStyle: null,
                    labelActiveStyle: null
                },
                startAddress: '',
                autocomplete1: null,
                geocoder: null,
                startMarker: null,
                transports: [],
                rawTransports: [],
                selectedTransport: null,
                departureMarker: null,
                destinationMarker: null,
                gmarkers: [],
                directionsService: null,
                directionsDisplay: null,
                bid: {
                    amount: '',
                    transport_id: '',
                },
                buyBid: {
                    amount: '',
                    transport_id: '',
                },
                search: {
                    startDate: '',
                    endDate: '',
                    lat: '',
                    lng: '',
                    radius: '',
                    petType: '',
                    petWeight: '',
                    amountMin: '',
                    amountMax: ''
                },
                timeout: null,
                transportsPart: [],
                moreTransports: true,
                transport_budget: '',
                TODAY: new Date().toISOString().slice(0, 10)
            }
        },
        props: [
            'searchGeoEndpoint',
            'allTransports',
            'bidEndpoint'
        ],
        mounted () {
            this.initMap();
            this.initAutocomplete();

            this.transports = JSON.parse(this.allTransports);

            // for (let i = 0; i < this.rawTransports.length; i++) {
            //     if (this.rawTransports[i].departure_date.slice(0, 10) < this.TODAY) {
            //         this.transports.push(this.allTransports[i]);
            //     } 
            // }

            $('#startDate').datepicker({
                minDate: new Date(),
                onSelect: ()=> {
                    this.search.startDate = $('#startDate').datepicker().val();
                    this.submit();
                }
            });
            $('#endDate').datepicker({
                minDate: new Date(),
                onSelect: ()=> {
                    this.search.endDate = $('#endDate').datepicker().val();
                    this.submit();
                }
            });

            this.loadTransportsPart();
        },
        methods: {
            loadTransportsPart(){
                if (this.transportsPart.length < this.transports.length) {
                    let transports = this.transports;
                    let count = 0;
                    for(let i = this.transportsPart.length; i < transports.length && count < 10; i++){
                        count++;
                        this.transportsPart.push(transports[i]);
                    }
                    this.moreTransports = true;
                } else {
                    this.moreTransports = false;
                }

                if(this.transportsPart.length === this.transports.length)
                    this.moreTransports = false;
            },
            submit() {
                clearTimeout(this.timeout);

                this.timeout = setTimeout(() => {
                    this.prepareForm();

                    showLoader();
                    axios.get(this.searchGeoEndpoint, {
                        params: this.search
                    })
                        .then(response => {

                            let length = this.transports.length;

                            this.transports.splice(0, length);

                            let lengthPart = this.transportsPart.length;

                            this.transportsPart.splice(0, length);

                            for (let i = 0; i < response.data.length; i++) {
                                this.transports.push(response.data[i]);
                            }


                            this.loadTransportsPart();

                            let targetElement = document.getElementById('transport-results');

                            targetElement.scrollIntoView(true);

                            hideLoader();
                        })
                        .catch(error => {
                            console.log(error.response);
                            this.showValidationErrors(error.response.data);
                            hideLoader();
                        });
                }, 700);

            },
            clearSearch() {

                $('#startDate').datepicker().val('');
                $('#endDate').datepicker().val('');
                
              
                for(let property in this.search){
                    if(this.search.hasOwnProperty(property)){
                        if(this.search[property] == 'startDate' || this.search[property] == 'endDate') {
                            this.search[property].value = '';
                        }
                        this.search[property] = '';
                    }
                }
                this.startMarker = null;
                this.submit();
            },
            initMap: function () {
                let uluru = {lat: 40.712, lng: -74.006};
                this.map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 10,
                    center: uluru
                });

                google.maps.event.addListener(this.map, 'click', event => {
                    this.placeMarker(event.latLng);
                });

                this.geocoder = new google.maps.Geocoder();
                this.directionsService = new google.maps.DirectionsService;
                this.directionsDisplay = new google.maps.DirectionsRenderer;
                this.directionsDisplay.setMap(this.map);
                this.directionsDisplay.setOptions({suppressMarkers: true});
            },
            initAutocomplete() {
                // Create the autocomplete object, restricting the search to geographical
                // location types.
                this.autocomplete1 = new google.maps.places.Autocomplete(
                    /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
                    {types: ['geocode']});

                // When the user selects an address from the dropdown, populate the address
                // fields in the form.
                this.autocomplete1.addListener('place_changed', this.fillInAddress1);
            },
            fillInAddress1() {
                this.startAddress = this.autocomplete1.getPlace().formatted_address;

                if (this.startMarker) {
                    this.startMarker.setPosition(this.autocomplete1.getPlace().geometry.location);
                } else {
                    this.startMarker = this.createMarker(this.autocomplete1.getPlace().geometry.location);
                }

                this.getAddressFromMarker(this.startMarker.getPosition());
                google.maps.event.addListener(this.startMarker, "dragend", () => {
                    let position = this.startMarker.getPosition();
                    this.getAddressFromMarker(position);
                });

                this.map.setCenter(this.startMarker.getPosition());
            },
            placeMarker(location) {
                if (this.startMarker === null) {
                    this.startMarker = this.createMarker(location);

                    this.getAddressFromMarker(this.startMarker.getPosition(), true);
                    google.maps.event.addListener(this.startMarker, "dragend", () => {
                        let position = this.startMarker.getPosition();
                        this.getAddressFromMarker(position, true);
                    });

                }
            },
            createMarker(location) {


                let marker = new google.maps.Marker({
                    position: location,
                    draggable:true,
                    map: this.map,
                    icon: '/img/marker.png'
                });
                this.gmarkers.push(marker);

                this.map.setCenter(location);
                return marker;
            },
            setMarker(lat, lng) {

                let location = {lat: parseFloat(lat), lng: parseFloat(lng)};
                let marker = new google.maps.Marker({
                    position: location,
                    draggable:false,
                    map: this.map,
                    icon: '/img/marker.png'
                });

                this.map.setCenter(location);
                this.gmarkers.push(marker);

                return marker;
            },
            getAddressFromMarker(pos) {

                this.geocoder.geocode({
                    latLng: pos
                }, responses => {
                    if (responses && responses.length > 0) {
                        this.startAddress = responses[0].formatted_address;
                    } else {
                        this.startAddress = 'Cannot determine address at this location.';
                    }
                });
            },
            prepareForm() {

                if (this.startMarker) {
                    this.search.lat = this.startMarker.getPosition().lat();
                    this.search.lng = this.startMarker.getPosition().lng();
                    this.search.radius = this.value;
                }
            },
            selectTransport(transport) {

                this.removeMarkers();
                this.selectedTransport = transport;
                this.departureMarker = this.setMarker(this.selectedTransport.departure_lat, this.selectedTransport.departure_lng);
                this.destinationMarker = this.setMarker(this.selectedTransport.destination_lat, this.selectedTransport.destination_lng);

                this.calculateAndDisplayRoute();
                window.scrollTo(0,0);

            },
            calculateAndDisplayRoute() {
                this.directionsService.route({
                    origin: this.departureMarker.getPosition(),
                    destination: this.destinationMarker.getPosition(),
                    travelMode: 'DRIVING'
                }, (response, status) => {
                    if (status === 'OK') {
                        this.directionsDisplay.setDirections(response);
                    } else {
                        window.alert('Directions request failed due to ' + status);
                    }
                });
            },
            removeMarkers(){
                for(let i=0; i<this.gmarkers.length; i++){
                    this.gmarkers[i].setMap(null);
                }
            },
            validateForm() {
                this.closeBidModal();
                this.$validator.validateAll().then(isValid => {
                    if (isValid) {
                        showLoader();
                        console.log(this.bid);
                        axios.post(this.bidEndpoint + this.bid.transport_id, this.bid)
                            .then(response => {
                                toastr.success(response.data);

                                window.location.replace('/driver/transport/' + this.bid.transport_id);
                            })
                            .catch(error => {
                                console.log(error.response.data);

                                hideLoader();
                            })
                    }
                });
            },
            validateBuyForm() {
                this.closeBuyModal();
                axios.post(this.bidEndpoint + this.buyBid.transport_id, this.buyBid)
                            .then(response => {
                                toastr.success(response.data);

                                window.location.replace('/driver/transport/' + this.buyBid.transport_id);
                            })
                            .catch(error => {
                                console.log(error.response.data);

                                hideLoader();
                            })

            },
            openBidModal(transport_id) {
                this.bid.transport_id = transport_id;
                $('#bidModal').modal('show');
            },
            openBuyModal(transport_id, transport_budget) {
                this.buyBid.transport_id = transport_id;
                this.transport_budget = transport_budget;
                $('#buyModal').modal('show');
            },
            closeBidModal() {
                $('#bidModal').modal('hide');
            },
            closeBuyModal() {
                $('#buyModal').modal('hide');
            }
        }
    }
</script>