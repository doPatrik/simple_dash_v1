<template>
    <div class="overview-container">
        <full-screen-loading-component v-if="isLoading.delete"></full-screen-loading-component>
        <div class="filters-container">
            <div class="filter-item">
                <select name="paid" id="paid" class="form-control" v-model="filters.paid">
                    <option selected value="all">Összes</option>
                    <option value="1">Befizetett</option>
                    <option value="0">Befizetetlen</option>
                </select>
            </div>
            <div class="filter-item">
                <select name="providers" id="providers" class="form-control" v-model="filters.provider">
                    <option value="unset">Válassz egy szolgáltatót</option>
                    <option v-for="(provider, key) in this.providers" :value="Number(key)">{{provider}}</option>
                </select>
            </div>
            <div class="filter-item">
                <input class="search-text" type="text" v-model="filters.search">
                <span class="search-icon"><i class="fas fa-search"></i></span>
            </div>
        </div>

        <messages-component :success="this.success" :errors="this.errors"></messages-component>

        <div class="payment-container">
            <div class="data-label">
                <h2>Számlák</h2>
            </div>
            <div class="data-content">
                <h3 v-if="bills.length === 0">Nincs megjeleníthető számla</h3>
                <div class="card-content" v-for="bill in billFilter">
                    <span class="tagColor" :style="{background: bill.color_code}"></span>
                    <span class="icon"><i class="fas fa-tags"></i></span>
                    <div class="deadLine">{{bill.dead_line}} <span>{{bill.remainingDay}} nap</span></div>
                    <span class="label">{{bill.label}} számla</span>
                    <span class="price text-success font-weight-bold">{{bill.price | currency}}</span>
                    <div class="buttons">
                        <span class="__button paid" v-if="bill.is_paid" @click="editBills(bill)"><i class="fas fa-check"></i> Fizetve {{bill.updated_at}}</span>
                        <span class="__button editButton" v-if="!bill.is_paid" @click="editBills(bill)"><i class="fas fa-pencil-alt"></i></span>
                        <span class="__button deleteButton" @click="delBills(bill)"><i class="fas fa-trash-alt"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <form v-if="isOpen.editModal" method="POST" @submit.prevent="submitBill">
            <edit-modal-component>
                <template v-slot:header>
                    Számla szerkesztése
                </template>
                <input type="hidden" name="_method" value="PUT">
                <div class="form-group">
                    <label for="start_date">Időszak</label>
                    <input type="date" class="form-control" id="start_date" v-model="modalForm.start_date">
                    <input type="date" class="form-control" id="end_date" v-model="modalForm.end_date">
                </div>
                <div class="form-group">
                    <label for="price">Összeg</label>
                    <input type="number" class="form-control" id="price" placeholder="Ft" v-model="modalForm.price">
                </div>
                <div class="form-group">
                    <label for="dead_line">Határidő</label>
                    <input type="date" class="form-control" id="dead_line" v-model="modalForm.dead_line">
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="is_paid" v-model="modalForm.is_paid">
                    <label class="form-check-label" for="is_paid">Befizetve</label>
                </div>
                <div class="form-group">
                    <label>Szolgáltató</label>
                    <select class="form-control" v-model="modalForm.id_provider">
                        <option v-for="(provider, key) in this.providers" :value="Number(key)" :selected="Number(key) === modalForm.id_provider">{{provider}}</option>
                    </select>
                </div>

                <template v-slot:footer>
                    <a class="btn btn-secondary" @click="closeModal()">Mégse</a>
                    <button type="submit" class="btn btn-primary" :disabled="isLoading.edit">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="isLoading.edit"></span>
                        Mentés
                    </button>
                </template>
            </edit-modal-component>
        </form>
    </div>
</template>

