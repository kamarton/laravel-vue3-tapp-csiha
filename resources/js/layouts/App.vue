<script lang="ts" setup>


</script>
<script lang="ts">
import NavBar from "../components/NavBar.vue";
export default {
    name: "App",
    components: {
        NavBar,
    },
    data() {
        return {
            appName: import.meta.env.VITE_APP_NAME ?? "---",
            appYear: import.meta.env.VITE_APP_YEAR ?? new Date().getFullYear(),
        }
    },
};
</script>

<template>
    <v-app>
        <NavBar
            :appName="appName"
        />
        <v-main>
            <v-container>
                <router-view :key="$route.fullPath"/>
            </v-container>
        </v-main>
        <v-footer class="d-flex flex-column mt-6 mb-2">
            <div class="text-center w-100">
                <span v-text="appYear"/> — <strong v-text="appName"/>
            </div>
        </v-footer>
    </v-app>
</template>

<style scoped>

</style>
