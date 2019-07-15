<template>
    <v-container>
        <v-layout row justify-center>
            <v-dialog v-model="employeeDialog" persistent max-width="600px">
                <v-btn slot="activator" color="#206eea" dark large @click="openRegisterDialog">NOVO TÉCNICO</v-btn>
                
                <v-card>
                    <v-card-title>
                        <span class="headline" v-if="editMode == false">NOVO TÉCNICO</span>
                        <span class="headline" v-else>EDITAR TÉCNICO</span>
                    </v-card-title>

                    <v-card-text>                        
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex>
                                    <span class="text-danger">{{ error? "Todos os campos devem ser preenchidos!": "" }}</span>
                                    <v-text-field
                                        v-model.trim="technician.name"
                                        :error-messages="nameErrors"
                                        label="Nome"
                                    ></v-text-field>

                                    <v-text-field
                                        v-model.trim="technician.login"
                                        :error-messages="loginErrors"
                                        label="Login"
                                    ></v-text-field>

                                    <v-text-field
                                        v-if="editMode == false"
                                        v-model.trim="technician.password"
                                        :error-messages="passwordErrors"
                                        :append-icon="showPass1 ? 'visibility' : 'visibility_off'"
                                        :type="showPass1 ? 'text' : 'password'"
                                        label="Senha"
                                        hint="No mínimo 6 caracteres"
                                        counter
                                        @click:append="showPass1 = !showPass1"
                                    ></v-text-field>

                                    <v-combobox
                                        v-model.trim="technician.department"
                                        :error-messages="departmentErrors"
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

        <h2>Técnicos</h2>
        <v-data-table
            :headers="headers"
            :items="technicians"
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
                    <v-btn flat color="red" @click="deleteEmployee(props.item.id)">Excluir</v-btn>
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
import { required, maxLength } from 'vuelidate/lib/validators'

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
            technician: {},
            technicians: [],
            departments: [],
            departmentSelected: [],
            editMode: false,
            employeeDialog: false,
            showPass1: false,
            isRegisterLoading: false,
            isUpdateLoading: false,
            
        }
    },

    validations: {
        technician: {
            name: {
                required,
                maxLength: maxLength(255)
            },
            login: {
                required,
                maxLength: maxLength(255)
            },
            password: {
                required,
                maxLength: maxLength(255)
            },
            department: {
                required,
            }
        }
    },

    methods: {
        async fetchDepartments() {
            const res = await fetch('api/departments')
            const departments = await res.json()
            this.departments = departments.data
        },

        async fetchTechnicians() {
            this.isLoadingDatatable = true
            const res = await fetch('api/technicians')
            const technicians = await res.json()
            this.technicians = technicians.data
            this.isLoadingDatatable = false
        },

        openRegisterDialog() {
            this.technician = {}
        },

        async registerEmployee() {

            this.$v.$touch()

            if(!this.$v.technician.$invalid) {

                let technician = {
                    name: this.technician.name,
                    login: this.technician.login,
                    password: this.technician.password,
                    department: this.technician.department.id,
                }

                const res = await fetch('api/technicians', {
                    method: 'post',
                    headers: {
                        'Accept': 'application/json, text/plain, */*',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(technician)
                })

                this.employeeDialog = !this.employeeDialog

                switch (res.status) {
                    case 201:
                        let text = 'TÉCNICO ADICIONADO!'
                        await this.fetchTechnicians()
                        this.showsSnackbarSuccess(text) 
                        break;
                
                    default:
                        let error = await res.json()
                        alert(`Não foi possível completar a ação.\n CÓDIGO DO ERRO: ${error}`)
                        break;
                }         
            }    
        },

        async openUpdateDialog(technician) {
            this.editMode = true
            this.technician = technician
            this.employeeDialog = !this.employeeDialog
        },

        async updateEmployee() {

            this.$v.$touch()

            if(!this.$v.technician.name.$invalid || !this.$v.technician.login.$invalid || !this.$v.technician.department.$invalid) {
                this.employeeDialog = !this.employeeDialog

                let technician = {
                    name: this.technician.name,
                    login: this.technician.login,
                    department: this.technician.department.id,
                }
            
                const res = await fetch(`api/technicians/${this.technician.id}`, {
                    method: 'put',
                    headers: {
                        'Accept': 'application/json, text/plain, */*',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(technician)
                })

                switch (res.status) {
                    case 200:
                        this.technician = await res.json()
                        let textSuccess = 'TÉCNICO EDITADO!'
                        await this.fetchTechnicians()
                        this.showsSnackbarSuccess(textSuccess) 
                        break;
                    case 404:
                        let textError = 'TÉCNICO NÃO ENCONTRADO!'
                        this.showsSnackbarError(textError)
                        break;
                    default:
                        let error = await res.json()
                        alert(`Não foi possível completar a ação.\n CÓDIGO DO ERRO: ${error}`)
                        break;
                } 
                
                this.editMode = false
            }
        },
        
        cancelEdit() {
            this.employeeDialog = false
            this.editMode = false
        },

        async deleteEmployee(id) {
            this.isLoadingDatatable = true
            const res = await fetch(`api/technicians/${id}`, {
                method: 'delete',
            })
            
            switch (res.status) {
                case 200:
                    let textSuccess = 'TÉCNICO EXCLUÍDO!'
                    await this.fetchTechnicians()
                    this.showsSnackbarSuccess(textSuccess) 
                    break;
                case 404:
                    let textError = 'TÉCNICO NÃO ENCONTRADO!'
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

    computed: {
        nameErrors () {
            const errors = []
            if (!this.$v.technician.name.$dirty) return errors
            !this.$v.technician.name.maxLength && errors.push('Limite máximo do tamanho do nome é de 255 caracteres')
            !this.$v.technician.name.required && errors.push('Informe um nome.')
            return errors
        },
        loginErrors () {
            const errors = []
            if (!this.$v.technician.login.$dirty) return errors
            !this.$v.technician.login.maxLength && errors.push('Limite máximo do tamanho do nome é de 255 caracteres')
            !this.$v.technician.login.required && errors.push('Informe um login.')
            return errors
        },
        passwordErrors () {
            const errors = []
            if (!this.$v.technician.password.$dirty) return errors
            !this.$v.technician.password.maxLength && errors.push('Limite máximo do tamanho do nome é de 255 caracteres')
            !this.$v.technician.password.required && errors.push('Informe uma senha.')
            return errors
        },
        departmentErrors () {
            const errors = []
            if (!this.$v.technician.department.$dirty) return errors
            !this.$v.technician.department.required && errors.push('Informe um setor.')
            return errors
        }
    },

    async mounted() {
        await this.fetchDepartments()
        await this.fetchTechnicians()
    }
}
</script>
