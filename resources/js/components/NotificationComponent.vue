<template>
    <div class="notifications">
        <div class="ti-bell notification-icon" @click="dropDownIsOpen = !dropDownIsOpen">
            <span class="notificationCounter" v-if="exists && notifications.length < 10">+{{notifications.length}}</span>
            <span class="notificationCounter" v-else-if="notifications.length >= 10">9+</span>
        </div>

        <div class="notification-dropdown" v-show="dropDownIsOpen && exists">
            <div class="notification-header">
                <span class="header-text">Értesítések</span>
                <span class="close-button" @click="dropDownIsOpen = !dropDownIsOpen"><i class="fas fa-window-close"></i></span>
            </div>
            <div class="notification-body">
                <div class="notification-item" v-for="notification in this.notifications">
                    <span class="notification-mark"><i class="fas fa-envelope"></i></span>
                    <div class="item-text">
                        <h2>Befizetetlen számla</h2>
                        <span>{{notification.data.name}} számla</span>
                    </div>
                    <div class="day-count">
                        {{notification.data.day}}
                    </div>
                    <div class="read-marker" v-if="!loading.spinner" @click="markAsRead(notification.id)"><i class="far fa-times-circle"></i></div>
                    <div class="read-marker" v-if="loading.spinner"><spinner-component :is-loaded="loading.spinner"></spinner-component></div>
                </div>
            </div>
            <div class="notification-footer">
                <span v-if="!loading.spinner" class="read-all" @click="markAsRead()">Összes elolvasás</span>
                <div v-if="loading.spinner"><spinner-component :is-loaded="loading.spinner"></spinner-component></div>
            </div>
        </div>
    </div>
</template>

<script>
    import SpinnerComponent from "./Loading/SpinnerComponent";
    export default {
        components: {SpinnerComponent},
        data() {
            return {
                dropDownIsOpen : false,
                notifications: {},
                loading: {
                    data: false,
                    spinner: false,
                },
            }
        },
        methods: {
            getNotifications() {
                this.loading.spinner = true;
                axios.get('/notifications/get')
                    .then(res => {
                        this.notifications = res.data.data;
                        this.loading.spinner = false;
                    })
            },
            markAsRead(id = null) {
                this.loading.spinner = true;
                axios.post('/notifications/notification/markAsRead', {
                    id: id,
                })
                    .then(res => {
                        this.getNotifications();
                    })
            }
        },
        mounted() {
            this.getNotifications();
        },
        computed: {
            exists() {
                return this.notifications.length > 0;
            }
        }
    }
</script>

<style>
    .notifications .notification-icon:hover {
        color: darkgrey;
    }

    .read-marker .spinner-border {
        width: 1rem;
        height: 1rem;
    }
</style>
