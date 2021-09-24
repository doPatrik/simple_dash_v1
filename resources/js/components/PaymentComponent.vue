<template>
    <div class="payment-container">
        <full-screen-loading-component v-if="isLoading.getData || isLoading.delete"></full-screen-loading-component>

        <div class="filters-container">
            <div class="filter-item">
                <select name="providers" id="providers" class="form-control" v-model="filters.provider">
                    <option value="">Válassz egy szolgáltatót</option>
                    <option v-for="(provider, key) in this.providers" :value="Number(key)">{{provider}}</option>
                </select>
            </div>
            <div class="filter-item">
                <input class="search-text" type="text" v-model="search" @keyup.enter="searchBill()">
                <span class="search-icon"><i class="fas fa-search"></i></span>
            </div>
        </div>

        <messages-component :errors="this.errors" :success="this.success"></messages-component>

        <div class="data-container" v-if="bills.length === 0">
            <div class="data-label"><h2>Számlák</h2></div>
            <div class="data-content">
                <h3>Nincs megjeleníthető számla</h3>
            </div>
        </div>

        <div class="data-container" v-for="(data, month) in this.bills">
            <div class="data-label">
                <h2>{{month | monthName}}</h2>
            </div>
            <div class="data-content">
                <div class="card-content" v-for="bill in data">
                    <span class="tagColor" :style="{background: bill.color_code}"></span>
                    <span class="icon"><i class="fas fa-tags"></i></span>
                    <div class="deadLine">{{bill.dead_line}} <span>{{bill.remainingDay}} nap</span></div>
                    <span class="label">{{bill.label}} számla</span>
                    <span class="price text-success font-weight-bold">{{bill.price | currency}}</span>
                    <div class="buttons">
                        <button class="__button payment" v-if="!bill.is_paid" @click="addBillToPay(bill)" :disabled="isLoading.payIn">{{billIsIn(bill) ? '-' : '+'}} Befizetés</button>
                        <span class="__button editButton" v-if="!bill.is_paid" @click="editBills(bill)"><i class="fas fa-pencil-alt"></i></span>
                        <span class="__button paid" v-if="bill.is_paid" @click="editBills(bill)"><i class="fas fa-check"></i> Fizetve {{bill.updated_at}}</span>
                        <span class="__button deleteButton" @click="delBills(bill)"><i class="fas fa-trash-alt"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-container" v-if="showPayIn">
            <h2>Befizetés</h2>
            <div class="gridContainer">
                <div class="header bg-info">
                    <span>Befizetésre váró számlák</span>
                </div>
                <div class="bContent" v-for="(bill, index) in this.billsToPay" :key="index">
                    <div class="innerGrid bg-light">
                    <div class="billName">
                        <p class="strong">{{bill.label}} Számla</p>
                        <p>{{bill.name}}</p>
                        </div>
                    <div class="price">
                        {{bill.price | currency}}
                        </div>
                    </div>
                </div>
                <div class="innerGrid bg-warning" id="dtBorder">
                    <div class="billName">
                        <p>Végösszeg</p>
                    </div>
                    <div class="price strong" id="truePrice">{{priceCount | currency}}</div>
                </div>
                <div class="innerGrid" id="payButton">
                    <button type="button" class="btn btn-success" @click="pay" :disabled="isLoading.payIn">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="isLoading.payIn"></span>
                        Véglegesítés
                    </button>
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
                    <label>Határidő</label>
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
        props: ['providers'],
        data() {
            return {
                billsToPay: [],
                bills: {},
                showPayIn: false,
                isLoading: {
                    getData: false,
                    payIn: false,
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
                filters: {
                    provider: '',
                },
                search: '',
                errors: {},
                success: {},
            }
        },
        mounted() {
            this.getData();
        },
        methods: {
            searchBill() {
                this.getData();
            },
            getData() {
                this.isLoading.getData = true;
                axios.get('/payment/getBills', {
                    params: {
                        search: this.search.length >= 1 ? this.search : '',
                        ...this.filters
                    }
                })
                .then(res => {
                    if(res.status == 200){
                        this.bills = res.data.data;
                    }
                    this.isLoading.getData = false;
                }).catch(err => {
                    this.errors = [['Adatbetöltés hiba, próbálkozzon később']];
                    this.isLoading.getData = false;
                });
            },

            isEmpty(object) {
               return object.length === 0;
            },

            addBillToPay(bill) {
                if (!this.billsToPay.includes(bill)) {
                    this.billsToPay.push(bill);
                } else {
                    this.billsToPay.splice(this.billsToPay.indexOf(bill), 1);
                }

                this.showPayIn = !this.isEmpty(this.billsToPay);
            },

            billIsIn(bill) {
                if (this.billsToPay.includes(bill)) {
                    return true;
                }

                return false;
            },
            delBills(bill) {
                let result = confirm('Biztos törölni szeretné?');
                if (result) {
                    this.isLoading.delete = true;
                    axios.delete('/payment/' + bill.id_bill)
                        .then(response => {
                            this.isLoading.delete = false;
                            this.success = ['Sikeres törlés'];
                            this.getData();
                        })
                        .catch(err => {
                            this.errors = [['Hiba történt törlés közben, próbálkozzon később']];
                            this.isLoading.delete = false;
                        })
                }
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
            submitBill() {
                this.isLoading.edit = true;
                axios.put('/newBill/' + this.modalForm.billId, this.modalForm)
                    .then(res => {
                        this.isLoading.edit = false;
                        this.isOpen.editModal = false;
                        this.getData();
                    })
                    .catch(err => {
                        if (err.response.status === 422) {
                            this.errors = err.response.data.errors;
                        } else {
                            this.errors = [['Hiba történt, próbálkozzon később']];
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
            },
            pay() {
                this.isLoading.payIn = true;
                axios.post('/payment/payBills', this.billsToPay)
                    .then(response => {
                        this.showPayIn = false;
                        this.billsToPay = [];
                        this.isLoading.payIn = false;
                        this.success = ['Sikeres befizetés'];
                        this.getData();
                    })
                    .catch(err => {
                        this.isLoading.payIn = false;
                        this.errors = [['Hiba történt, próbálkozzon később']];
                    });
            }
        },
        watch: {
            filters: {
              handler() {
                this.getData();
              },
              deep: true
            },
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
        computed: {
            priceCount() {
                let price = 0;
                for(let i in this.billsToPay) {
                    if (!this.billsToPay.hasOwnProperty(i)) continue;
                    price += this.billsToPay[i].price;
                }
                return price;
            }
        },
    }
</script>
