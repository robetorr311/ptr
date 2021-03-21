<template src="./owner-transport.html"></template>

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
                bids: [],
                directionsService: null,
                directionsDisplay: null,
                departureMarker: null,
                destinationMarker: null,
                stripeHandler: null,
                selectedBid: null,
                view: 'details', // [details|rating]
                acceptedBid: null,
                comments: [],
                newComment: '',
                moreBids: true,
                moreQuestions: true,
                acceptedDriver: null,
            }
        },
        props: [
            'transport',
            'stripeKey',
        ],

        created(){
            this.user = window.user;
            this.transportDetails = this.transport;
            this.pets = this.transport.pets;
            this.loadBids();
            this.loadComments();

            for (let i = 0; i < this.transport.bids.length; i++) {
                if (this.transport.bids[i].status === 'accepted' || this.transport.bids[i].status === 'completed') {
                    this.acceptedBid = this.transport.bids[i];
                }
            }
        },
        mounted(){
            this.initMap();
            this.initStripe();
        },
        methods: {
            loadBids(){
                if (this.bids.length < this.transport.bids.length) {
                    let bids = this.transport.bids;
                    let count = 0;
                    for(let i = this.bids.length; i < bids.length && count < 4; i++){
                        count++;
                        this.bids.push(bids[i]);
                    }
                } else {
                    this.moreBids = false;
                }

                if(this.bids.length === this.transport.bids.length)
                    this.moreBids = false;
            },
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
            initStripe() {
                this.stripeHandler = StripeCheckout.configure({
                    //key: this.stripeKey,
                    key: '',
                    image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
                    locale: 'auto',
                    zipCode: true,
                    token: (token) => {
                        // You can access the token ID with `token.id`.
                        // Get the token ID to your server-side code for use.
                        showLoader();
                        console.log(token);
                        axios.post('/stripe/getpayment/' + this.selectedBid.id, token)
                            .then(response => {
                                hideLoader();
                                window.scrollTo(0,0);
                                toastr.success(response.data);
                                Vue.set(this.transportDetails, 'status', 'in-progress');
                                for (let i = 0; i < this.bids.length; i ++) {
                                    if (this.bids[i].id === this.selectedBid.id) {
                                        Vue.set(this.bids[i], 'status', 'accepted');
                                    }
                                }
                            })
                            .catch(error => {
                                hideLoader();
                                toastr.error('Error! Payment failed');
                                console.log(error.response);
                            });

                    }
                });
                window.addEventListener('popstate', () => {
                    this.stripeHandler.close();
                });
            },
            checkout(event, bid) {
                this.selectedBid = bid;
                this.acceptedBid = bid;
                this.stripeHandler.open({
                    name: 'Pet Travel Hub',
                    image: '/img/marker.png',
                    description: '',
                    amount:  parseInt(bid.amount)*100
                });
                event.preventDefault();
            },
            reject(event, bid) {
                axios.delete(`/owner/bid/${bid.id}`)
                    .then(response => {
                        window.location.replace('');

                    })
                    .catch(error => {
                        window.location.replace('');
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
                    draggable:true,
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
                        console.log('Directions request failed due to ' + status);
                    }
                });
            },
            openView(view) {
                this.view = view;
            },
            completedTransport() {
                this.transportDetails.status = 'completed';
                this.openView('details');
            },
            showPayoutCode() {
                showLoader();
                axios.get('/owner/transports/' + this.transportDetails.id + '/payout-code')
                    .then(response => {
                        hideLoader();
                        swal(
                            response.data.code,
                            'Please give the payout code above to the driver!',
                            'success'
                        );

                    })
                    .catch(error => {
                        hideLoader();
                        console.log(error);
                    });

            },
            showDriverData(bid) {
                showLoader();
                axios.get('/owner/transports/' + bid.id + "/driver")
                    .then(response => {
                        console.log('/owner/transports/' + bid.id + "/driver");
                        hideLoader();
                        swal({
                            title: 'Driver\'s info',
                            html: response.data.driver.email  + '<br>' + response.data.driver.phone,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok'
                        });
                    }).catch(error => {
                        hideLoader();
                        console.log(error);
                    });
            },
            getDriverFromBid(driverId) {
                axios.get(`/owner/${driverId}`)
                    .then(response => {
                        console.log(response);
                    })
            }
        },
        computed: {
            leadingBid() {

                if (this.bids.length > 0) {
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