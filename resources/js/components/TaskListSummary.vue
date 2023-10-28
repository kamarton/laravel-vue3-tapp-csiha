<script setup lang="ts">

import {Task} from "../types/Task";

defineProps({
    selected: {
        type: Array<Task>,
        required: true,
    },
});
</script>
<script lang="ts">
import SpentTime from "./SpentTime.vue";
import {Task} from "../types/Task";

export default {
    name: "TaskListSummary",
    components: {
        SpentTime,
    },
    computed: {
        count() {
            return this.selected.length;
        },
        summaryEstimated() {
            return this.selected.reduce((acc, item: Task) => {
                return acc + item.estimated_time ?? 0
            }, 0);
        },
        hasSummaryEstimated() {
            return this.summaryEstimated > 0;
        },
        summarySpent() {
            return this.selected.reduce((acc, item: Task) => {
                return acc + item.spent_time;
            }, 0);
        },
        hasSummarySpent() {
            return this.summarySpent > 0;
        },
    },
};
</script>

<template>
    <v-card class="mt-2 pa-2" title="Summary"
            subtitle="Summary of selected tasks">
        <v-card-text>
            <div>
                <v-label
                    text="∑ Estimated time:"
                    class="me-2"
                />
                <span v-if="hasSummaryEstimated">
                    <SpentTime :seconds="summaryEstimated"/>
                </span>
                <span v-else>
                    N/A
                </span>
            </div>
            <div>
                <v-label
                    text="∑ Spent time:"
                    class="me-2"
                />
                <span v-if="hasSummarySpent">
                    <SpentTime :seconds="summarySpent"/>
                </span>
                <span v-else>
                    N/A
                </span>
            </div>
        </v-card-text>
    </v-card>
</template>

<style scoped>

</style>
