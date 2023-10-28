<script setup lang="ts">

defineProps({
    seconds: {
        type: Number,
        required: true,
    }
});

</script>
<script lang="ts">

import {spentTime} from "../helpers/date-time-helper";

export default {
    name: "SpentTime",
    data() {
        return {}
    },
    computed: {
        hasTimeParts() {
            return (this.seconds ?? 0) > 0;
        },
        timeParts() {
            return spentTime(this.seconds ?? 0);
        }
    }
};
</script>

<template>
    <span v-if="hasTimeParts">
        <span v-for="part in timeParts" :key="part" class="me-2">
            <b v-text="part.count" class="me-2"/>
            <span v-text="part.unit"/>
        </span>
    </span>
    <span v-else>
        N/A
    </span>
</template>

<style scoped>

</style>
