<template src="./sign-in.html">
</template>


<script>
    export default {

        data() {
            return {
                csrfToken: window.csrfToken,
                isVisibleDriver: true,
                isVisibleOwner: false,
                signInFormActive: true,
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
                avatarPreview: {
                    image: null,
                    name: null
                },
                driverFormStep: 1,
                vehiclePhotos: [],
            }
        },
        props: [
            'loginEndpoint',
            'driverEndpoint'
        ],
        created(){
            console.log(this.ownerRegistrationEndpoint);
        },
        mounted(){
        },
        methods: {
            submitDriverForm(){
                this.$validator.validateAll('step2').then((result) => {
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
            addVehiclePicture: function (file) {
                this.vehicleFiles.push(file);
                this.isVisibleDriver = true;
            },
            removeVehiclePicture: function (file) {
                let index = this.vehicleFiles.indexOf(file);
                if (index > -1) this.vehicleFiles.splice(index);
            },
            validateForm: function (scope) {
                this.$validator.validateAll(scope).then(isValid => {
                    if (isValid) {
                        let form = document.getElementById(scope);
                        form.submit();
                    }
                });
            },
            validateDriverForm(scope) {
                this.$validator.validateAll(scope).then(isValid => {
                    if (isValid) {

                        this.validateDriverFiles()
                            .then(response => {
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
                                .catch(function(){
                                    console.log('FAILURE!!');
                                });

                            })
                            .catch(error => {
                                toastr.error(error);
                            });
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

                for (let i = 0; i < this.vehicleFiles.length; i++) {
                    formData.append('vehicle_photos[]', this.vehicleFiles[i]);
                }

                formData.append('avatar', this.files[0]);

                return formData;
            }
        }
    }
</script>