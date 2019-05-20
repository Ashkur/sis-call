<template>
    <v-container>
        <v-layout>
            <v-flex xs12 sm6 offset-sm3>
                <v-card>
                    <v-card-text>
                        <v-combobox
                            v-model="employeeSelected"
                            :items="employees"
                            label="Selecione um servidor"
                            ></v-combobox>
                    </v-card-text>

                    <v-card-actions>
                        <v-btn flat color="red" @click="loading" :loading="loadingButtom">REDEFINIR</v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            employeeSelected: '',
            employees: ['uriel', 'carneiro', 'silva'],
            loadingButtom: false
        }
    },

    methods: {
        loading() {
            this.loadingButtom = !this.loadingButtom
        }
    }
}
</script>
