<template>
    <div>
        <full-screen-loading-component v-if="loading.screen"></full-screen-loading-component>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Felhasználó</th>
                    <th scope="col">Jogosultság</th>
                    <th scope="col">Név</th>
                    <th scope="col">Aktív</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="user in this.users" @dblclick="editUser(user)">
                    <th scope="row">{{user.id}}</th>
                    <td>{{user.name}}</td>
                    <td>{{user.id_role === 1 ? 'User' : 'Admin'}}</td>
                    <td>{{user.first_name}} {{user.last_name}}</td>
                    <td v-if="user.is_active"><i class="far fa-check-circle text-success"></i></td>
                    <td v-else><i class="far fa-times-circle text-danger"></i></td>
                </tr>
                </tbody>
            </table>
        </div>

        <form v-if="isOpen.editModal" method="POST" @submit.prevent="submitUser">
            <edit-modal-component>
                <template v-slot:header>
                    Felhasználó szerkesztése
                </template>
                <input type="hidden" name="_method" value="PUT">
                <div class="form-group">
                    <label for="username">Felhasználó</label>
                    <input type="text" class="form-control" id="username" v-model="modalForm.name">
                </div>
                <div class="form-group">
                    <label for="role">Jogosultság</label>
                    <select name="role" class="form-control" id="role" v-model="modalForm.id_role">
                        <option :selected="modalForm.id_role === 1" value="1">User</option>
                        <option :selected="modalForm.id_role === 2" value="2">Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="last_name">Családnév</label>
                    <input type="text" class="form-control" id="last_name" v-model="modalForm.last_name">
                    <label for="first_name">Keresztnév</label>
                    <input type="text" class="form-control" id="first_name" v-model="modalForm.first_name">
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="isActive" v-model="modalForm.is_active">
                    <label class="form-check-label" for="isActive">Aktív</label>
                </div>

                <template v-slot:footer>
                    <a class="btn btn-secondary" @click="closeModal()">Mégse</a>
                    <button class="btn btn-danger" @click="delUser()" :disabled="loading.spinner">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="loading.spinner"></span>
                        Törlés
                    </button>
                    <button type="submit" class="btn btn-primary" :disabled="loading.spinner">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="loading.spinner"></span>
                        Mentés
                    </button>
                </template>
            </edit-modal-component>
        </form>

    </div>
</template>

<script>
    import EditModalComponent from "../../Modals/EditModalComponent";
    import FullScreenLoadingComponent from "../../Loading/FullScreenLoadingComponent";
    export default {
        components: {
            EditModalComponent,
            FullScreenLoadingComponent,
        },
        data() {
            return {
                users: {},
                loading: {
                    screen: false,
                    spinner: false,
                },
                modalForm: {
                    name: '',
                    id_role: '',
                    first_name: '',
                    last_name: '',
                    is_active: ''
                },
                userId: null,
                isOpen: {
                    editModal: false,
                },
            }
        },
        methods: {
            loadUsers() {
                this.loading.screen = true;

                axios.get('/admin/users/loadUsers/')
                    .then(res => {
                        if (res.status === 200) {
                            this.users = res.data.data;
                        }
                        this.loading.screen = false;
                    })
                    .catch(err => {
                        this.$emit('error-message', [['Adatok betöltése közben hiba lépett fel, próbálkozzon később.']]);
                        this.loading.screen = false;
                    })
            },
            closeModal()
            {
                if (this.loading.spinner) {
                    return false;
                }

                this.isOpen.editModal = false;
            },
            editUser(user)
            {
                this.modalForm.name = user.name;
                this.modalForm.id_role = user.id_role;
                this.modalForm.first_name = user.first_name;
                this.modalForm.last_name = user.last_name;
                this.modalForm.is_active = user.is_active;
                this.userId = user.id;
                this.isOpen.editModal = true;
            },
            submitUser()
            {
                this.loading.spinner = true;

                axios.put('/admin/users/update/' + this.userId, this.modalForm)
                    .then(res => {
                        this.loading.spinner = false;
                        this.isOpen.editModal = false;
                        this.loadUsers();
                    })
                    .catch(err => {
                        if (err.response.status === 422) {
                            this.$emit('error-message', err.response.data.errors);
                        } else {
                            this.$emit('error-message', [['Hiba történt, próbálja meg később.']]);
                        }

                        this.loading.spinner = false;
                        this.isOpen.editModal = false;
                    })
            },
            delUser(user)
            {
                let result = confirm('Biztos törölni szeretné?')

                if(result) {
                    this.loading.spinner = true;

                    axios.delete('/admin/users/delete/' + this.userId)
                        .then(res => {
                            this.loading.spinner = false;
                            this.isOpen.editModal = false;
                            this.loadUsers();
                        })
                        .catch(err => {
                            this.$emit('error-message', [['Hiba történt, próbálja meg később.']]);
                            this.loading.spinner = false;
                            this.isOpen.editModal = false;
                        })
                }
            }
        },
        mounted() {
            this.loadUsers();
        }
    }
</script>

<style scoped>

</style>
