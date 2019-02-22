<template>
    <div>
        das
        <table class="table" >
            <tr v-for="ticket in tickets.slice().reverse()" :key="ticket.id">
                <td>{{ticket.id}}</td>
            </tr>
        </table>
    </div>
</template>

<script>
    export default {

        data() {
            return {
                tickets: [],
            }
        },

        methods: {

            async fetchTickets () {
                const res = await fetch('api/tickets')
                const tickets = await res.json()
                this.tickets = tickets.data
                console.log(this.tickets)
            },

            soundNotification() {
                var audio = new Audio(require('./news-ting.mp3'))
                audio.play()
            },

            listenChannel() {
                window.Echo.channel('ticket-open')
                    .listen('.newTickets', () => {
                        this.fetchTickets()
                        this.soundNotification()
                    })
            }
        },

        mounted() {
            //this.soundNotification()
            this.listenChannel()
            console.log('Component mounted.')

            var audio = new Audio(require('news-ting.mp3'))
            audio.play()
        }

    }
</script>
