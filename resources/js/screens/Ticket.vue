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
        <v-dialog
            v-model="dialog"
            max-width="600px">

            <v-card>
                <v-card-title
                    class="headline lighten-2"
                    primary-title>
                    Finalizar Chamado
                </v-card-title>
                
                <v-card-text>
                    <v-radio-group v-model="solution.state" :mandatory="false">
                        <v-radio label="RESOLVIDO" value="RESOLVIDO"></v-radio>
                        <v-radio label="NÃO RESOLVIDO" value="NÃO RESOLVIDO"></v-radio>                                                          
                    </v-radio-group>
                    <span class="text-danger" v-if="error">Você deve preencher o campo <b>descrição</b>.</span>
                    <v-textarea
                        class=".fontfamily"
                        v-model="solution.description"
                        color="#206eea"
                        label="Descrição da solução"
                        value=""
                        hint="Descreva como ocorreu a solução do problema ou o motivo de não ser solucionado."
                        rows="2"
                        ></v-textarea>
                </v-card-text>                

                <v-divider></v-divider>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="primary"
                        flat
                        @click="dialog = false"
                    >
                        Confirmar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

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
                        <div class="headline">{{ticket.employee.name}}</div>
                        <div class="subheading">Servidor(a)</div>
                    </div>
                    
                    <div class="m-20">
                        <div class="headline">{{ticket.employee.department.name}}</div>
                        <div class="subheading">Setor</div>
                    </div>

                    <div class="m-20">
                        <div class="headline">Está ocorrência ainda não foi solucionada.</div>
                        <div class="subheading">Solução</div>
                    </div>

                    <div class="m-20" v-if="ticket.technician == ''">
                        <div class="headline">Esta ocorrência ainda não foi atendida.</div>
                        <div class="subheading">Técnico Responsável</div>
                    </div>

                    <div class="m-20" v-else>
                        <div v-for="technician in ticket.technician" :key="technician.id">
                            <div class="headline">{{technician.name}}.</div>                            
                        </div>
                        <div class="subheading">Técnico(s) Responsável</div>                        
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
import pdfMake from 'pdfmake'
import TicketStatus from '.././components/TicketStatus'
import pdfFonts from "pdfmake/build/vfs_fonts";

export default {
    props: ['id'],

    components: {
        ticketstatus: TicketStatus
    },

    data() {
        return{
            dialog: false,
            isLoading: true,
            error: false,
            ticket: {},
            employee: {},
            technician: {
                id: 1
            },
            solution: {
                state: 'RESOLVIDO',
                description: '',
            }, 
            items: [
                { 
                    title: 'Atender', 
                    icon: 'touch_app',
                    action: this.answerCall
                },
                { 
                    title: 'Finalizar Chamado', 
                    icon: 'done',
                    action: this.finalizarChamado
                },
                { 
                    title: 'Gerar PDF', 
                    icon: 'insert_drive_file',
                    action: this.generatePdf
                }
            ],
        }       
    },

    methods: {
        async fetchTicket(id) {
            const res = await fetch(`/api/tickets/${id}`)
            const ticket = await res.json()
            this.ticket = ticket.data
            this.employee = this.ticket.employee
            console.log(this.ticket)
        },

        mounteText() {
           
            var text = {
                content:[]
            }

            text.content.push(
                {
                    image:'data:image/png;base64,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',
                    width: 150,
                    alignment: 'center'
                },
                {text: 'JUNTA COMERCIAL DO ESTADO DE RORAIMA', bold:true, alignment: 'center'},
                '\n\n\n\n\n\n\n\n\n',
            )

            text.content.push(
                {   
                    alignment: 'justify',
                    text: [
                        'O chamado de Nº',
                        {text: ` ${this.ticket.id} `, bold: true},
                        'em estado',
                        {text: ` ${this.ticket.status} `, bold: true},
                        'é referente a ocorrência',
                        this.ticket.ocurrence?{text: ` ${this.ticket.ocurrence} `, bold: true}: null,
                        'requisitado pelo servidor',
                        {text: ` ${this.employee.name} `, bold: true},
                        'do setor',
                        {text: ` ${this.ticket.department.name} `, bold: true},
                        'aberto na data',
                        {text: ` ${this.ticket.created_at} `, bold: true},
                        '.'
                    ], 
                },
                '\n',                
            )

            if(this.ticket.description) {
                text.content.push(
                    'O servidor descreve a ocorrência em suas palavras da seguinte forma: ', 
                    '\n',
                    {
                        text: `"${this.ticket.description}"`,
                        italics: true,
                        bold: true,
                        alignment: 'justify'
                    },
                    '\n'
                )
            }
            
            switch (this.ticket.status) {
                case 'PENDENTE':
                    text.content.push(
                        {
                            text: [
                                'O técnico responsável pela ocorrência é o servidor',
                                {
                                    text: ' Uriel Carneiro.', 
                                    bold: true
                                }                           
                            ]                            
                        },
                        '\n',
                    )
                    break;
                
                case 'RESOLVIDO':
                    text.content.push(
                        {
                            text: [
                                'O técnico responsável pela ocorrência',
                                {
                                    text: ' Uriel Carneiro ', 
                                    bold: true
                                },
                                'informa que a solução foi:',                                
                            ]                            
                        },
                        '\n',
                        {
                            text: '"euismod ipsum interdum dolor, non vehicula justo ultrices"',
                            italics: true,
                            bold: true,
                            alignment: 'justify'
                        },
                        '\n'
                    )
                    break
                
                case 'NÃO RESOLVIDO':
                    text.content.push(
                        {
                            text: [
                                'O técnico responsável pela ocorrência',
                                {
                                    text: ' Uriel Carneiro ', 
                                    bold: true
                                },
                                'informa que o motivo da não solução foi:',                                
                            ]                            
                        },
                        '\n',                        
                        {
                            text: '"euismod ipsum interdum dolor, non vehicula justo ultrices"',
                            italics: true,
                            bold: true,
                            alignment: 'justify'
                        }
                    )
                    break
                default:
                    break;
            }

            text.content.push(
                '\n\n\n',
                'Documento gerado em 08 de Maio de 2019.'
            )
            
            return text
        },

        async answerCall() {
            console.log("responder chamado")
            let data = {
                ticket: this.ticket.id,
                technician: this.technician.id
            }
            await this.registerTechnician(data)
            // notificaUsuario()
            this.ticket.status = "EM ANDAMENTO"
        },

        finalizarChamado() {
            console.log('sendComment')
            this.dialog = true;
        },

        generatePdf() {
            console.log('generate pdf')

            pdfMake.vfs = pdfFonts.pdfMake.vfs;

            let dd = this.mounteText()

            pdfMake.createPdf(dd).open();

        },

        async registerTechnician(data) {        
            const res = await fetch('/api/technicians/answercall',
                {
                    method: 'post',
                    headers: {
                        'Accept': 'application/json, text/plain, */*',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
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

