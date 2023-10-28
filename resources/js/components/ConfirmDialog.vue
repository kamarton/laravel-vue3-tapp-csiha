<script setup lang="ts">

import {ref} from "vue";

defineProps({
    title: {
        type: String,
        required: true,
    },
    message: {
        type: String,
        required: true,
    },
    confirmText: {
        type: String,
        default: 'Confirm',
    },
    cancelText: {
        type: String,
        default: 'Cancel',
    },
});
</script>
<script lang="ts">

import {ref} from "vue";

export default {
    name: "ConfirmDialog",
    emits: ['cancel', 'confirm'],
    data() {
        return {
            show: false
        }
    },
};
</script>

<template>
    <v-dialog
        v-model="show"
        persistent
        width="auto"
    >
        <v-card>
            <v-card-title
                class="text-h5"
                v-text="title"
            />
            <v-card-text v-text="message"/>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    v-if="'' !== cancelText"
                    color="green-darken-1"
                    variant="text"
                    @click="$emit('cancel')"
                    v-text="cancelText"
                />
                <v-btn
                    color="green-darken-1"
                    variant="text"
                    @click="$emit('confirm')"
                    v-text="confirmText"
                />
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<style scoped>

</style>
