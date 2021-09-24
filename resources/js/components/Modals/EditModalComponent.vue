<template>
    <div class="modal-container">

        <div class="modal-card">
            <div class="modal-card-header">
                <h2><slot name="header"></slot></h2>
            </div>
            <div class="modal-card-content">
                <slot></slot>
            </div>
            <div class="modal-card-footer">
                <div class="footer-buttons">
                    <slot name="footer"></slot>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {

    }
</script>

<style scoped>
    .modal-container {
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background: rgba(0,0,0, .5);
        z-index: 111;
    }

    .modal-card {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        background: #fff;
        box-shadow: 2px 2px 5px #888888;
        border-radius: 10px;
    }

    .modal-card .modal-card-header {
        background: #e6e6e6;
        padding: 1rem;
        border-bottom: 1px solid #bababa;
        border-radius: 10px 10px 0 0;
    }

    .modal-card .modal-card-content {
        margin: 1rem;
    }

    .form-group {
        margin: 1rem 0;
    }

    .modal-card .modal-card-footer {
        padding: 1.5rem 1rem;
        border-radius: 0 0 10px 10px;
        background: #bababa;
    }

    .modal-card .modal-card-footer .footer-buttons{
        display: flex;
        justify-content: space-between;
    }
</style>
