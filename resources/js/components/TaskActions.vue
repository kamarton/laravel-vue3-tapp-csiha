<script setup lang="ts">
import type {Task} from "../types/Task";
import type {PropType} from 'vue'

defineProps({
    tasks: {
        type: Object as PropType<Array<Task>>,
        default: () => [],
    },
    buttonIconOnly: {
        type: Boolean,
        required: false,
        default: true,
    },
    buttonSize: {
        type: String,
        required: false,
        default: "x-small",
    },
    buttonCompleteText: {
        type: String,
        required: false,
        default: "Complete",
    },
    buttonDeleteText: {
        type: String,
        required: false,
        default: "Delete",
    },
    tooltipEnabled: {
        type: Boolean,
        required: false,
        default: true,
    },
});
</script>
<script lang="ts">

import ConfirmDialog from "./ConfirmDialog.vue";
import ProgressDialog from "./ProgressDialog.vue";
import {apiCompleteTask, apiDeleteTask} from "../api/api-v1-tasks";
import {Task} from "../types/Task";

export default {
    name: "TaskActions",
    emits: ['deleted', 'completed'],
    components: {
        ConfirmDialog,
        ProgressDialog,
    },
    data() {
        return {
            showCompleteDialog: false,
            showDeleteDialog: false,
            errorDialogMessage: "",
            showErrorDialog: false,
            apiInProgress: 0,
            showApiProgress: false,
            buttonIconComplete: "mdi-check-circle",
            buttonIconTrash: "mdi-trash-can-outline",
        }
    },
    computed: {
        buttonCompletePrependIcon() {
            return this.buttonIconOnly ? null : this.buttonIconComplete;
        },
        buttonCompleteIcon() {
            return this.buttonIconOnly ? this.buttonIconComplete : null;
        },
        buttonDeletePrependIcon() {
            return this.buttonIconOnly ? null : this.buttonIconTrash;
        },
        buttonDeleteIcon() {
            return this.buttonIconOnly ? this.buttonIconTrash : null;
        },
        completeDialogMessage() {
            if (this.completableTaskCount < 2) {
                return "Are you sure you want to mark this task as completed?";
            }
            return "Are you sure you want to mark these " + this.completableTaskCount + " tasks as completed?";
        },
        completeDialogTitle() {
            if (this.completableTaskCount < 2) {
                return "Complete task";
            }
            return "Complete tasks";
        },
        deleteDialogMessage() {
            if (this.deletableTaskCount < 2) {
                return "Are you sure you want to delete this task?";
            }
            return "Are you sure you want to delete these " + this.deletableTaskCount + " tasks?";
        },
        deleteDialogTitle() {
            if (this.deletableTaskCount < 2) {
                return "Delete task";
            }
            return "Delete tasks";
        },
        showView() {
            return this.hasCompletableTasks || this.hasDeletableTasks
        },
        deletableTasks() {
            return this.tasks.filter((task: Task) => {
                return (task.deleted_at ?? null) === null && task.id !== null;
            });
        },
        deletableTaskCount() {
            return this.deletableTasks.length;
        },
        hasDeletableTasks() {
            return this.deletableTaskCount > 0;
        },
        completableTasks() {
            return this.tasks.filter((task: Task) => {
                return (task.deleted_at ?? null) === null
                    && (task.completed_at ?? null) === null
                    && task.id !== null;
            });
        },
        completableTaskCount() {
            return this.completableTasks.length;
        },
        hasCompletableTasks() {
            return this.completableTaskCount > 0;
        },
    },
    methods: {
        startDeleteDialog() {
            this.showDeleteDialog = true;
        },
        closeDeleteDialog() {
            this.showDeleteDialog = false;
        },
        startCompletedDialog() {
            this.showCompleteDialog = true;
        },
        closeCompletedDialog() {
            this.showCompleteDialog = false;
        },
        closeErrorDialog() {
            this.showErrorDialog = false;
        },
        startErrorDialog(message: String | null) {
            this.showErrorDialog = true;
            this.errorDialogMessage = message ?? "An error occurred. Please try again later.";
        },
        deleteItems() {
            this.closeDeleteDialog();
            this.deletableTasks.forEach((task: Task) => {
                this.apiInProgress = this.apiInProgress + 1;
                apiDeleteTask(task).then(() => {
                    task.deleted_at = new Date().toISOString();
                    this.$emit('deleted', task);
                }).catch((data) => {
                    this.startErrorDialog(data.response?.data?.message ?? null);
                }).finally(() => {
                    this.apiInProgress = this.apiInProgress - 1;
                });
            });
        },
        markCompletes() {
            this.closeCompletedDialog();
            this.completableTasks.forEach((task: Task) => {
                this.apiInProgress = this.apiInProgress + 1;
                apiCompleteTask(task).then((response) => {
                    let responseTask: Task = response.data;
                    task.completed_at = responseTask.completed_at ?? task.completed_at;
                    task.updated_at = responseTask.updated_at ?? task.updated_at;
                    this.$emit('completed', task);
                }).catch((data) => {
                    this.startErrorDialog(data.response?.data?.message ?? null);
                }).finally(() => {
                    this.apiInProgress = this.apiInProgress - 1;
                });
            });
        },
    },
    watch: {
        apiInProgress() {
            this.showApiProgress = this.apiInProgress > 0;
        }
    },
}
</script>

<template>
    <div class="text-no-wrap" v-if="showView">
        <v-tooltip text="Mark as complete" :disabled="!tooltipEnabled">
            <template v-slot:activator="{ props }">
                <v-btn
                    v-bind="props"
                    v-if="hasCompletableTasks"
                    color="green"
                    :icon="buttonCompleteIcon"
                    :prepend-icon="buttonCompletePrependIcon"
                    :size="buttonSize"
                    @click="startCompletedDialog(item)"
                    class="ma-1"
                    :text="buttonCompleteText"
                />
            </template>
        </v-tooltip>
        <v-tooltip text="Delete this task" :disabled="!tooltipEnabled">
            <template v-slot:activator="{ props }">
                <v-btn
                    v-if="hasDeletableTasks"
                    v-bind="props"
                    color="error"
                    :icon="buttonDeleteIcon"
                    :prepend-icon="buttonDeletePrependIcon"
                    :size="buttonSize"
                    @click="startDeleteDialog(item)"
                    class="ma-1"
                    :text="buttonDeleteText"
                />
            </template>
        </v-tooltip>
        <ConfirmDialog
            :title="deleteDialogTitle"
            v-model="showDeleteDialog"
            :message="deleteDialogMessage"
            confirmText="Delete"
            @cancel="closeDeleteDialog"
            @confirm="deleteItems"/>
        <ProgressDialog
            message="Sending request(s)..."
            v-model="showApiProgress"
        />
        <ConfirmDialog
            title="Error"
            v-model="showErrorDialog"
            :message="errorDialogMessage"
            confirmText="OK"
            @confirm="closeErrorDialog"
        />
        <ConfirmDialog
            :title="completeDialogTitle"
            v-model="showCompleteDialog"
            :message="completeDialogMessage"
            confirmText="Complete"
            @cancel="closeCompletedDialog"
            @confirm="markCompletes"
        />
    </div>
</template>

<style scoped>

</style>
