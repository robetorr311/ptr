<template src="./owner-landing.html"></template>

<script>


    export default {
        data() {
            return {
                view: 'landing', // [landing, post-offer, my-transports]
            }
        },
        props: [
            'addPetEndpoint',
            'pets',
            'transportEndpoint',
            'myTransportsEndpoint'
        ],
        created(){},
        mounted(){
        },
        methods: {
            openView(view) {
                window.scrollTo(0, 0);
                this.view = view;
            }
        }
    }
</script>