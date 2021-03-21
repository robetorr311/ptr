<template src="./driver-profile-guest.html"></template>

<script>


    export default {
        data() {
            return {
                transports: [],
                user: null,
                avatar: null,
                changePassword: true,
                vehicle: {
                    type: '',
                    pictures: []
                }
            }
        },
        props: [
            'userData'
        ],
        created(){
            this.user = Object.assign(this.userData, {}, this.user);
        },
        mounted(){
            this.loadTransports();
        },
        methods: {
            loadTransports(){
                let bids = this.userData.bids;

                bids.forEach(bid => {
                    this.transports.push(bid.transport);
                });
            },
            prepareForm() {
                let formData = new FormData();
                if (this.avatar !== null) formData.append('avatar', this.avatar);

                if (this.user.old_password && this.user.new_password && this.user.old_password !== '' && this.user.new_password !== '') {
                    formData.append('old_password', this.user.old_password);
                    formData.append('new_password', this.user.new_password);
                }

                formData.append('name', this.user.name);
                formData.append('phone', this.user.phone);

                if (this.vehicle.type && this.vehicle.type !== ''){
                    formData.append('vehicle_type', this.vehicle.type);
                }

                if (this.vehicle.pictures.length > 0) {
                    this.vehicle.pictures.forEach(file => {
                        formData.append('vehicle_photo[]', file);
                    });
                }

                return formData;
            },
            submitProfile() {
                showLoader();

                let formData = this.prepareForm();
                axios.post('/profile/update', formData)
                    .then(response => {
                        toastr.success(response.data);
                        hideLoader();
                        this.closeProfileModal();
                    })
                    .catch(error => {
                        toastr.error(error.response.data);
                        hideLoader();
                    });
            },
            fileUploaded(event) {
                let files = [...event.target.files];
                this.avatar = files[0];
            },
            vehicleUploaded(event) {
                let files = [...event.target.files];
                files.forEach(file => {
                    this.vehicle.pictures.push(file);
                });
            },
            openProfileModal(){
                $('#profileModal').modal('show');
            },
            closeProfileModal(){
                $('#profileModal').modal('hide');
            },
            openView(view) {
                window.scrollTo(0, 0);
                this.view = view;
            },
            showErrors(errors) {
                for (let property in errors) {
                    if (errors.hasOwnProperty(property)) {

                        toastr.error(errors[property][0]);

                    }
                }
            },
            transportDetails(id) {
                return '/driver/transport/' + id;
            }
        },
        computed: {
            changePasswordText() {
                return this.changePassword ? 'Change Password' : 'Cancel';
            }
        }
    }
</script>