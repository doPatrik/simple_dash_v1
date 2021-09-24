<template>
    <div class="expenditure-container">
        <div class="tab-expenditure" v-if="tabIndex === 0">
            <full-screen-loading-component v-if="loading.screen || loading.types"></full-screen-loading-component>
            <div class="buttons-container">
                <div>
                    <button class="btn btn-primary" @click="tabIndex = 1">Típuok</button>
                </div>
                <div>
                    <button class="btn btn-success" @click="openTypeModal('new')">+ Új típus</button>
                    <button class="btn btn-success" @click="openExpModal('new')">+ Új kiadás</button>
                </div>
            </div>

            <div class="filters-container">
                <div class="filter-item">
                    <select name="type" id="type" class="form-control" v-model="filters.type">
                        <option value="">Válassz típust</option>
                        <option v-for="(type, key) in this.typeOptions" :value="Number(key)">{{type}}</option>
                    </select>
                </div>
                <div class="filter-item">
                    <input class="search-text" type="text" v-model="search" @keyup.enter="searchExp()">
                    <span class="search-icon"><i class="fas fa-search"></i></span>
                </div>
            </div>

            <messages-component :errors="this.errors" :success="this.success"></messages-component>

            <div class="payment-container" v-if="this.expenditures.length === 0">
                <div class="data-label"><h2>Kiadások</h2></div>
                <div class="data-content">
                    <h3>Nincs megjeleníthető kiadás</h3>
                </div>
            </div>

            <div class="payment-container" v-for="(data, month) in this.expenditures">
                <div class="data-label">
                    <h2>{{month | monthName}}</h2>
                </div>
                <div class="data-content">
                    <div class="card-content" v-for="expenditure in data">
                        <span class="tagColor" :style="{background: expenditure.color_code}"></span>
                        <span class="icon"><i class="fas fa-tags"></i></span>
                        <div class="deadLine">{{expenditure.description}} <span>{{expenditure.purchase_date}}</span></div>
                        <span class="label">{{expenditure.type_name}}</span>
                        <span class="price text-success font-weight-bold">{{expenditure.price | currency}}</span>
                        <div class="buttons">
                            <span class="__button editButton" @click="openExpModal('update', expenditure)"><i class="fas fa-pencil-alt"></i></span>
                            <span class="__button deleteButton" @click="delExpenditure(expenditure)"><i class="fas fa-trash-alt"></i></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expenditure form BEGIN -->

            <form v-if="isOpen.newModal" method="POST" @submit.prevent="submitForm(expFormMethod)">
                <edit-modal-component>
                    <template v-slot:header>
                        Új kiadás
                    </template>
                    <input v-if="expFormMethod === 'update'" type="hidden" name="_method" value="PUT">
                    <div class="form-group">
                        <label for="description">Leírás</label>
                        <input type="text" class="form-control" id="description" v-model="newModalForm.description">
                    </div>
                    <div class="form-group">
                        <label for="price">Összeg</label>
                        <input type="number" class="form-control" id="price" placeholder="Ft" v-model="newModalForm.price">
                    </div>
                    <div class="form-group">
                        <label>Kiadás típus</label>
                        <select class="form-control" v-model="newModalForm.id_type">
                            <option value="">Válassz típust</option>
                            <option v-for="(type, key) in this.typeOptions" :value="Number(key)">{{type}}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="purchase_date">Vásárlás dátuma</label>
                        <input type="date" class="form-control" id="purchase_date" v-model="newModalForm.purchase_date">
                    </div>

                    <template v-slot:footer>
                        <a class="btn btn-secondary" @click="closeModal('newModal')">Mégse</a>
                        <button type="submit" class="btn btn-primary" :disabled="loading.spinner">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="loading.spinner"></span>
                            Mentés
                        </button>
                    </template>
                </edit-modal-component>
            </form>
            <!-- Expenditure form END -->

            <!-- Expenditure type BEGIN -->

            <form v-if="isOpen.typeModal" method="POST" @submit.prevent="submitTypeForm(typeFormMethod)">
                <edit-modal-component>
                    <template v-slot:header>
                        Új kiadás tipus
                    </template>
                    <input v-if="typeFormMethod === 'update'" type="hidden" name="_method" value="PUT">
                    <div class="form-group">
                        <label for="name">Név</label>
                        <input type="text" class="form-control" id="name" v-model="typeModalForm.name">
                    </div>
                    <div class="form-group">
                        <label for="color_code">Szín</label>
                        <input type="color" class="form-control" id="color_code" v-model="typeModalForm.color_code">
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
        <div class="tab-types" v-else-if="tabIndex === 1">
            <expenditure-type-component @change-tab="changeTab"></expenditure-type-component>
        </div>
    </div>
</template>

