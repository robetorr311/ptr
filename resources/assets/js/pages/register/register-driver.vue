<template src="./register-driver.html">
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
                drivers_licence: '',
                driverForm: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    phone: '',
                    vehicle: '',
                    avatar: '',
                    drivers_licence: '',
                    vehicle_photos: []
                },
                licencePreview: {
                    image: null,
                    name: null
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
            // console.log(this.ownerRegistrationEndpoint);
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
            handleDriverLicence(){
                ([...this.$refs.drivers_licence.files]).forEach(this.previewLicence);
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
            previewLicence(file) {
                this.licencePreview.name = file.name;
                let reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onloadend = () => {
                    this.licencePreview.image = reader.result;
                }
            },
            removeAvatar(){
                this.avatarPreview.image = null;
                this.avatarPreview.name = null;
            },
            removeLicence(){
                this.licencePreview.image = null;
                this.licencePreview.name = null;
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
                        let form = document.getElementById('driverForm');
                        form.submit();
                    }
                });
            },

            validateDriverForm() { 

                this.$validator.validateAll().then(isValid => {
                    if (isValid) {


                        let formData = this.prepareDriverForm();
                        axios.post( this.driverEndpoint,
                            formData,
                            {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            }
                        ).then(function(){
                            console.log('SUCCESS!!');
                        })
                        .catch((error) => {
                            console.log('FAILURE!!');
                            this.$showValidationErrors(error.response.data);
                        });

                        // this.validateDriverFiles()
                        //     .then(response => {
                        //         let formData = this.prepareDriverForm();
                        //
                        //         axios.post( this.driverEndpoint,
                        //             formData,
                        //             {
                        //                 headers: {
                        //                     'Content-Type': 'multipart/form-data'
                        //                 }
                        //             }
                        //         ).then(function(){
                        //             console.log('SUCCESS!!');
                        //         })
                        //             .catch(function(){
                        //                 console.log('FAILURE!!');
                        //             });
                        //
                        //     })
                        //     .catch(error => {
                        //         toastr.error(error);
                        //     });
                    }
                });
            },
            validateDriverFiles() {
                return new Promise((resolve, reject) => {
                    if (this.files.length === 0)
                        reject('Please upload an avatar');

                    if (this.files.length > 1)
                        reject('Only one avatar picture allowed');

                    if (this.vehicleFiles.length === 0)
                        reject('Please upload vehicle photos');

                    resolve('Valid');
                })
            },
            prepareDriverForm() {
                let formData = new FormData();

                for (let property in this.driverForm) {
                    if (this.driverForm.hasOwnProperty(property)) {
                        formData.append(property, this.driverForm[property]);
                    }
                }

                let avatar = document.getElementById('avatar').files[0];
                let vehicleFiles = document.getElementById('driver-vehicle').files;
                let vehiclePhotos = [...vehicleFiles];

                formData.append('avatar', avatar);

                for (let i = 0; i < vehiclePhotos.length; i++) {

                    formData.append('vehicle_photos[]', vehiclePhotos[i]);

                }

                return formData;
            }
        }
    }
</script>