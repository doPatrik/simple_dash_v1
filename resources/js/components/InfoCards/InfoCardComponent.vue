<template>
    <div class="info-card">
        <span :class="['tag', classes.tag]"></span>
        <div class="info-card-content">
            <div class="card-item">
                <span class="card-label">{{labels.first}}</span>
                <span :class="['card-value', classes.valueColor]">{{values.first.value}} {{values.first.postFix}}</span>
            </div>
            <div class="card-item">
                <span class="card-label">{{labels.second}}</span>
                <span :class="['card-value', classes.valueColor]">{{values.second.value | currency}}</span>
            </div>
        </div>
        <div :class="['icon-wrap', classes.iconBg]">
            <i :class="classes.icon"></i>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            labels: {
                first: String,
                second: String,
            },
            values: {
                first: {
                    value: String,
                    postFix: String,
                },
                second: {
                    value: String,
                    postFix: String,
                }
            },
            classes: {
                icon: String,
                iconBg: String,
                tag: String,
                valueColor: String,
            }
        },
        filters: {
            currency(value) {
                return new Intl.NumberFormat('hu-HU', { style: 'currency', currency: 'HUF', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(value);
            }
        }
    }
</script>