<script>
    import FullScreenLoadingComponent from "../Loading/FullScreenLoadingComponent";
    import EditModalComponent from "../Modals/EditModalComponent";
    import ExpenditureTypeComponent from "./ExpenditureTypeComponent";
    import MessagesComponent from "../MessagesComponent";
    export default {
        components: {
            EditModalComponent,
            FullScreenLoadingComponent,
            ExpenditureTypeComponent,
            MessagesComponent,
        },
        data() {
            return {
                tabIndex: 0,
                filters: {
                    type: '',
                },
                search: '',
                typeOptions: {},
                expenditures: {},
                expFormMethod: '',
                typeFormMethod: '',
                loading: {
                    spinner: false,
                    screen: false,
                    types: false,
                },
                isOpen: {
                    newModal: false,
                    typeModal: false,
                },
                newModalForm: {
                    description: '',
                    price: '',
                    id_type: '',
                    purchase_date: '',
                },
                typeModalForm: {
                    name: '',
                    color_code: '',
                },
                typeId: null,
                expId: null,
                errors: {},
                success: {},
            }
        },
        methods: {
            searchExp() {
                this.loadData();
            },
            changeTab(index) {
                this.tabIndex = index;
                this.loadData();
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
                axios.get('/expenditure/loadData', {
                    params: {
                        search: this.search.length >= 1 ? this.search : '',
                        ...this.filters
                    }
                })
                    .then(res => {
                        if (res.status == 200) {
                            this.expenditures = res.data;
                            this.loading.screen = false;
                        }
                    })
                    .catch(err => {
                        this.errors = [['Adatok betöltésénél hiba történt. Probálja meg később.'],];
                        this.loading.screen = false;
                    })
            },
            openExpModal(type, expenditure = null) {
                if (type === 'new') {
                    this.isOpen.newModal = true;
                    this.expFormMethod = 'new';
                    for (let key in this.newModalForm) {
                        this.newModalForm[key] = '';
                    }
                } else if (type === 'update') {
                    this.isOpen.newModal = true;
                    this.expFormMethod = 'update';
                    this.newModalForm.description = expenditure.description;
                    this.newModalForm.price = expenditure.price;
                    this.newModalForm.id_type = expenditure.id_type;
                    this.newModalForm.purchase_date = expenditure.purchase_date;
                    this.expId = expenditure.id_expenditure;
                }
            },
            openTypeModal(type, expType = null) {
                if (type === 'new') {
                    this.isOpen.typeModal = true;
                    this.typeFormMethod = 'new'
                    for (let key in this.typeModalForm) {
                        this.typeModalForm[key] = '';
                    }
                }
            },
            closeModal(name)
            {
                if (this.loading.spinner) {
                    return false;
                }

                this.isOpen[name] = false;
            },
            submitForm(type)
            {
                this.loading.spinner = true;

                if (type === 'new') {
                    axios.post('/expenditure/store', this.newModalForm)
                        .then(res => {
                            this.loading.spinner = false;
                            this.isOpen.newModal = false;
                            for (let key in this.newModalForm) {
                                this.newModalForm[key] = '';
                            }
                            this.loadData();
                        })
                        .catch(err => {
                            if (err.response.status === 422) {
                                this.errors = err.response.data.errors;
                            } else {
                                this.errors = [['Hiba történt. Probálja meg később.'],];
                            }
                            this.loading.spinner = false;
                        })
                } else if (type === 'update') {
                    axios.put('/expenditure/update/' + this.expId, this.newModalForm)
                        .then(resp => {
                            this.loading.spinner = false;
                            this.isOpen.newModal = false;
                            for (let key in this.newModalForm) {
                                this.newModalForm[key] = '';
                            }
                            this.loadData();
                        })
                        .catch(err => {
                            if (err.response.status === 422) {
                                this.errors = err.response.data.errors;
                            } else {
                                this.errors = [['Hiba történt. Probálja meg később.'],];
                            }
                            this.loading.spinner = false;
                        })
                }
            },
            submitTypeForm(type) {
                this.loading.spinner = true;

                if (type === 'new') {
                    axios.post('/expenditure/type/store', this.typeModalForm)
                        .then(res => {
                            this.loading.spinner = false;
                            this.isOpen.typeModal = false;
                            for (let key in this.typeModalForm) {
                                this.typeModalForm[key] = '';
                            }
                            this.loadTypes();
                        })
                        .catch(err => {
                            if (err.response.status === 422) {
                                this.errors = err.response.data.errors;
                            } else {
                                this.errors = [['Hiba történt. Probálja meg később.'],];
                            }
                            this.loading.spinner = false;
                        })
                }
            },
            delExpenditure(expenditure) {
                let result = confirm('Biztos törölni szeretné?');

                if(result) {
                    this.loading.screen = true;

                    axios.delete('/expenditure/destroy/' + expenditure.id_expenditure)
                        .then(res => {
                            this.loadData();
                        })
                        .catch(err => {
                            this.errors = [['A törlés közben hiba történt, próbálkozzon később']];
                            this.loading.screen = false;
                        })
                }
            },
        },
        mounted() {
            this.loading.screen = true;
            this.loadTypes();
            this.loadData();
        },
        filters: {
            monthName(value) {
                if (!value) return '';
                let monthNamesHU = {
                    '01': 'Január',
                    '02': 'Február',
                    '03': 'Március',
                    '04': 'Április',
                    '05': 'Május',
                    '06': 'Június',
                    '07': 'Július',
                    '08': 'Augusztus',
                    '09': 'Szeptember',
                    '10': 'Október',
                    '11': 'November',
                    '12': 'December',
                };
                let key = value.toString().slice(-2);
                let year = value.toString().slice(0,4);
                return year + '-' + monthNamesHU[key];
            },
            currency(value) {
                return new Intl.NumberFormat('hu-HU', { style: 'currency', currency: 'HUF', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(value);
            }
        },
        watch: {
            filters: {
                handler() {
                    this.loadData();
                },
                deep: true
            },
        },
        emits: ['change-tab'],
    }
</script>

<style scoped>
    .buttons-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .buttons-container button {
        margin: 1rem;
    }
</style>
