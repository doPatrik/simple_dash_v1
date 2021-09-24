<template>
    <div class="messages">
        <!-- SUCESS MESSAGE START-->
        <div class="alert alert-success rMessage" v-if="success" v-for="msg in this.success">
            <span>{{msg}}</span>
        </div>
        <!-- SUCESS MESSAGE END-->

        <div class="alert alert-danger rMessage" v-if="errors" v-for="error in this.errors">
            {{error[0]}}
        </div>
    </div>
</template>

<script>
    export default {
        props: ['success', 'errors']
    }
</script>

<style scoped>
    .rMessage {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
</style>
