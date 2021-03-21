<template src="./my-transport.html"></template>

<script>
    import DatePicker from 'vue2-datepicker'

    export default {
        components: { DatePicker },
        data() {
            return {
                map: null,
                marker: null
            }
        },
        props: [
        ],
        created(){},
        mounted(){

        },
        methods: {
            initMap: function () {
                let uluru = {lat: -25.363, lng: 131.044};
                this.map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 4,
                    center: uluru
                });
                this.marker = new google.maps.Marker({
                    position: uluru,
                    map: this.map
                });
            }
        }
    }
</script>