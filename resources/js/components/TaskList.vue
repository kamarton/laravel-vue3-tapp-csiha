<script lang="ts" setup>

defineProps({});
</script>
<script lang="ts">
import {apiFetchTaskList, apiDeleteTask, apiCompleteTask} from "../api/api-v1-tasks";
import {VDataTable} from 'vuetify/labs/VDataTable'
import {Task} from "../types/Task";
import ConfirmDialog from "./ConfirmDialog.vue";
import SpentTime from "./SpentTime.vue";
import ProgressDialog from "./ProgressDialog.vue";
import TaskActions from "./TaskActions.vue";
import TaskListSummary from "./TaskListSummary.vue";

const tableHeaders = [
    {
        title: "ID",
        align: "end",
        sortable: true,
        key: "id",
        width: "1%",
    },
    {
        title: "Name",
        align: "start",
        sortable: true,
        key: "name",
        width: "50%",
    },
    {
        title: "Created at",
        align: "start",
        sortable: true,
        key: "created_at",
        width: "20%",
    },
    {
        title: "Assigned to",
        align: "start",
        sortable: true,
        key: "assigned_user.user_name",
        width: "20%",
    },
    {
        title: "Actions",
        align: "end",
        sortable: false,
        key: "actions",
    },
]

export default {
    name: "TaskList",
    components: {
        VDataTable,
        SpentTime,
        ConfirmDialog,
        ProgressDialog,
        TaskActions,
        TaskListSummary,
    },
    data() {
        return {
            search: "",
            items: [],
            loading: true,
            selectedItems: [],
            apiInProgress: false,
            showErrorDialog: false,
            errorDialogMessage: "",
        }
    },
    computed: {
        totalItems() {
            return this.items.length;
        },
        hasSelected() {
            return this.selectedItems.length > 0;
        },
    },
    methods: {
        fetchItems() {
            this.loading = true
            apiFetchTaskList().then(response => {
                this.items = response.data;
            }).catch((data) => {
            }).finally(() => {
                this.loading = false;
            })
        },
        startErrorDialog(message: String | null) {
            this.showErrorDialog = true;
            this.errorDialogMessage = message ?? "An error occurred. Please try again later.";
        },
        closeErrorDialog() {
            this.showErrorDialog = false;
        },
        gotoNewTask() {
            this.$router.push({name: 'task.new'});
        },
    },
    mounted() {
        this.fetchItems();
    },
};
</script>

<template>
    <div class="mb-4">
        <v-btn
            :disabled="loading"
            color="primary"
            prepend-icon="mdi-refresh"
            @click="fetchItems"
            class="float-end ma-2"
            title="Refresh task list"
        >
            Refresh
        </v-btn>
        <v-btn
            :disabled="loading"
            color="secondary"
            prepend-icon="mdi-plus-circle"
            @click="gotoNewTask"
            class="float-end ma-2"
            title="Refresh task list"
        >
            New task
        </v-btn>
        <h1>Tasks</h1>
    </div>
    <v-card>
        <v-card-title>
            <v-text-field
                v-model="search"
                append-icon="mdi-magnify"
                label="Search"
                single-line
                hide-details
            ></v-text-field>
        </v-card-title>
        <v-card-actions class="float-end">
            <TaskActions
                :tasks="selectedItems"
                button-size="small"
                :button-icon-only="false"
                button-delete-text="Delete selected"
                button-complete-text="Mark selected as complete"
                :tooltip-enabled="false"
            />
        </v-card-actions>
        <v-data-table
            v-model="selectedItems"
            :headers="tableHeaders"
            :items="items"
            :loading="loading"
            :total-items="totalItems"
            :search="search"
            item-key="id"
            return-object
            show-select
            class="elevation-1 mt-2"
        >
            <template v-slot:item.name="{ item }">
                <span style="width:30px" class="d-inline-block">
                    <v-icon
                        v-if="null !== item.completed_at"
                        color="green">
                        mdi-check-circle
                    </v-icon>
                </span>
                <span
                    v-if="item.deleted_at !== undefined"
                    v-text="item.name" class="text-decoration-line-through"
                />
                <router-link
                    v-else
                    :to="{name: 'task.view', params: {id: item.id}}"
                    class="text-decoration-none"
                >
                    <span v-text="item.name"/>
                </router-link>
            </template>
            <template v-slot:item.assigned_user.user_name="{ item }">
                <span
                    v-if="item.assigned_user?.id ?? null"
                >
                    <v-icon
                        color="primary"
                        size="small"
                        icon="mdi-account"
                        class="me-1"
                    />
                    <span
                        v-text="item.assigned_user?.user_name ?? ''"
                        :class="{ 'text-decoration-line-through': item.deleted_at !== undefined }"
                    />
                </span>
            </template>
            <template v-slot:item.created_at="{ item }">
                <span v-text="new Date(item.created_at).toLocaleString()"
                      class="text-caption"
                      :class="{ 'text-decoration-line-through': item.deleted_at !== undefined }"
                />
            </template>
            <template v-slot:item.actions="{ item }">
                <TaskActions :tasks="[item]"/>
            </template>
        </v-data-table>
    </v-card>
    <TaskListSummary :selected="selectedItems" v-if="hasSelected"/>
    <ProgressDialog
        message="Sending request..."
        v-model="apiInProgress"
    />
    <ConfirmDialog
        title="Error"
        v-model="showErrorDialog"
        :message="errorDialogMessage"
        confirmText="OK"
        @confirm="closeErrorDialog"
    />

</template>

<style scoped>

</style>
