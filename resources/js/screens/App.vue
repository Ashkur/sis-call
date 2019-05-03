<template>
    <v-app>
        <v-navigation-drawer app></v-navigation-drawer>
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

}
</script>
