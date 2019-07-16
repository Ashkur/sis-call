<template>
    <v-container>

        <v-layout row justify-center>
            <v-dialog v-model="dialog" persistent max-width="600px">
                <v-btn slot="activator" color="#206eea" dark large>NOVO CHAMADO</v-btn>

                <v-card>
                    <v-card-title>
                        <span class="headline">NOVO CHAMADO</span>                        
                    </v-card-title>

                    <v-card-text>                        
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex>
                                    <span class="text-danger">{{ error? "Ao menos um dos campos deve ser preenchido.": "" }}</span>
                                    <v-combobox
                                        v-model="ticket.ocurrence"
                                        :items="commomProblems"
                                        label="Ocorrência"
                                        color="#206eea"
                                        ></v-combobox>

                                    <v-textarea
                                        v-model="ticket.description"
                                        color="#206eea"
                                        label="Descrição da ocorrência"
                                        value=""
                                        hint="Descreva um problema não listado ou complemente as informações de uma ocorrência."
                                        rows="2"
                                        ></v-textarea>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" flat @click="dialog = false">Cancelar</v-btn>
                        <v-btn color="blue darken-1" flat @click="registerTicket">Ok!</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-layout>

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
            commomProblems: [
                'Atualizar tabelas SEFIP',
                'Compartilhar pasta',
                'Erro ao imprimir',
                'Impressora offline',
                'Impressora não instalada',
                'Instalar aplicativo',
                'Atualizar aplicativo',
                'Não é possível acessar pasta compartilhada',
                'Não é possível acessar determinado site',
                'Problemas ao fazer login no OMNE',
                'Sem acesso a internet',
                'Sem acesso a rede',
                'Hardware (Ex.: Mouse, Teclado, etc.) com defeito'
            ],
            noDataText: 'Nenhum chamado registrado até o momento.',
            tickets: [],
            ticket: {
                ocurrence:'',
                description: ''
            },            
            isTableLoading: false,
            dialog: false,
            error: false,
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

        async registerTicket() {
            console.log('registerTicket')
            this.isTableLoading = true
            let ticket = {
                ocurrence: this.ticket.ocurrence,
                description: this.ticket.description
            }

            if(!ticket.ocurrence && !ticket.description) {
                this.error = true;
                
            } else {
                const res = await fetch('api/tickets', {
                    method: 'post',
                    headers: {
                        'Accept': 'application/json, text/plain, */*',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(ticket)
                })

                this.dialog = !this.dialog

                if(res.status == 500){alert('Não foi possível completar a ação.')}
                console.log(res)
            }
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
