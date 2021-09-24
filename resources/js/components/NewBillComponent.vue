<template>
    <div class="new-bill-container">
        <form method="POST" @submit.prevent="submitBill">

            <div class="backDrop" v-if="popup.isOpen">
                <div class="popup" id="interval" v-if="popup.interval">
                    <label for="beginDate">-Tól</label></br>
                    <input type="date" name="beginDate" id="beginDate" required v-model="form.beginDate"><br>

                    <label for="end_date">-Ig</label></br>
                    <input type="date" name="end_date" id="end_date" required v-model="form.end_date"><br>

                    <button type="button" class="confirmButton" id="confirmDate" @click="closePopup('interval')">
                        OK
                    </button>
                </div>

                <div class="popup" id="price" v-if="popup.price">
                    <label for="price">Összeg</label></br>
                    <input type="number" name="priceData" id="priceData" required v-model="form.priceData"><br>

                    <button type="button" class="confirmButton" id="confirmPrice" @click="closePopup('price')">
                        OK
                    </button>
                </div>

                <div class="popup" id="deadline" v-if="popup.deadline">
                    <label for="deadLDate">Határidő</label></br>
                    <input type="date" name="deadLDate" id="deadLDate" required v-model="form.deadlineDate"><br>

                    <button type="button" class="confirmButton" id="confirmDeadL" @click="closePopup('deadline')">
                        OK
                    </button>
                </div>

            </div>

            <messages-component :errors="this.errors" :success="this.success"></messages-component>

            <div class="buttons">
                <a class="subPageLink" @click="newBill">
                    <button type="button" class="btn light base-button" id="addBill">
                        Számla hozzáadása
                        <i class="fas fa-plus-circle"></i>
                    </button>
                </a>
                <a class="subPageLink" @click="newProvider">
                    <button type="button" class="btn dark base-button">
                        Szolgáltató felvétele
                        <i class="fas fa-plus-circle"></i>
                    </button>
                </a>
            </div>

            <div class="card">
                <div class="title"><h2>Szolgáltatók</h2></div>
                <div class="text">
                    <div class="alert alert-success" role="alert">
                        <h4 class="alert-heading">Információ!</h4>
                        <p>Az alábbi lista segítségével kiválaszthatsz egy szolgáltatót, melynek segítségével egy félig kitöltött számlát kaphatsz. Módosítsd az összeget, illetve a befizetési határidőt! </p>
                        <hr>
                        <p class="mb-0">Amennyiben nem találod a szolgáltatódat a listában, Kattints a "Szolgáltató felvétele" -gombra. A hozzáadás után már szerepelni fog a listában!</p>
                    </div>
                    <select id="providers" class="form-control" v-model="id_provider">
                        <option value="-1">Válassz egy szolgáltatót</option>
                        <option v-for="(provider) in this.providerselect" :value="provider.id_provider" :key="provider.id_provider">{{provider.name}} - {{provider.label}}</option>
                    </select>
                </div>
            </div>

            <!--SZÁMLA ADATOK START-->
            <div class="card" id="billDatas" v-if="showBill">
                <div class="title"><h2 id="billTitle">Számla</h2></div>
                <div class="billText">

                    <div class="szolgaltatoAdat">
                        <b id="providerName">Szolgáltató neve: {{selectedProvider.name}}</b>
                        <p id="providerAddress">
                            Címe: {{selectedProvider.provider_address.postal_code}}
                            {{selectedProvider.provider_address.city}},
                            {{selectedProvider.provider_address.street_name}}
                        </p>
                    </div>
                </div>

                <div class="mainText">

                    <div class="bill" ref="billData">

                        <div class="bill-item">
                            <span class="item-title">Elszámolt időszak:</span>
                            <span class="item-value">{{form.beginDate}} / {{form.end_date}} <i class="fas fa-edit" id="intervalEdit" @click="openPopup('interval')"></i></span>
                        </div>

                        <div class="bill-item">
                            <span class="item-title">Fizetendő összeg:</span>
                            <span class="item-value">{{form.priceData | currency}}<i class="fas fa-edit" id="priceEdit" @click="openPopup('price')"></i></span>
                        </div>


                        <div class="bill-item">
                            <span class="item-title">Fizetési határidő:</span>
                            <span class="item-value">{{form.deadlineDate}}<i class="fas fa-edit" id="deadLineEdit" @click="openPopup('deadline')"></i></span>
                        </div>
                    </div>

                    <div class="egyebAdat">
                        Felhasználó azonosító száma: {{selectedProvider.number}}<br/>
                        Felhasználó neve: {{selectedProvider.owner_name}}<br/>
                        Felhasználó címe: {{selectedProvider.user_address.post_code}} {{selectedProvider.user_address.city}}<br/>
                        <!-- utca -->{{selectedProvider.user_address.street_name}}<br/>
                        Felhasználási hely címe: {{selectedProvider.user_address.post_code}} {{selectedProvider.user_address.city}}<br/>
                        <!-- utca -->{{selectedProvider.user_address.street_name}}
                    </div>
                </div>

                <div class="buttons">
                    <button type="submit" class="btn green base-button" id="addNewBill" :disabled="isLoading.newBill">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="isLoading.newBill"></span>
                        Számla létrehozása
                        <i class="fas fa-plus-circle"></i>
                    </button>
                </div>
            </div>
            <!--SZÁMLA ADATOK END-->
        </form>
    </div>
