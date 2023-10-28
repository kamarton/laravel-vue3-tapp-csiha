<script lang="ts" setup>

defineProps({
    appName: {
        type: String,
        required: true,
    },
});
</script>
<script lang="ts">

export default {
    name: "NavBar",
    data() {
        return {
            sidebar: false,
            nav: [
                {title: 'Tasks', name: 'task.list', icon: 'home'},
                {title: 'New task', name: 'task.new', icon: 'task'},
            ]
        }
    },
};
</script>

<template>
    <v-toolbar app dark color="blue-grey darken-1">
        <v-toolbar-title>
            <router-link
                :to="{name: 'task.list'}"
                tag="span"
                class="ma-2 text-white text-decoration-none font-weight-bold"
                v-text="appName"
            />
        </v-toolbar-title>
        <v-spacer></v-spacer>
        <router-link v-for="item in nav"
                     :key="item.title"
                     :to="{name: item.name}"
                     tag="v-btn"
                     text
                     class="ma-2 text-white text-decoration-none font-weight-bold"
                     v-text="item.title"/>
    </v-toolbar>
</template>

<style scoped>

</style>
