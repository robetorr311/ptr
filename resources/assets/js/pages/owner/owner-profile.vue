<template src="./owner-profile.html"></template>

<script>


    export default {
        data() {
            return {
                pets: [],
                transports: [],
                pet: {
                    name: '',
                    type: '',
                    weight: '',
                    size: ''
                },
                user: null,
                avatar: null,
                changePassword: true
            }
        },
        props: [
            'userData',
            'addPetEndpoint'
        ],
        created(){
            console.log(this.userData);
            this.pets = this.userData.pets;
            this.transports = this.userData.transports;
            this.user = Object.assign(this.userData, {}, this.user);
        },
        mounted(){
        },
        methods: {
            slideRight(){
                // var far = $('.pets-carousel').width();
                // let pos = $('.pets-carousel').scrollLeft() + 400;
                // $('.pets-carousel').animate( { scrollLeft: pos }, 1000, 'easeOutQuad' );
            },
            slideLeft(){
                // var far = $('.pets-carousel').width();
                // let pos = $('.pets-carousel').scrollLeft() - 400;
                // $('.pets-carousel').animate( { scrollLeft: pos }, 1000, 'easeOutQuad' );
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

                files.forEach(this.previewAvatar);
            },
            previewAvatar(file){
                // this.avatarPreview.name = file.name;
                let reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onloadend = () => {
                    this.user.avatar = reader.result;
                }
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
            openPetModal() {
                $('#addPetModal').modal('show');
            },
            closePetModal() {
                $('#addPetModal').modal('hide');
            },
            validateForm: function (scope) {
                this.$validator.validateAll(scope).then(isValid => {
                    if (isValid) {
                        if (scope === 'petForm') this.addNewPet();
                    }
                });
            },
            addNewPet() {
                axios.post(this.addPetEndpoint, this.pet)
                    .then(response => {

                        this.closePetModal();

                        this.insertPet(response.data);

                        toastr.success('Pet added');
                    })
                    .catch(error => {
                        this.showErrors(error.response.data.errors);
                    });
            },
            resetPetForm() {
                for(let property in this.pet) {
                    if (this.pet.hasOwnProperty(property)) {
                        this.pet[property] = '';
                    }
                }
            },
            showErrors(errors) {
                for (let property in errors) {
                    if (errors.hasOwnProperty(property)) {

                        toastr.error(errors[property][0]);

                    }
                }
            },
            insertPet(pet) {
                this.pets.push(pet);
            },
            transportDetails(id) {
                return '/owner/transports/' + id;
            }
        },
        computed: {
            changePasswordText() {
                return this.changePassword ? 'CHANGE PASSWORD' : 'CANCEL';
            }
        }
    }
</script>