require('./bootstrap');

import AOS from 'aos';

import VeeValidate from 'vee-validate';

import StarRating from 'vue-star-rating';

import VueClip from 'vue-clip';

// ES6 Modules or TypeScript
import swal from 'sweetalert2';

window.swal = swal;


window.Vue = require('vue');



// Vue.config.devtools = true;

Vue.use(VeeValidate);
Vue.use(VueClip);

//  PAGES
Vue.component('header-component', require('./components/header/header.vue'));
Vue.component('star-rating', StarRating);
Vue.component('footer-component', require('./components/footer/footer.vue'));
Vue.component('faq', require('./pages/faq/faq.vue'));
Vue.component('sign-in-area', require('./pages/landing/sing-in-area/sign-in.vue'));
Vue.component('profile-owner', require('./pages/profile/owner/profile.vue'));
Vue.component('profile-review', require('./pages/profile-review/profileReview.vue'));
Vue.component('search-base', require('./pages/search/base/searchBase.vue'));
Vue.component('my-transport', require('./pages/my-transport/myTransport.vue'));
Vue.component('search-geo', require('./pages/search/geo/searchGeo.vue'));
Vue.component('post-transport', require('./pages/post-transport/postTransport.vue'));
Vue.component('owner-landing', require('./pages/owner/owner-landing.vue'));
Vue.component('owner-profile', require('./pages/owner/owner-profile.vue'));
Vue.component('driver-profile', require('./pages/driver/driver-profile.vue'));
Vue.component('owner-transport', require('./pages/owner/owner-transport.vue'));
Vue.component('owner-transport-edit', require('./pages/owner/owner-transport-edit.vue'));
Vue.component('cashout', require('./pages/driver/cashout.vue'));
Vue.component('driver-transport', require('./pages/driver/driver-transport.vue'));
Vue.component('notifications', require('./components/header/notifications/notifications.vue'));
Vue.component('driver-profile-guest', require('./pages/owner/driver-profile-guest.vue'));
Vue.component('register', require('./pages/register/register.vue'));
Vue.component('register-driver', require('./pages/register/register-driver.vue'));
Vue.component('register-owner', require('./pages/register/register-owner.vue'));



Vue.prototype.$showValidationErrors = function (data) {
    if (data.errors) {
        for (let property in data.errors) {
            if (data.errors.hasOwnProperty(property)) {
                data.errors[property].forEach(message => {
                    toastr.error(message);
                });
            }
        }
    } else if (data.error) {
        toastr.error(data.error);
    } else {
        console.log('Invalid error object');
        console.log(data);
    }
}

Vue.mixin({
    methods: {
        showValidationErrors(data) {
            if (data.errors) {

                for (let property in data.errors) {
                    if (data.errors.hasOwnProperty(property)) {

                        data.errors[property].forEach(message => {
                            toastr.error(message);
                        });
                    }
                }

            } else {
                console.log('Invalid error object');
                console.log(data);
            }
        }
    }
});

let App = window.App = new Vue({
    el: '#app',
    data: {
    },
    created(){

    },
    mounted(){
        hideLoader();

        AOS.init();

        document.addEventListener('DOMContentLoaded', function () {

            // Get all "navbar-burger" elements
            var $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

            // Check if there are any navbar burgers
            if ($navbarBurgers.length > 0) {

                // Add a click event on each of them
                $navbarBurgers.forEach(function ($el) {
                    $el.addEventListener('click', function () {

                        // Get the target from the "data-target" attribute
                        var target = $el.dataset.target;
                        var $target = document.getElementById(target);

                        // Toggle the class on both the "navbar-burger" and the "navbar-menu"
                        $el.classList.toggle('is-active');
                        $target.classList.toggle('is-active');

                    });
                });
            }

        });
    },
    methods: {

    }
});
