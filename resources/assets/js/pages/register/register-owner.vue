<template src="./register-owner.html">
</template>


<script>
    export default {

        data() {
            return {
                csrfToken: window.csrfToken,
                isVisibleDriver: false,
                isVisibleOwner: true,
                signInFormActive: false,
                options: {
                    url: '/',
                    paramName: 'file',
                    maxFiles: 1,
                    acceptedFiles: {
                        extensions: ['image/*'],
                        message: 'You are uploading an invalid file'
                    },
                    maxFilesize: {
                        limit: 1,
                        message: '{{ filesize }} is greater than the {{ maxFilesize }}'
                    }
                },
                files: [],
                vehicleFiles: [],
                driverForm: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    phone: '',
                    vehicle: '',
                    avatar: '',
                    vehicle_photos: []
                },
                ownerForm: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    phone: '',
                    avatar: ''
                },
                avatarPreview: {
                    image: null,
                    name: null
                },
                driverFormStep: 1,
                vehiclePhotos: [],
            }
        },
        props: [
            'ownerRegistrationEndpoint',
            'loginEndpoint',
            'driverEndpoint'
        ],
        created(){
            //console.log("ENDPOINT: " + this.ownerRegistrationEndpoint);
        },
        mounted(){
        },
        methods: {
            submitDriverForm(){
                this.$validator.validateAll('step1').then((result) => {
                    if (result) {
                        let form = document.getElementById('driverForm');
                        form.submit();
                    }
                });
            },
            removeVehicle(photo, index){
                this.vehiclePhotos.splice(index, 1);
            },

            handleAvatarDriver(){
                ([...this.$refs.avatarDriver.files]).forEach(this.previewAvatar);
            },
            handleVehiclePhotos(){
                ([...this.$refs.vehicle.files]).forEach(this.previewVehicle);
            },
            previewVehicle(file){
                let vehicle = {};
                vehicle.name = file.name;
                let reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onloadend = () => {
                    vehicle.image = reader.result;
                    this.vehiclePhotos.push(vehicle);
                }
            },

            handleAvatar(){
                ([...this.$refs.avatar.files]).forEach(this.previewAvatar);
            },
            previewAvatar(file){

                this.avatarPreview.name = file.name;
                let reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onloadend = () => {
                    this.avatarPreview.image = reader.result;
                }
            },
            removeAvatar(){
                this.avatarPreview.image = null;
                this.avatarPreview.name = null;
            },
            sending (file, xhr, formData) {
                xhr.abort();
            },
            toggleDriver: function () {
                if (this.isVisibleOwner) this.isVisibleOwner = false;
                this.isVisibleDriver = true;
            },
            toggleOwner: function () {
                if (this.isVisibleDriver) this.isVisibleDriver = false;
                this.isVisibleOwner = true;
            },
            driverNextStep(){
                this.$validator.validateAll('step1').then((result) => {
                    if (result) {
                        this.driverFormStep = 2;
                    }
                });
            },
            hideForm: function (e) {
                //Check to see if we clicked on the open form button
                if (
                    (!document.getElementById('ownerLoginButton').contains(e.target)) &&
                    (!document.getElementById('signUp-owner').contains(e.target)) &&
                    (!document.getElementById('signIn').contains(e.target))
                ){
                    //If not, then we hide the form
                    this.isVisibleOwner = false;
                }
                if (!document.getElementById('driverLoginButton').contains(e.target) &&
                    (!document.getElementById('signIn').contains(e.target)) &&
                    (!document.getElementById('signUp-driver').contains(e.target))
                ){
                    //If not, then we hide the form
                    this.isVisibleDriver = false;
                }
            },
            signIn: function () {
                this.signInFormActive = true;
            },
            signUp: function () {
                this.signInFormActive = false;
            },
            addedFile: function (file) {
                this.files.push(file);
                this.isVisibleDriver = true;
            },
            removedFile: function (file) {
                let index = this.files.indexOf(file);
                if (index > -1) this.files.splice(index);
            },
            validateForm: function () {
                this.$validator.validateAll().then(isValid => {
                    if (isValid) {
                        let form = document.getElementById('ownerForm');
                        form.submit();
                    } else {
                        this.$showValidationErrors(error.response.data);
                    }
                });
            },
            validateOwnerForm() { 

                this.$validator.validateAll().then(isValid => {
                    if (isValid) {

                        let formData = this.prepareOwnerForm();
                        axios.post( this.ownerRegistrationEndpoint,
                            formData,
                        ).then(function(){
                            console.log('SUCCESS!!');
                        })
                        .catch((error) => {
                            console.log('FAILURE!!');
                            this.$showValidationErrors(error.response.data);
                        });
                    }
                });
            },
            prepareOwnerForm() {
                let formData = new FormData();

                for (let property in this.ownerForm) {
                    if (this.ownerForm.hasOwnProperty(property)) {
                        // console.log(this.ownerForm[property]);
                        formData.append(property, this.ownerForm[property]);
                    }
                }

                console.log(this.files[0]);

                formData.append('avatar', this.files[0]);

                return formData;
            }
        }
    }
</script>