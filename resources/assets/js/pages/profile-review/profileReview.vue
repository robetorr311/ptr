<template src="./profile-review.html">
</template>


<script>
    import StarRating from 'vue-star-rating';

    export default {
        data() {
            return {
                rating: "No Rating Selected",
                currentRating: "No Rating",
                currentSelectedRating: "No Current Rating",
                form: {
                    driver: 0,
                    vehicle: 0,
                    communication: 0,
                    overall: 0,
                    comment: ''
                },
                user: null
            }
        },
        props: [
            'bid'
        ],
        created(){
            this.user = window.user;


        },
        methods: {
            setRating: function(rating) {
                this.rating = "You have Selected: " + rating + " stars";
            },
            showCurrentRating: function(rating) {
                this.currentRating = (rating === 0) ? this.currentSelectedRating : "Click to select " + rating + " stars"
            },
            setCurrentSelectedRating: function(rating) {
                this.currentSelectedRating = "You have Selected: " + rating + " stars";
            },
            submitReview() {
                showLoader();

                axios.post('/owner/bid/' + this.bid.id + '/finish', this.form)
                    .then(response => {
                        hideLoader();
                        swal(
                            response.data.payout_code,
                            'Transport completed! Please give the payout code above to the driver!',
                            'success'
                        );

                        this.$emit('completed');
                    })
                    .catch(error => {
                        hideLoader();
                        console.log(error.response.data.errors);

                        this.showErrors(error.response.data.errors);

                    });

                // swal(
                //     'Good job!',
                //     'You clicked the button!',
                //     'success'
                // )
            },
            showErrors(errors) {
                for (let property in errors) {
                    if (errors.hasOwnProperty(property)) {

                        toastr.error(errors[property][0]);

                    }
                }
            },
        },
        components: {
            'star-rating': StarRating
        }
    }
</script>