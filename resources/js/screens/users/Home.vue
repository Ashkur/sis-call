<template>
    <div class="container">
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

        <h2>Meus Chamados</h2>

        <v-data-table
            :loading="isTableLoading"
            :headers="headers"
            :items="tickets"
            :pagination.sync="pagination"
            class="elevation-1"
        >
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
                    <td class="text-xs-left">
                        <router-link 
                            :to="{
                                name:'TicketDetail', 
                                params: {
                                    id:props.item.id,
                                }
                            }">
                            <v-icon large>assignment</v-icon>
                        </router-link>      
                    </td>
                </tr>
            </template>
            <template slot="expand" slot-scope="props">
                <v-card flat>
                    <v-card-text>{{ props.item.description }}</v-card-text>
                </v-card>
            </template>
        </v-data-table>

        <pre>
            {{$data.ticket}}
        </pre>
    </div>

</template>

<script>
import TicketStatus from '../../components/TicketStatus'

export default {
    components: {
        ticketstatus: TicketStatus
    },
    data () {
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
          { text: 'DETALHES', value: ''}
        ],
        tickets: [],
        dialog: false,
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
        ticket: {
            ocurrence:'',
            description: ''
        },
        expand: false,
        pagination: {
          sortBy: 'STATUS'
        },
        error: false,
        isTableLoading: false
      }
    },

    methods: {
        async fetchTickets () {
            this.isTableLoading = true
            console.log('fetchTickets')
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

        soundNotification() {
            var audioPath = 'sound/news-ting.wav';
            var audio = new Audio(audioPath)
            audio.play()
            console.log('soundNotification')
        },

        displayNotification() {
            console.log('displayNotification')
            this.$snotify.success('Chamado registrado!')
        },

        listenChannel() {
            console.log('listenChannel')
            window.Echo.channel('ticket-open')
                .listen('.newTickets', () => {
                    this.fetchTickets()
                    this.displayNotification()
                    this.soundNotification()
                })
        }

    },

    async mounted() {
        await this.fetchTickets()
        await this.listenChannel()
    },

    destroyed: function () {
            window.Echo.channel('ticket-open')
                .stopListening('.newTickets')
        },
  }
</script>
