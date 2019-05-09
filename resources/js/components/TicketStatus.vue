<template>
    <v-chip color="green" text-color="white" v-if="status == 'RESOLVIDO'">{{ status }}</v-chip>
    <v-chip color="warning" text-color="white" v-else-if="status == 'PENDENTE'">{{ status }}</v-chip>
    <v-chip color="red" text-color="white" v-else-if="status == 'NÃO RESOLVIDO'">{{ status }}</v-chip>
    <v-chip color="blue" text-color="white" v-else-if="status == 'EM ANDAMENTO'">{{ status }}</v-chip>
</template>

<script>
export default {
    props: ['status']
}
</script>

