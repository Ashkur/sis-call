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
                                    <v-select
                                    :items="commomProblems"
                                    v-model="ocurrency.description"
                                    label="Ocorrência"
                                    ></v-select>

                                    <v-textarea
                                        name="input-7-1"
                                        label="Descrição da ocorrência"
                                        value=""
                                        hint="Descreva um problema não listado ou complemente as informações de uma ocorrência."
                                        rows="2"
                                        >{{ocurrency.description}}</v-textarea>
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

        <div class="text-align:center">
            <h2>Meus Chamados</h2>
        </div>

        <v-data-table
            :headers="headers"
            :items="tickets"
            class="elevation-1"
        >
            <template slot="items" slot-scope="props">
                <td>{{ props.item.id }}</td>
                <td class="text-xs-left">{{ props.item.description }}</td>
                <td class="text-xs-left">{{ props.item.status }}</td>
                <td class="text-xs-left">{{ props.item.created_at.date }}</td>
                <td class="text-xs-left">{{ props.item.user.name }}</td>
                <td class="text-xs-left">IT</td>
            </template>
        </v-data-table>

        <pre>
            {{$data.ocurrency}}
        </pre>
    </div>

</template>

<script>
  export default {
    data () {
      return {
        headers: [
          {
            text: 'CHAMADO',
            align: 'center',
            sortable: true,
            value: 'name'
          },
          { text: 'DESCRIÇÃO', value: 'description' },
          { text: 'STATUS', value: 'status' },
          { text: 'ABERTO EM', value: 'created_at' },
          { text: 'SERVIDOR', value: 'user' },
          { text: 'SETOR', value: 'setor' }
        ],
        tickets: [],
        dialog: false,
        commomProblems: [
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
        ocurrency: {
            title:'',
            description: ''
        }
      }
    },

    methods: {
        async fetchTickets () {
            const res = await fetch('api/tickets')
            const tickets = await res.json()
            this.tickets = tickets.data
            console.log(this.tickets)
        },

        async registerTicket() {
            let ocurrency = {
                description: this.ocurrency.description
            }

            const res = await fetch('api/tickets', {
                method: 'post',
                body: JSON.stringify(ocurrency)
            })

            this.dialog = !this.dialog

            console.log(res)
        },

        soundNotification() {
            var audioPath = 'sound/news-ting.wav';
            var audio = new Audio(audioPath)
            audio.play()
        },

        displayNotification() {
            this.$snotify.success('Chamado registrado!')
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
        this.fetchTickets()
    }
  }
</script>
