<template>
    <div class="payment-container">
        <div class="data-label">
            <h2>Heti számlák</h2>
        </div>
        <div class="data-content">
            <div v-if="bills.length === 0"><h3>Nincs esedékes számla</h3></div>
            <div class="card-content" v-for="bill in bills">
                <span class="tagColor" :style="{background: bill.color_code}"></span>
                <span class="icon"><i class="fas fa-tags"></i></span>
                <div class="deadLine">{{bill.dead_line}} <span>{{bill.remainingDay}} nap</span></div>
                <span class="label">{{bill.label}} számla</span>
                <span class="price text-success font-weight-bold">{{bill.price | currency}}</span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            bills: Array,
        },
        filters: {
            currency(value) {
                return new Intl.NumberFormat('hu-HU', { style: 'currency', currency: 'HUF', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(value);
            }
        }
    }
</script>
