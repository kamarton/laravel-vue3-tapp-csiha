<script setup lang="ts">

defineProps({
    message: {
        type: String,
        required: true,
    }
});
</script>
<script lang="ts">


export default {
    name: "ErrorDialog",
    data() {
        return {
            show: true
        }
    },
};
</script>

<template>
    <v-dialog
        v-model="show"
        :scrim="false"
        persistent
        width="auto"
    >
        <v-card
            color="primary"
        >
            <v-card-text>
                <span v-text="message"/>
                <v-progress-linear
                    indeterminate
                    color="white"
                    class="mb-0"
                ></v-progress-linear>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<style scoped>

</style>
