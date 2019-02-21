<template>
    <div>
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

            listenChannel() {
                window.Echo.channel('ticket-open')
                    .listen('.newTickets', () => {
                        this.fetchTickets()
                    })
            }
        },

        mounted() {
            this.listenChannel()
            console.log('Component mounted.')
        }

    }
</script>
