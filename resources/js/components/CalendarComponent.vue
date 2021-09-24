<template>
    <div id="calendar-container">
        <full-screen-loading-component v-if="isLoading"></full-screen-loading-component>
        <messages-component :errors="this.errors" :success="this.success"></messages-component>
        <FullCalendar :options="calendarOptions"/>
    </div>
</template>

<script>
    import FullCalendar from '@fullcalendar/vue'
    import dayGridPlugin from '@fullcalendar/daygrid'
    import interactionPlugin from '@fullcalendar/interaction'
    import FullScreenLoadingComponent from "./Loading/FullScreenLoadingComponent";
    import MessagesComponent from "./MessagesComponent";
    export default {
        props: ['bills'],
        components: {
            FullCalendar,
            FullScreenLoadingComponent,
            MessagesComponent,
        },
        data() {
            return {
                isLoading: false,
                calendarOptions: {
                    locale: 'hu',
                    buttonText: {
                      today: 'mai nap'
                    },
                    height: '90vh',
                    plugins: [ dayGridPlugin, interactionPlugin ],
                    initialView: 'dayGridMonth',
                    events: [],
                },
                errors: {},
                success: {},
            }
        },
        created() {
            this.getEvents();
        },
        methods: {
            getEvents() {
                this.isLoading = true;
                axios.get('/calendar/events')
                .then(response => {
                    this.calendarOptions.events = response.data.data;
                    this.isLoading = false;
                })
                .catch(err => {
                    this.errors = [['Naptár betöltése közben hiba történt, próbálkozzon később']];
                    this.isLoading = false;
                });
            }
        }
    }
</script>

<style>
    #calendar-container{
        margin: 50px 50px 0 50px;
    }
</style>
