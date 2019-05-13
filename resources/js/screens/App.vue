<template>
    <v-app>
        <v-navigation-drawer
            permanent
            app>
            <v-toolbar flat class="transparent">
                <v-list class="pa-0">
                    <v-list-tile avatar>
                        <v-list-tile-content>
                            <v-list-tile-title>$USER_NAME</v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </v-list>
            </v-toolbar>

            <v-divider></v-divider>

            <v-list dense class="pt-0">
                <v-list-tile
                    v-for="(item, index) in items"
                    :key="index"
                    @click="navigateTo(item.screen)">
                        <v-list-tile-action>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-tile-action>

                        <v-list-tile-content>
                            <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                        </v-list-tile-content>                                     
                </v-list-tile>
            </v-list>
        </v-navigation-drawer>
        <v-content>
            <v-container fluid>
                <router-view></router-view>
                <vue-snotify></vue-snotify>
            </v-container>
        </v-content>
    </v-app>

</template>

<script>
export default {
    data () {
      return {
        items: [
            { title: 'Início', icon: 'home', screen: 'Home' },
            { title: 'Dashboard', icon: 'dashboard', screen: 'Dashboard' },
            { title: 'Redefinir Senha', icon: 'vpn_key', screen: 'RedefinirSenha'},
            { title: 'Sair'}
        ]
      }
    },
    methods: {
        navigateTo(route) {
            this.$router.push({ name: route})
        }
    }
}
</script>
