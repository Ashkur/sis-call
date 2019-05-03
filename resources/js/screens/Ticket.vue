<template>
<div>
    <v-container fluid v-if="isLoading">
        <v-layout class="align-center justify-center column fill-height">
            <v-progress-circular 
                :size="140"
                :width="10"
                color="blue"
                indeterminate
                ></v-progress-circular>
            <h1>Carregando...</h1>
        </v-layout>
    </v-container>
    
    <v-container grid-list-md v-else>
        <v-layout row wrap>
            <v-flex md8>
                <div>
                    <div class="m-20">
                        <div style="float: left">
                            <div class="headline">{{ticket.id}}</div>
                            <div class="subheading">Nº do Chamado</div>                
                        </div>

                        <div style="float: right">
                            <ticketstatus :status=ticket.status class="headline"/>              
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="m-20">
                        <div class="headline">{{ticket.ocurrence}}</div>
                        <div class="subheading">Ocorrência</div>
                    </div>

                    <div class="m-20">
                        <div class="headline" v-if="ticket.description!=null">{{ ticket.description}}</div>
                        <div class="headline" v-else>Nenhuma descrição informada</div>
                        <div class="subheading">Descrição</div>
                    </div>

                    <div class="m-20">
                        <div class="headline">{{ticket.created_at}}</div>
                        <div class="subheading">Aberto em</div>
                    </div>

                    <div class="m-20">
                        <div class="headline">{{user.name}}</div>
                        <div class="subheading">Servidor(a)</div>
                    </div>
                    
                    <div class="m-20">
                        <div class="headline">T.I.</div>
                        <div class="subheading">Setor</div>
                    </div>

                    <div class="m-20">
                        <div class="headline">Está ocorrência ainda não foi solucionada.</div>
                        <div class="subheading">Solução</div>
                    </div>

                    <div class="m-20">
                        <div class="headline">Uriel Carneiro</div>
                        <div class="subheading">Técnico Responsável</div>
                    </div>
                </div>
            </v-flex>

            <v-flex md2>
                <v-card>
                    <v-list two-line>
                        <template v-for="(item, index) in items"> 
                            <v-divider
                                v-if="item.divider"
                                :key="index"
                                :inset="item.inset"
                                ></v-divider>                           
                            <v-list-tile
                                @click="item.action"
                                :key="item.title">
                                <v-list-tile-action>
                                    <v-icon>{{item.icon}}</v-icon>
                                </v-list-tile-action>
                                <v-list-tile-content>                        
                                    <v-list-tile-title v-html="item.title"></v-list-tile-title>
                                </v-list-tile-content>                                
                            </v-list-tile>                            
                        </template>
                    </v-list>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</div>
</template>

<script>
import jsPDF from 'jspdf'
import TicketStatus from '.././components/TicketStatus'
export default {
    props: ['id'],

    components: {
        ticketstatus: TicketStatus
    },

    data() {
        return{
            isLoading: true,
            ticket: [],
            user: [],
            items: [
                { 
                    title: 'Atender', 
                    icon: 'touch_app',
                    action: this.answerCall
                },
                { 
                    title: 'Adicionar Solução', 
                    icon: 'comment',
                    action: this.sendComment
                },
                { 
                    title: 'Imprimir', 
                    icon: 'print',
                    action: this.printTicket
                }]
        }       
    },

    methods: {
        async fetchTicket(id) {
            const res = await fetch(`/api/tickets/${id}`)
            const ticket = await res.json()
            this.ticket = ticket.data
            this.user = this.ticket.user
            console.log(this.ticket)
        },

        answerCall() {
            console.log("responder chamado")
        },

        sendComment() {
            console.log('sendComment')
        },

        printTicket() {
            console.log('printing ticket')
            var doc = new jsPDF()
            doc.text('Hello World!', 10, 10)
            doc.save('a4.pdf')
        },

        consolelog(title){
            console.log(title)
        }
    },

    async mounted() {
        
        await this.fetchTicket(this.$route.params.id)
        this.isLoading = false

        console.log(this.$route.params.id)
    }
}
</script>

<style scoped>
    .m-20 {
        padding: 20px;
    }

    .clearfix {
        clear: left;
    }

</style>

