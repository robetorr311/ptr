<template src="./driver-transport.html"></template>

<script>
    import DatePicker from 'vue2-datepicker'

    export default {
        components: { DatePicker },
        data() {
            return {
                user: null,
                map: null,
                marker: null,
                transportDetails: null,
                pets: null,
                directionsService: null,
                directionsDisplay: null,
                departureMarker: null,
                destinationMarker: null,
                view: 'details', // [details|rating]
                comments: [],
                newComment: '',
                bids: null,
                bid: {
                    amount: '',
                    transport_id: ''
                },
                buyBid: {
                    amount: this.transport.budget,
                    transport_id: this.transport.id,
                },
                moreQuestions: true,
                transport_budget: this.transport.budget,
            }
        },
        props: [
            'transport'
        ],
        created(){
            this.user = window.user;
            this.transportDetails = this.transport;
            this.pets = this.transport.pets;
            this.loadComments();
            this.bids = this.transport.bids;
        },
        mounted(){
            this.initMap();
        },
        methods: {
            loadComments(){
                if (this.comments.length < this.transport.comments.length) {
                    let comments = this.transport.comments;
                    let count = 0;
                    for(let i = this.comments.length; i < comments.length && count < 4; i++){
                        count++;
                        this.comments.push(comments[i]);
                    }
                } else {
                    this.moreQuestions = false;
                }

                if(this.comments.length === this.transport.comments.length)
                    this.moreQuestions = false;
            },
            submitComment(event) {
                if (this.newComment !== '') {
                    if (event.which == 13 || event.keyCode == 13) {
                        showLoader();
                        axios.post('/transport/'+ this.transportDetails.id +'/comment', {
                            content: this.newComment
                        })
                            .then(response => {
                                hideLoader();

                                this.comments.push(response.data);
                                toastr.success('Comment posted');
                                this.newComment = '';
                            })
                            .catch(error => {
                                hideLoader();
                                console.log(error.response.data);

                            });

                        return false;
                    }
                    return true;
                }
            },
            validateBuyForm() {
                this.closeBuyModal();
                axios.post('/driver/bid/' + this.buyBid.transport_id, { amount: this.transport.budget, transport_id: this.transport.id })
                            .then(response => {
                                toastr.success(response.data);

                                window.location.replace('/driver/transport/' + this.transport.id);
                            })
                            .catch(error => {
                                console.log(error.response.data);

                                hideLoader();
                            })

            },
            openBuyModal() {
                $('#buyModal').modal('show');
            },
            closeBuyModal() {
                $('#buyModal').modal('hide');
            },
            submitCommentClick() {
                if (this.newComment !== '') {
                    showLoader();
                    axios.post('/transport/'+ this.transportDetails.id +'/comment', {
                        content: this.newComment
                    })
                    .then(response => {
                        hideLoader();

                        this.comments.push(response.data);
                        toastr.success('Comment posted');
                        this.newComment = '';
                    })
                    .catch(error => {
                        hideLoader();
                        console.log(error.response.data);

                    });
                }
            },
            showDriverData(bid) {
                showLoader();
                axios.get('/driver/owner-details/' + this.transportDetails.id)
                    .then(response => {
                        console.log(response.data);
                        hideLoader();
                        swal({
                            title: 'Owner\'s info',
                            html: response.data.owner.email  + '<br>' + response.data.owner.phone,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok'
                        });
                    }).catch(error => {
                        hideLoader();
                        console.log(error);
                    });
            },
            initMap: function () {
                let uluru = {lat: -25.363, lng: 131.044};
                this.map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 10,
                    center: uluru
                });

                this.directionsService = new google.maps.DirectionsService;
                this.directionsDisplay = new google.maps.DirectionsRenderer;
                this.directionsDisplay.setMap(this.map);
                this.directionsDisplay.setOptions({suppressMarkers: true});

                this.departureMarker = this.createMarker({
                    lat: parseFloat(this.transportDetails.departure_lat),
                    lng: parseFloat(this.transportDetails.departure_lng)
                });

                this.destinationMarker = this.createMarker({
                    lat: parseFloat(this.transportDetails.destination_lat),
                    lng: parseFloat(this.transportDetails.destination_lng)
                });

                this.calculateAndDisplayRoute();
            },
            createMarker(location) {

                let marker = new google.maps.Marker({
                    position: location,
                    draggable:false,
                    map: this.map,
                    icon: '/img/marker.png'
                });
                this.map.setCenter(location);

                return marker;
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
            validateForm() {
                this.$validator.validateAll().then(isValid => {
                    if (isValid) {
                        showLoader();
                        axios.post('/driver/bid/' + this.bid.transport_id, this.bid)
                            .then(response => {
                                this.closeBidModal();
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
        },
        computed: {
            leadingBid() {

                if (this.bids && this.bids.length && this.bids.length > 0) {
                    let min = this.bids[0].amount;
                    for (let i = 0; i < this.bids.length; i++) {
                        if (min > this.bids[i].amount)
                            min = this.bids[i].amount;
                    }
                    return min;
                } else {
                    return 0;
                }
            }
        }
    }
</script>