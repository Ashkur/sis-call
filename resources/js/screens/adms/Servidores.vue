<template>
    <v-container>
        <v-layout row justify-center>
            <v-dialog v-model="employeeDialog" persistent max-width="600px">
                <v-btn slot="activator" color="#206eea" dark large @click="openRegisterDialog">NOVO SERVIDOR</v-btn>
                
                <v-card>
                    <v-card-title>
                        <span class="headline" v-if="editMode == false">NOVO SERVIDOR</span>
                        <span class="headline" v-else>EDITAR SERVIDOR</span>
                    </v-card-title>

                    <v-card-text>                        
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex>
                                    <span class="text-danger">{{ error? "Todos os campos devem ser preenchidos!": "" }}</span>
                                    <v-text-field
                                        v-model="employee.name"
                                        label="Nome"
                                    ></v-text-field>

                                    <v-text-field
                                        v-model="employee.login"
                                        label="Login"
                                    ></v-text-field>

                                    <v-text-field
                                        v-if="editMode == false"
                                        v-model="employee.password"
                                        :append-icon="showPass1 ? 'visibility' : 'visibility_off'"
                                        :type="showPass1 ? 'text' : 'password'"
                                        label="Senha"
                                        hint="No mínimo 6 caracteres"
                                        counter
                                        @click:append="showPass1 = !showPass1"
                                    ></v-text-field>

                                    <v-combobox
                                        v-model="employee.department"
                                        :items="departments"
                                        item-text="name"
                                        item-value="id"
                                        label="Setor"
                                        color="#206eea"
                                        ></v-combobox>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <template v-if="editMode">
                            <v-btn color="blue darken-1" flat @click="cancelEdit">Cancelar</v-btn>
                            <v-btn color="blue darken-1" flat @click="updateEmployee" :loading="isUpdateLoading">Ok!</v-btn>
                        </template>
                        <template v-else>
                            <v-btn color="blue darken-1" flat @click="employeeDialog = false">Cancelar</v-btn>
                            <v-btn color="blue darken-1" flat @click="registerEmployee" :loading="isRegisterLoading">Ok!</v-btn>
                        </template>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-layout>

        <h2>Servidores</h2>
        <v-data-table
            :headers="headers"
            :items="employees"
            :no-data-text="noDataText"
            :loading="isLoadingDatatable"
            class="elevation-1 text-xs-center"
            >
            <template v-slot:items="props">
                <td>{{ props.item.id }}</td>
                <td>{{ props.item.name }}</td>
                <td>{{ props.item.login }}</td>
                <td>{{ props.item.department.name }}</td>
                <td>{{ props.item.created_at }}</td>
                <td>
                    <v-btn flat color="blue" @click="openUpdateDialog(props.item)">Editar</v-btn>
                    |
                    <v-btn flat color="red" @click="deleteEmployee(props.item.id)" :loading="isDeleteLoading">Excluir</v-btn>
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
                    align: 'center',
                    text: 'ID',
                    sortable: true,
                    value: 'id'                    
                },
                {
                    align: 'center',
                    text: 'NOME',
                    value: 'name'
                },
                {
                    align: 'center',
                    text: 'LOGIN',
                    value: 'login'
                },
                {
                    align: 'center',
                    text: 'SETOR',
                    value: 'department'
                },
                {
                    align: 'center',
                    text: 'REGISTRADO EM',
                    value: 'created_at'
                },
            ],
            noDataText: 'Nenhum servidor encontrado',
            isLoadingDatatable: false,
            snackbar: {
                status: false,
                y: 'top',
                x: 'right',
                mode: '',
                timeout: 6000,
                text: '',
                color: ''
            },
            error: '',
            employee: {},
            employees: [],
            departments: [],
            departmentSelected: [],
            editMode: false,
            employeeDialog: false,
            showPass1: false,
            isRegisterLoading: false,
            isUpdateLoading: false,
        }
    },

    methods: {
        async fetchDepartments() {
            const res = await fetch('api/departments')
            const departments = await res.json()
            this.departments = departments.data
        },

        async fetchEmployees() {
            this.isLoadingDatatable = true
            const res = await fetch('api/employees')
            const employees = await res.json()
            this.employees = employees.data
            this.isLoadingDatatable = false
        },

        openRegisterDialog() {
            this.employee = {}
        },

        async registerEmployee() {
            this.loadingBtnRegister()
            if(!this.employee.name ||  !this.employee.login || !this.employee.password || !this.employee.department) {
                this.error = true;
                
            } else {

                let employee = {
                    name: this.employee.name,
                    login: this.employee.login,
                    password: this.employee.password,
                    department: this.employee.department.id,
                }

                const res = await fetch('api/employees', {
                    method: 'post',
                    headers: {
                        'Accept': 'application/json, text/plain, */*',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(employee)
                })

                this.employeeDialog = !this.employeeDialog

                switch (res.status) {
                    case 201:
                        let text = 'SETOR ADICIONADO!'
                        await this.fetchEmployees()
                        this.showsSnackbarSuccess(text) 
                        break;
                
                    default:
                        let error = await res.json()
                        alert(`Não foi possível completar a ação.\n CÓDIGO DO ERRO: ${error}`)
                        break;
                }         
            }    
            this.loadingBtnRegister()        
        },

        async openUpdateDialog(employee) {
            this.editMode = true
            this.employee = employee
            this.employeeDialog = !this.employeeDialog
        },

        async updateEmployee() {
            this.loadingBtnUpdate()
            if(!this.employee.name ||  !this.employee.login || !this.employee.department) {
                this.error = true;
                
            } else {
                this.employeeDialog = !this.employeeDialog

                let employee = {
                    name: this.employee.name,
                    login: this.employee.login,
                    department: this.employee.department.id,
                }
            
                const res = await fetch(`api/employees/${this.employee.id}`, {
                    method: 'put',
                    headers: {
                        'Accept': 'application/json, text/plain, */*',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(employee)
                })

                switch (res.status) {
                    case 200:
                        this.employee = await res.json()
                        let textSuccess = 'SERVIDOR EDITADO!'
                        await this.fetchEmployees()
                        this.showsSnackbarSuccess(textSuccess) 
                        break;
                    case 404:
                        let textError = 'SERVIDOR NÃO ENCONTRADO!'
                        this.showsSnackbarError(textError)
                        break;
                    default:
                        let error = await res.json()
                        alert(`Não foi possível completar a ação.\n CÓDIGO DO ERRO: ${error}`)
                        break;
                } 
                
                this.editMode = false
            }
            this.loadingBtnUpdate()
        },
        
        cancelEdit() {
            this.employeeDialog = false
            this.editMode = false
        },

        async deleteEmployee(id) {
            this.isLoadingDatatable = true
            const res = await fetch(`api/employees/${id}`, {
                method: 'delete',
            })
            
            switch (res.status) {
                case 200:
                    let textSuccess = 'SERVIDOR EXCLUÍDO!'
                    await this.fetchEmployees()
                    this.showsSnackbarSuccess(textSuccess) 
                    break;
                case 404:
                    let textError = 'SERVIDOR NÃO ENCONTRADO!'
                    this.showsSnackbarError(textError)
                    break;
                default:
                    let error = await res.json()
                    alert(`Não foi possível completar a ação.\n CÓDIGO DO ERRO: ${error}`)
                    this.showsSnackbarSuccess(textSuccess)
                    break;
            }
            this.isLoadingDatatable = false
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

        loadingBtnUpdate(){
            this.isUpdateLoading = !this.isUpdateLoading
        },

        loadingBtnRegister(){
            this.isRegisterLoading = !this.isRegisterLoading
        },
    },

    async mounted() {
        await this.fetchDepartments()
        await this.fetchEmployees()
    }
}
</script>
