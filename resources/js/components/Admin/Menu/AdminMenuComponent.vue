<template>
    <div>
        <full-screen-loading-component v-if="loading.screen"></full-screen-loading-component>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Név</th>
                    <th scope="col">Jogosultság</th>
                    <th scope="col">Icon</th>
                    <th scope="col">URL</th>
                    <th scope="col">Aktív</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="menuItem in menuItems" @dblclick="editMenu(menuItem)">
                    <th scope="row">{{menuItem.id_default_menu}}</th>
                    <td>{{menuItem.name}}</td>
                    <td>{{menuItem.type}}</td>
                    <td>{{menuItem.icon}}</td>
                    <td>{{menuItem.route}}</td>
                    <td v-if="menuItem.is_active"><i class="far fa-check-circle text-success"></i></td>
                    <td v-else><i class="far fa-times-circle text-danger"></i></td>
                </tr>
                </tbody>
            </table>
        </div>

        <form v-if="isOpen.menuModal" @submit.prevent="submitMenu">
            <edit-modal-component>
                <template v-slot:header>
                    Menü szerkesztése
                </template>
                <div class="form-group">
                    <label for="name">Név</label>
                    <input type="text" class="form-control" id="name" v-model="menuForm.name">
                </div>
                <div class="form-group">
                    <label for="permission">Jogosultság</label>
                    <select name="permission" class="form-control" id="permission" v-model="menuForm.type">
                        <option :selected="menuForm.type === 'default'" value="default">Alapértelmezett</option>
                        <option :selected="menuForm.type === 'admin'" value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="icon">Icon</label>
                    <input type="text" class="form-control" id="icon" v-model="menuForm.icon">
                </div>
                <div class="form-group">
                    <label for="url">URL</label>
                    <input type="text" class="form-control" id="url" v-model="menuForm.route">
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="active" v-model="menuForm.is_active">
                    <label class="form-check-label" for="active">Aktív</label>
                </div>

                <template v-slot:footer>
                    <a class="btn btn-secondary" @click="closeModal()">Mégse</a>
                    <button class="btn btn-danger" @click="delMenu()" :disabled="loading.spinner">
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
                menuItems: {},
                loading: {
                    screen: false,
                    spinner: false,
                },
                menuForm: {
                    name: '',
                    icon: '',
                    route: '',
                    type: '',
                    is_active: '',
                },
                menuId: null,
                isOpen: {
                    menuModal: false,
                },
            }
        },
        methods: {
            loadMenuItems() {
                this.loading.screen = true;

                axios.get('/admin/menu/loadMenuItems/')
                    .then(res => {
                        if (res.status === 200) {
                            this.menuItems = res.data.data;
                        }
                        this.loading.screen = false;
                    })
                    .catch(err => {
                        this.$emit('error-message', [['Adatok betöltése közben hiba lépett fel, próbálkozzon később.']]);
                        this.loading.screen = false;
                    })
            },
            editMenu(menuItem) {
                this.menuForm = menuItem;
                this.menuId = menuItem.id_default_menu;
                this.isOpen.menuModal = true;
            },
            submitMenu() {
                this.loading.spinner = true;

                axios.put('/admin/menu/updateMenu/' + this.menuId, this.menuForm)
                    .then(res => {
                        this.loading.spinner = false;
                        this.isOpen.menuModal = false;
                        this.loadMenuItems();
                    })
                    .catch(err => {
                        if (err.response.status === 422) {
                            this.$emit('error-message', err.response.data.errors);
                        } else {
                            this.$emit('error-message', [['Hiba történt, próbálja meg később.']]);
                        }

                        this.isLoading.edit = false;
                        this.isOpen.editModal = false;
                    })
            },
            closeModal() {
                if (this.loading.spinner) {
                    return false;
                }

                this.isOpen.menuModal = false;
            },
            delMenu() {
                let result = confirm('Biztos törölni szeretné?');

                if(result) {
                    this.loading.spinner = true;

                    axios.delete('/admin/menu/delete/' + this.menuId)
                        .then(res => {
                            this.loading.spinner = false;
                            this.isOpen.menuModal = false;
                            this.loadMenuItems();
                        })
                        .catch(err => {
                            this.$emit('error-message', [['Hiba történt, próbálja meg később.']]);
                            this.loading.spinner = false;
                            this.isOpen.menuModal = false;
                        })
                }
            },
        },
        mounted() {
            this.loadMenuItems();
        }
    }
</script>

<style scoped>

</style>
