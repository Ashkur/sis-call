<template>
    <div class="container">
        <h3>Chamados</h3>
        <table class="table" >
            <tr>
                <td>ID</td>
                <td>DESCRIÇÃO</td>
                <td>STATUS</td>
                <td>ABERTO EM</td>
                <td>SERVIDOR</td>
                <td>SETOR</td>
            </tr>
            <tr v-for="ticket in tickets.slice().reverse()" :key="ticket.id">
                <td>{{ticket.id}}</td>
                <td>{{ticket.description}}</td>
                <td>{{ticket.status}}</td>
                <td>{{ticket.created_at.date}}</td>
                <td>{{ticket.user.name}}</td>
                <td>T.I</td>
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
                var audioPath = 'sound/news-ting.wav';
                var audio = new Audio(audioPath)
                audio.play()
            },

            displayNotification() {
                this.$snotify.warning('Um novo chamado!', 'Você tem um novo chamado!')
            },

            listenChannel() {
                window.Echo.channel('ticket-open')
                    .listen('.newTickets', () => {
                        this.fetchTickets()
                        this.displayNotification()
                        this.soundNotification()
                    })
            }
        },

        mounted() {
            this.listenChannel()
            this.fetchTickets()
        }

    }
</script>
