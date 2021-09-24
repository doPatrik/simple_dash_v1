<template>
    <div class="admin-container">
        <div class="navigation-buttons">
            <button class="btn" :class="[active.users ? 'btn-success' : 'btn-info']" @click="changeMenu('users')">Felhasználók</button>
            <button class="btn" :class="[active.menu ? 'btn-success' : 'btn-info']" @click="changeMenu('menu')">Menük</button>
        </div>

        <messages-component :errors="this.errors" :success="this.success"></messages-component>

        <admin-user-component v-if="active.users" @error-message="showMessage"></admin-user-component>

        <admin-menu-component v-if="active.menu" @error-message="showMessage"></admin-menu-component>
    </div>
</template>

<script>
    import MessagesComponent from "../MessagesComponent";
    import AdminUserComponent from "./User/AdminUserComponent";
    import AdminMenuComponent from "./Menu/AdminMenuComponent";
    export default {
        components: {
            MessagesComponent,
            AdminUserComponent,
            AdminMenuComponent,
        },
        data() {
            return {
                active: {
                    users: true,
                    menu: false,
                },
                errors: {},
                success: {},
            }
        },
        methods: {
            showMessage(message)
            {
                this.errors = message;
            },
            changeMenu(menuItem) {
                switch (menuItem) {
                    case 'users':
                        this.active.users = true;
                        this.active.menu = false;
                        break;
                    case 'menu':
                        this.active.users = false;
                        this.active.menu = true;
                        break;
                }
            },
        }
    }
</script>

<style scoped>
    .navigation-buttons {
        display: flex;
        justify-content: flex-start;
        flex-wrap: wrap;
        margin: 1rem;
    }

    .navigation-buttons button {
        margin: 1rem;
    }
</style>