</template>

<script>
    import MessagesComponent from "./MessagesComponent";
    export default {
        components: {
            MessagesComponent,
        },
        props: ['providerselect', 'providers'],
        data() {
            return {
                form: {
                    beginDate: "éééé-hh-nn",
                    end_date: "éééé-hh-nn",
                    priceData: 0,
                    deadlineDate: "éééé-hh-nn"
                },
                showBill: false,
                id_provider: -1,
                popup: {
                  isOpen: false,
                  interval: false,
                  price: false,
                  deadline: false,
                },
                isLoading: {
                    newBill: false,
                },
                errors: {},
                success: {},
            }
        },
        methods: {
            openPopup(name) {
                this.popup.interval = false;
                this.popup.price = false;
                this.popup.deadline = false;

                this.popup.isOpen = true;
                this.popup[name] = true;
            },
            closePopup(name) {
              this.popup[name] = false;
              this.popup.isOpen = false;
            },
            newBill: function () {
                if(this.id_provider > 0) {
                    this.showBill = !this.showBill;

                    let element = this.$refs.billData;
                    if (element) {
                        element.scrollIntoView({behavior: 'smooth'});
                    }
                }
            },
            submitBill() {
                this.isLoading.newBill = true;
                let data = new FormData();
                data.append('start_date', this.form.beginDate);
                data.append('end_date', this.form.end_date);
                data.append('price', this.form.priceData);
                data.append('dead_line', this.form.deadlineDate);
                data.append('id_provider', this.id_provider);

                axios.post('/newBill', data)
                .then((response) => {
                    this.form.beginDate = "éééé-hh-nn";
                    this.form.end_date = "éééé-hh-nn";
                    this.form.priceData = 0;
                    this.form.deadlineDate = "éééé-hh-nn";
                    this.success = ['Sikeres számla létrehozás.'];
                    this.showBill = false;
                    this.id_provider = -1;
                    this.errors = {};
                    this.isLoading.newBill = false;
                }).catch(error => {
                    this.isLoading.newBill = false;
                   if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                   } else {
                       this.errors = [['Hiba történt, próbálkozzon később']];
                   }
                });
            },
            newProvider() {
                window.location = '/provider/create';
            }
        },
        computed: {
            selectedProvider() {
                return this.providers.filter( provider => provider.id_provider === this.id_provider)[0]
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        filters: {
            currency(value) {
                return new Intl.NumberFormat('hu-HU', { style: 'currency', currency: 'HUF', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(value);
            }
        }
    }
</script>
