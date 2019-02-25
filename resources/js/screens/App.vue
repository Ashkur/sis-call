<template>
    <div>
        <router-link :to="{ name: 'home' }">Home</router-link> |
        <router-link :to="{ name: 'dashboard' }">Hello World</router-link>

        <router-view></router-view>
        <vue-snotify></vue-snotify>
    </div>

</template>

<script>
export default {

}
</script>
