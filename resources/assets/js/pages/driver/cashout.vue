<template src="./cashout.html">
</template>


<script>
    export default {
        data() {
            return {
                bids: null,
                timeOut: null,
            }
        },
        props: [
            'allBids'
        ],
        created(){
            this.bids = Object.assign({}, this.allBids);
        },
        mounted(){

        },
        methods: {
            verifyCode(bid) {
                /* If timeout already running clear it first */
                clearTimeout(this.timeOut);

                /* Add timeout so when user change search param we dont send imidetly req to server */
                this.timeOut = setTimeout(() => {

                    // Vue.set(bid, 'status', 'asdasd');
                    if (bid.payout_code === bid.input_code) {
                        Vue.set(bid, 'error', false);
                    } else {
                        Vue.set(bid, 'error', true);
                    }

                }, 700);
            },
            submitCode(bid) {
                if (bid.payout_code === bid.input_code) {
                    Vue.set(bid, 'error', false);

                    showLoader();
                    axios.post('/driver/cashout/' + bid.id , {
                        'code': bid.input_code
                    })
                        .then(response => {
                            hideLoader();
                            toastr.success(response.data);
                            bid.status = 'cashed';
                            //Vue.set(bid, 'status', 'cashed');
                        })
                        .catch(error => {
                            hideLoader();
                            toastr.error(error.response.data);
                            console.log(error.response.data);
                        })
                } else {
                    Vue.set(bid, 'error', true);
                    toastr.error('Invalid code!');
                }
            }
        }
    }
</script>