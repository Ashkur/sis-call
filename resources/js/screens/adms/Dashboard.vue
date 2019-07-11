<template>
    <v-container>
        <h2>Todos os Chamados</h2>        
        <v-data-table
            :loading="this.isTableLoading"
            :headers="headers"
            :items="tickets"
            :pagination.sync="pagination"
            :no-data-text="noDataText"
            class="elevation-1">
            <template slot="items" slot-scope="props">
                <tr @click="props.expanded = !props.expanded">
                    <td class="text-xs-left">{{ props.item.id }}</td>
                    <td class="text-xs-left">{{ props.item.ocurrence?props.item.ocurrence:stringTruncate(props.item.description, 50) }}</td>
                    <td class="text-xs-left">
                        <ticketstatus :status=props.item.status />
                    </td>
                    <td class="text-xs-left">{{ props.item.created_at }}</td>
                    <td class="text-xs-left">{{ props.item.user.name }}</td>
                    <td class="text-xs-left">IT</td>
                    <td>
                        <v-menu transition="slide-x-transition" bottom right>
                            <template v-slot:activator="{ on }">
                                <v-btn class="deep-orange" color="primary" dark v-on="on">
                                    Opções
                                </v-btn>
                            </template>

                            <v-list>
                                <v-list-tile 
                                    v-for="(action, i) in actions"
                                    :key="i"
                                    :to="{
                                        name:'TicketDetail', 
                                        params: {
                                            id:props.item.id,
                                        }
                                    }">
                                    <v-list-tile-title>{{ action.title }}</v-list-tile-title>
                                </v-list-tile>
                            </v-list>
                        </v-menu>
                    </td>
                </tr>
            </template>
            <template slot="expand" slot-scope="props">
                <v-card flat>
                    <v-card-text v-if="props.item.description!=null">{{ props.item.description}}</v-card-text>
                    <v-card-text v-else>Nenhuma descrição informada</v-card-text>
                </v-card>
            </template>
        </v-data-table>

        <pre>
            {{$data.tickets}}
        </pre>
    </v-container>
</template>

<script>
import TicketStatus from '../../components/TicketStatus'

export default {
    components: {
        ticketstatus: TicketStatus
    },
    data() {
        return {
            headers: [
                {
                    text: 'Nº CHAMADO',
                    align: 'center',
                    sortable: true,
                    value: 'id'
                },
                { text: 'OCORRÊNCIA', value: 'ocurrence' },
                { text: 'STATUS', value: 'status' },
                { text: 'ABERTO EM', value: 'created_at' },
                { text: 'SERVIDOR', value: 'user' },
                { text: 'SETOR', value: 'setor' },
                { text: '', value: '' }
            ],
            pagination: {
                sortBy: 'STATUS'
            },
            actions: [
                { title: 'Atender', link: '/' },
                { title: 'Detalhes'},
            ],
            noDataText: 'Nenhum chamado registrado até o momento.',
            tickets: [],
            isTableLoading: false
        }
    },

    methods: {
        async fetchTickets () {
            this.isTableLoading = true
            const res = await fetch('api/tickets')
            const tickets = await res.json()
            this.tickets = tickets.data
            console.log(this.tickets)
            this.isTableLoading = false
        },

        stringTruncate (str, length) {
            var dots = str.length > length ? '...' : '';
            return str.substring(0, length) + dots;
        },
    },

    mounted() {
        this.fetchTickets()
    }

}
</script>
