<template>
    <div>

        <div class="dropdown notifications-wrapper">
            <a class="notification-button" href="javascript:void(0);" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="/img/notification.png" alt="Notifications">
                <i v-if="notifications && notifications.length > 0" class="fa fa-circle"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right notifications"
                 :class="preview ? 'notifications-preview' : ''"
                 aria-labelledby="dropdownMenuButton">

                <a v-for="notification in notifications" :key="notification.id" class="dropdown-item" href="javascript:void(0)" @click="readNotification(notification)">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="notification-avatar" :style="'background-image: url(' + notification.data.sender.avatar + ')'"></div>
                        </div>
                        <div class="col-md-10">
                            <div class="description">
                                <p>{{ notification.data.text }}</p>
                            </div>
                            <div class="date">
                                {{ notification.created_at }}
                            </div>
                        </div>
                    </div>
                </a>
                <div v-if="!notifications || notifications.length === 0">
                    <span>You have no new notifications</span>
                </div>
                <!--<a v-show="preview" @click="openAll" class="dropdown-item see-more" href="javascript:void(0)">-->
                    <!--<i class="fa fa-chevron-down"></i> See More-->
                <!--</a>-->
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                notifications: null,
                preview: true
            }
        },
        props: [
            'data'
        ],
        created(){
            this.notifications = Object.assign(this.data, {}, this.notifications);
        },
        mounted(){},
        methods: {
            openAll() {
                this.preview = false;
            },
            readNotification(notification) {
                axios.post('/notification/' + notification.id)
                    .then(response => {
                        window.location.href = notification.data.link;
                    })
            }
        }
    }
</script>