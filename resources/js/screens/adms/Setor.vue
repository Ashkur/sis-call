<template>
    <v-container>
        <v-layout row justify-center>
            <v-dialog v-model="newDepartmentDialog" persistent max-width="600px">
                <v-btn slot="activator" color="#206eea" dark large @click="openRegisterDialog">NOVO SETOR</v-btn>
                
                <v-card>
                    <v-card-title>
                        <span class="headline" v-if="editMode == false">NOVO SETOR</span>
                        <span class="headline" v-else>EDITAR SETOR</span>
                    </v-card-title>

                    <v-card-text>                        
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex>
                                    <span class="text-danger">{{ error? "O nome do setor está vazio!": "" }}</span>
                                    <v-text-field
                                        v-model="department.name"
                                        label="Nome"
                                    ></v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <template v-if="editMode">
                            <v-btn color="blue darken-1" flat @click="cancelEdit">Cancelar</v-btn>
                            <v-btn color="blue darken-1" flat @click="updateDepartment">Ok!</v-btn>
                        </template>
                        <template v-else>
                            <v-btn color="blue darken-1" flat @click="newDepartmentDialog = false">Cancelar</v-btn>
                            <v-btn color="blue darken-1" flat @click="registerDepartment">Ok!</v-btn>
                        </template>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-layout>

        <h2>Setores</h2>
        <v-data-table
            :headers="headers"
            :items="departments"
            class="elevation-1 text-xs-center"
            >
            <template v-slot:items="props">
                <td>{{ props.item.id }}</td>
                <td>{{ props.item.name }}</td>
                <td>
                    <v-btn flat color="blue" @click="openUpdateDialog(props.item)">Editar</v-btn>
                    |
                    <v-btn flat color="red" @click="deleteDepartment(props.item.id)">Excluir</v-btn>
                </td>
            </template>
        </v-data-table>

        <v-snackbar
            v-model="snackbar.status"
            :top="snackbar.y"
            :right="snackbar.x"
            :timeout="snackbar.timeout"
            :color="snackbar.color">
            <b>{{ snackbar.text }}</b>
            <v-btn
                color="white"
                flat
                @click="snackbar.status = false"
            >
                Fechar
            </v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            headers: [
                {
                    text: 'ID',
                    align: 'center',
                    sortable: true,
                    value: 'id'
                },
                {
                    align: 'center',
                    text: 'NOME',
                    value: 'name'
                }
            ],
            snackbar: {
                status: false,
                y: 'top',
                x: 'right',
                mode: '',
                timeout: 6000,
                text: '',
                color: ''
            },
            department: {name: ''},
            departments: [],
            error: '',
            newDepartmentDialog: false,
            editMode: false
        }
    },
    methods: {
        async fetchDepartments() {
            const res = await fetch('api/departments')
            const departments = await res.json()
            this.departments = departments.data
            console.log(this.departments)
        },
        async registerDepartment() {
            if(!this.department) {
                this.error = true;
                
            } else {
                const res = await fetch('api/departments', {
                    method: 'post',
                    headers: {
                        'Accept': 'application/json, text/plain, */*',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(this.department)
                })

                this.newDepartmentDialog = !this.newDepartmentDialog

                switch (res.status) {
                    case 201:
                        let text = 'SETOR ADICIONADO!'
                        await this.fetchDepartments()
                        this.showsSnackbarSuccess(text) 
                        break;
                
                    default:
                        let error = await res.json()
                        alert(`Não foi possível completar a ação.\n CÓDIGO DO ERRO: ${error}`)
                        break;
                }         
            }            
        },
        async openUpdateDialog(department) {
            this.editMode = true
            this.department = department
            this.newDepartmentDialog = !this.newDepartmentDialog
        },
        async updateDepartment() {            
            console.log(this.department)
            if(!this.department) {
                this.error = true;
                
            } else {
                const res = await fetch(`api/departments/${this.department.id}`, {
                    method: 'put',
                    headers: {
                        'Accept': 'application/json, text/plain, */*',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(this.department)
                })

                this.newDepartmentDialog = !this.newDepartmentDialog

                switch (res.status) {
                    case 200:
                        let textSuccess = 'SETOR EDITADO!'
                        await this.fetchDepartments()
                        this.showsSnackbarSuccess(textSuccess) 
                        break;
                    case 404:
                        let textError = 'SETOR NÃO ENCONTRADO!'
                        this.showsSnackbarError(textError)
                        break;
                    default:
                        let error = await res.json()
                        alert(`Não foi possível completar a ação.\n CÓDIGO DO ERRO: ${error}`)
                        break;
                }             
            }
            this.editMode = false
        },
        async deleteDepartment(id) {
            const res = await fetch(`api/departments/${id}`, {
                method: 'delete',
            })
            
            switch (res.status) {
                case 200:
                    let textSuccess = 'SETOR EXCLUÍDO!'
                    await this.fetchDepartments()
                    this.showsSnackbarSuccess(textSuccess) 
                    break;
                case 404:
                    let textError = 'SETOR NÃO ENCONTRADO!'
                    this.showsSnackbarError(textError)
                    break;
                default:
                    let error = await res.json()
                    alert(`Não foi possível completar a ação.\n CÓDIGO DO ERRO: ${error}`)
                    this.showsSnackbarSuccess(textSuccess)
                    break;
            }
        },
        showsSnackbarSuccess(text) {
            this.snackbar.status = true
            this.snackbar.text = text
            this.snackbar.color = '#4CAF50'
        },
        showsSnackbarError(text) {
            this.snackbar.status = true
            this.snackbar.text = text
            this.snackbar.color = 'red'
        },
        cancelEdit() {
            this.newDepartmentDialog = false
            this.editMode = false
        },
        openRegisterDialog() {
            this.department = {name: ''}
        }
    },

    async mounted() {
        await this.fetchDepartments()
    }
}
</script>

