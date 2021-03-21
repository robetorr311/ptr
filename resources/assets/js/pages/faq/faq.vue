<template src="./faq.html">
</template>


<script>
    export default {
        data() {
            return {
                questions: [
                    {
                        title: 'General Question',
                        text: 'Where do I start?',
                        answer: 'You submit your transport for free in our system.  Wait for bids from approved drivers. Then accept the bid you want.',
                        isActive: false
                    },
                    {
                        title: 'General Question',
                        text: 'How much will this cost?',
                        answer: 'Every pet move is different, which makes standard pricing difficult to establish. Factors like pet size, breed, origin, destination, and even time of year can all come into play when determining costs. The pet driver will determine what makes sense for them. ',
                        isActive: false
                    },
                    {
                        title: 'General Question',
                        text: 'How do I pay?',
                        answer: 'You only pay when you accept a bid. Stripe Payment Systems processing all payments and has a separate secure platform. You then pay the pet driver with a code when the delivery is completed. ',
                        isActive: false
                    },
                    {
                        title: 'General Question',
                        text: 'Can my pet fly in the cabin with me?',
                        answer: 'Understandably, most pet owners would rather keep their pets by their side when they fly, but airline and country rules often make this impossible. Tiny pets can sometimes fly in the cabin, especially if you’re flying within the United States, but most international flights require pets to fly via cargo.',
                        isActive: false
                    },
                    {
                        title: 'General Question',
                        text: 'Is my pet safe in the cargo hold? ',
                        answer: 'Yes.  We also have options to have someone drive your pet in their car. Door to Door. ',
                        isActive: false
                    },
                ]
            }
        },
        props: [

        ],
        created(){},
        mounted(){},
        methods: {
            toggleQuestion: function (question) {
                question.isActive = !question.isActive;
            }
        }
    }
</script>