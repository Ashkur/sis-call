<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Example Component</div>

                    <div class="card-body">
                        I'm an example component.
                        <u>teste</u>

                        <ul v-for="ticket in tickets" :key="ticket.id">
                            <li>{{ticket.description}}</li>
                        </ul>

                        <pre>
                            {{ $data }}
                        </pre>

                    </div>
                </div>
            </div>
        </div>
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
