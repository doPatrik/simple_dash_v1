<template>
    <div class="expenditure-type-container">
        <full-screen-loading-component v-if="loading.screen || loading.types"></full-screen-loading-component>
        <div class="buttons-container">
            <button class="btn btn-primary" @click="changeTab">Kiadások</button>
        </div>

        <div class="filters-container">
            <div class="filter-item">
                <select name="type" id="type" class="form-control" v-model="filters.type">
                    <option value="">Válassz típust</option>
                    <option v-for="(type, key) in this.typeOptions" :value="Number(key)">{{type}}</option>
                </select>
            </div>
            <div class="filter-item">
                <input class="search-text" type="text" v-model="search" @keyup.enter="searchType()">
                <span class="search-icon"><i class="fas fa-search"></i></span>
            </div>
        </div>


        <messages-component :errors="this.errors" :success="this.success"></messages-component>

        <div class="payment-container">
            <div class="data-label">
                <h2>Kiadás típusok</h2>
            </div>
            <div class="data-content">
                <div v-if="types.length === 0"><h3>Nincs kiadás típus</h3></div>
                <div class="card-content" v-for="type in types">
                    <span class="tagColor" :style="{background: type.color_code}"></span>
                    <span class="icon"><i class="fas fa-tags"></i></span>
                    <div class="deadLine">{{type.name}}</div>
                    <div class="buttons">
                        <span class="__button editButton" @click="openModal(type)"><i class="fas fa-pencil-alt"></i></span>
                        <span class="__button deleteButton" @click="delType(type)"><i class="fas fa-trash-alt"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expenditure type BEGIN -->

        <form v-if="isOpen.editModal" method="POST" @submit.prevent="submitForm()">
            <edit-modal-component>
                <template v-slot:header>
                    Kiadás típus
                </template>
                <div class="form-group">
                    <label for="name">Név</label>
                    <input type="text" class="form-control" name="Név" id="name" v-model="modalForm.name">
                </div>
                <div class="form-group">
                    <label for="color_code">Szín</label>
                    <input type="color" class="form-control" id="color_code" v-model="modalForm.color_code">
                </div>
                <template v-slot:footer>
                    <a class="btn btn-secondary" @click="closeModal('typeModal')">Mégse</a>
                    <button type="submit" class="btn btn-primary" :disabled="loading.spinner">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="loading.spinner"></span>
                        Mentés
                    </button>
                </template>
            </edit-modal-component>
        </form>

        <!-- Expenditure type END -->
    </div>
</template>

<script>
    import FullScreenLoadingComponent from "../Loading/FullScreenLoadingComponent";
    import EditModalComponent from "../Modals/EditModalComponent";
    import MessagesComponent from "../MessagesComponent";
    export default {
        components: {
            MessagesComponent,
            FullScreenLoadingComponent,
            EditModalComponent,
        },
        data() {
            return {
                filters: {
                    type: '',
                },
                search: '',
                loading: {
                  spinner: false,
                  screen: false,
                  types: false,
                },
                isOpen: {
                    editModal: false,
                },
                typeOptions: {},
                types: {},
                modalForm: {
                    name: '',
                    color_code: '',
                },
                typeId : null,
                errors: {},
                success: {},
            }
        },
        methods: {
            searchType() {
                this.loadData();
            },
            changeTab() {
                this.$emit('change-tab', 0);
            },
            loadTypes() {
                this.loading.types = true;
                this.errors = {};
                axios.get('/expenditure/types')
                    .then(res => {
                        if(res.status == 200) {
                            this.typeOptions = res.data;
                        }
                        this.loading.types = false;
                    })
                    .catch(err => {
                        this.errors = [['Típusok betöltésénél hiba történt. Probálja meg később.'],];
                        this.loading.types = false;
                    })
            },
            loadData() {
                this.loading.screen = true;
                this.errors = {};
                axios.get('/expenditure/type/loadData', {
                    params: {
                        search: this.search.length >= 1 ? this.search : '',
                        ...this.filters
                    }
                })
                    .then(res => {
                        if (res.status == 200) {
                            this.types = res.data.data;
                            this.loading.screen = false;
                        }
                    })
                    .catch(err => {
                        this.errors = [['Adatok betöltésénél hiba történt. Probálja meg később.'],];
                        this.loading.screen = false;
                    })

            },
            openModal(type) {
                this.modalForm.name = type.name;
                this.modalForm.color_code = type.color_code;
                this.typeId = type.id_type;
                this.isOpen.editModal = true;
            },
            closeModal() {
                if (this.loading.spinner) {
                    return false;
                }

                this.isOpen.editModal = false;
            },
            submitForm() {
                this.loading.spinner = true;

                axios.put('/expenditure/type/update/' + this.typeId, this.modalForm)
                    .then(res => {
                        this.loading.spinner = false;
                        this.isOpen.editModal = false;
                        this.modalForm = {};
                        this.loadData();
                        this.loadTypes();
                    })
                    .catch(err => {
                        if (err.response.status === 422) {
                            this.errors = err.response.data.errors;
                        } else {
                            this.errors = [['Hiba történt, próbálkozzon később']]
                        }
                        this.loading.spinner = false;
                        this.isOpen.editModal = false;
                    })
            },
            delType(type) {
                let result = confirm('Biztos törölni szeretné?');

                if(result) {
                    this.loading.screen = true;
                    axios.delete(
                        '/expenditure/type/destroy/' + type.id_type
                    ).then(res => {
                        if(res.status === 200) {
                            this.loadData();
                            this.loadTypes();
                        }
                    }).catch(err => {
                        if(err.response.status === 400) {
                            this.errors = err.response.data;
                        } else {
                            this.errors = [['A törlés közben hiba történt, próbálkozzon később']]
                        }
                        this.loading.screen = false;
                    })
                }
            }
        },
        mounted() {
            this.loading.screen = true;
            this.loadTypes();
            this.loadData();
        },
        watch: {
            filters: {
                handler() {
                    this.loadData();
                },
                deep: true
            },
        },
    }
</script>