<script>
    import FullScreenLoadingComponent from "./Loading/FullScreenLoadingComponent";
    import EditModalComponent from "./Modals/EditModalComponent";
    import MessagesComponent from "./MessagesComponent";
    export default {
        components: {
            FullScreenLoadingComponent,
            EditModalComponent,
            MessagesComponent,
        },
        props: ['bills', 'providers'],
        data() {
          return {
            filters: {
                provider: 'unset',
                paid: 'all',
                search: '',
            },
            isLoading: {
                delete: false,
                edit: false,
            },
            isOpen: {
              editModal: false,
            },
            modalForm: {
                billId: null,
                start_date: '',
                end_date: '',
                dead_line: '',
                price: 0,
                is_paid: false,
                id_provider: null,
            },
            errors: {},
            success: {},
          }
        },
        methods: {
            showBills(is_paid) {
               this.is_paid = is_paid;
            },
            editBills(bill) {
                this.isOpen.editModal = true;
                this.modalForm.billId = bill.id_bill;
                this.modalForm.start_date = bill.start_date;
                this.modalForm.end_date = bill.end_date;
                this.modalForm.dead_line = bill.dead_line;
                this.modalForm.price = bill.price;
                this.modalForm.is_paid = bill.is_paid;
                this.modalForm.id_provider = bill.id_provider;
            },
            delBills(bill) {
                let result = confirm('Biztos törölni szeretné?');
                if (result) {
                    this.isLoading.delete = true;
                    axios.delete('/payment/' + bill.id_bill)
                    .then(response => {
                        window.location = 'overview';
                    })
                    .catch(err => {
                        this.errors = [['Törlés közben hiba lépett fel, próbálkozzon később.']];
                        this.isLoading.delete = false;
                    })
                }
            },
            submitBill() {
                this.isLoading.edit = true;
                axios.put('/newBill/' + this.modalForm.billId, this.modalForm)
                    .then(res => {
                        window.location = 'overview';
                    })
                    .catch(err => {
                        if (err.response.status === 422) {
                            this.errors = err.response.data.errors;
                        } else {
                            this.errors = [['Hiba lépett fel, próbálkozzon később']];
                        }
                        this.isOpen.editModal = false;
                        this.isLoading.edit = false;
                    })
            },
            closeModal() {
                if (this.isLoading.edit) {
                    return false;
                }

                this.isOpen.editModal = false;
            }
        },
        computed: {
            billFilter() {
                if (this.filters.provider === 'unset' && this.filters.search === '' && this.filters.paid === 'all') {
                    return this.bills;
                } else if (this.filters.provider !== 'unset' && this.filters.search !== '' && this.filters.paid !== 'all') {
                    return this.bills.filter(bill => (bill.id_provider === this.filters.provider)
                        && bill.label.startsWith(this.filters.search)
                        && bill.is_paid === Number(this.filters.paid));
                } else if (this.filters.provider !== 'unset') {

                    if (this.filters.search !== '' && this.filters.paid === 'all') {
                        return this.bills.filter(bill => (bill.id_provider === this.filters.provider)
                            && bill.label.startsWith(this.filters.search));
                    }

                    if (this.filters.search === '' && this.filters.paid !== 'all') {
                        return this.bills.filter(bill => (bill.id_provider === this.filters.provider) && bill.is_paid === Number(this.filters.paid));
                    }

                    return this.bills.filter(bill => (bill.id_provider === this.filters.provider));

                } else if(this.filters.search !== '') {

                    if (this.filters.paid !== 'all') {
                        return this.bills.filter(bill => bill.label.startsWith(this.filters.search) && bill.is_paid === Number(this.filters.paid));
                    }

                    return this.bills.filter(bill => bill.label.startsWith(this.filters.search));
                } else if (this.filters.paid !== 'all') {

                    return this.bills.filter(bill => bill.is_paid === Number(this.filters.paid));
                }
            }
        },
        filters: {
            currency(value) {
                return new Intl.NumberFormat('hu-HU', { style: 'currency', currency: 'HUF', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(value);
            }
        }
    }
</script>
