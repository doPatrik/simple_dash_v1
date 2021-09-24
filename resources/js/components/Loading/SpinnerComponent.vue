<template>
    <div class="text-center" v-if="isLoaded">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['isLoaded'],
    }
</script>

