<script lang="ts" setup>
import TaskActions from "./TaskActions.vue";

defineProps();
</script>
<script lang="ts">

import {useRoute} from "vue-router";
import {apiFetchTask, apiCreateOrUpdateTask} from "../api/api-v1-tasks";
import {apiFetchUserList} from "../api/api-v1-users";
import SpentTimePicker from "./SpentTimePicker.vue";
import {User} from "../types/User";
import {Task} from "../types/Task";
import SpentTime from "./SpentTime.vue";

const EMPTY_USER: User = {
    id: null,
    user_name: "* Unassigned *",
};
const EMPTY_TASK: Task = {
    id: null,
    name: "",
    description: "",
    assigned_user: EMPTY_USER,
    estimated_time: 0,
    spent_time: 0,
    completed_at: null,
};
export default {
    name: "TaskForm",
    components: {
        SpentTimePicker,
        SpentTime,
    },
    data() {
        return {
            rulesName: [
                (v: string) => !!v && v.trim().length > 0 || "Task name is required and cannot be blank",
                (v: string) => !!v && v.length <= 255 || 'Task name must be less than 255 characters',
            ],
            rulesDescription: [
                (v: string | null) => null === v || v.length <= 2048 || 'Task description must be less than 4096 characters',
            ],
            updateFailed: false,
            loadingTask: false,
            loadingUsers: false,
            taskCompleted: false,
            users: [],
            loadingFailed: false,
            timeSpentAdd: 0,
            task: EMPTY_TASK,
            tasksForActions: [],
            validForm: false,
        }
    },
    methods: {
        loadData() {
            this.loadingUsers = true;
            apiFetchUserList().then((response) => {
                const data: Array<User> = response.data;
                data.unshift(EMPTY_USER)
                this.users = data;
            }).catch((data) => {
            }).finally(() => {
                this.loadingUsers = false;
            });
            if (null === this.task.id) {
                this.fill({...EMPTY_TASK});
                return;
            }
            this.loadingTask = true;
            apiFetchTask(this.task.id).then((response) => {
                const data: Task = response.data;
                this.fill(data);
            }).catch((data) => {
                this.loadingFailed = true;
            }).finally(() => {
                this.loadingTask = false;
            });
        },
        fill(task : Task) {
            this.task = task;
            this.tasksForActions.length = 0;
            this.tasksForActions.push(task);
            this.taskCompleted = null !== task.completed_at;
            if (null === task.assigned_user) {
                task.assigned_user = EMPTY_USER;
            }
            this.timeSpentAdd = 0;
        },
        save() {
            this.loadingTask = true;
            apiCreateOrUpdateTask({
                id: this.task.id,
                name: this.task.name,
                description: this.task.description,
                assigned_user_id: this.task.assigned_user?.id ?? null,
                estimated_time: this.task.estimated_time,
                spent_time_add: this.timeSpentAdd,
            }).then((response) => {
                if (null === this.task.id) {
                    this.$router.push({
                        name: 'task.view',
                        params: {
                            id: response.data.id,
                        }
                    });
                    return;
                }
                this.fill(response.data);
            }).catch((data) => {
                this.updateFailed = true;
            }).finally(() => {
                this.loadingTask = false;
            });
        },
        submitHandler() {
            if (this.$refs.taskFormName.validate() && this.validForm) {
                this.save();
                return
            }
        },
        taskDeletedResult(task: Task) {
            this.$router.push({name: 'task.list'});
        },
        taskCompletedResult(task: Task) {
            this.fill(task);
        },
    },
    computed: {
        formDisabled() {
            return this.loadingTask || this.loadingUsers || this.loadingFailed;
        },
        titleText() {
            if(this.loadingTask || this.loadingUsers || this.loadingFailed) {
                return "Loading...";
            }
            if (null === this.task.id) {
                return "New task";
            }
            if(this.task.completed_at !== null) {
                return "View task";
            }
            return "Edit task";
        },
    },
    mounted() {
        const route = useRoute();
        this.task.id = route.params?.id ?? null;
        this.loadData();
    },
};
</script>

<template>
    <v-alert
        v-if="loadingFailed"
        type="error"
        dismissible
    >
        <span>An error occurred while loading the task and other resources. Please try again later or
        <router-link :to="{name: 'task.list'}">
            back to task list.
        </router-link>
        </span>
    </v-alert>
    <v-alert
        v-if="updateFailed"
        type="error"
        dismissible
    >
        An error occurred while update or create the task and other resources.
    </v-alert>
    <div class="mb-4">
        <TaskActions
            :tasks="tasksForActions"
            class="float-end"
            :button-icon-only="false"
            button-size="default"
            @deleted="taskDeletedResult"
            @completed="taskCompletedResult"
            :tooltip-enabled="false"
        />
        <h1>
            <v-icon
                v-if="taskCompleted"
                icon="mdi-check-circle"
                size="extra-large"
                color="green"
                class="me-2"
            />
            <span v-text="titleText"/>
        </h1>
    </div>
    <v-card>
        <v-form @submit.prevent="submitHandler"
                v-model="validForm"
                ref="taskFormName"
                lazy-validation
                validate-on-blur
                :disabled="formDisabled">
            <v-text-field
                :readonly="taskCompleted || formDisabled"
                v-model="task.name"
                :rules="rulesName"
                label="Name"
            />
            <v-textarea
                :readonly="taskCompleted || formDisabled"
                v-model="task.description"
                :rules="rulesDescription"
                label="Description"
            />
            <v-select
                :readonly="taskCompleted || formDisabled"
                v-model="task.assigned_user"
                :return-object="true"
                :items="users"
                item-text="user_name"
                item-value="id"
                item-title="user_name"
                label="Assignee user"
            />

            <v-text-field
                :readonly="taskCompleted || formDisabled"
                v-if="taskCompleted"
                v-model="task.completed_at"
                label="Completed at"
                readonly="readonly"
            />

            <v-container>
                <v-row>
                    <v-label>Estimated time</v-label>
                </v-row>
            </v-container>

            <SpentTimePicker
                :readonly="taskCompleted || formDisabled"
                :seconds="task.estimated_time ?? 0"
                @update="this.task.estimated_time = $event"
            />
            <v-container>
                <v-row>
                    <v-label class="me-2">Spent time:</v-label>
                    <SpentTime :seconds="task.spent_time ?? 0"/>
                </v-row>
            </v-container>

            <v-container>
                <v-row>
                    <v-label>Add spent time</v-label>
                </v-row>
            </v-container>

            <SpentTimePicker
                :seconds="timeSpentAdd"
                :readonly="taskCompleted || formDisabled"
                @update="timeSpentAdd = $event"
            />

            <v-btn
                :loading="loadingTask || loadingUsers"
                :disabled="formDisabled"
                @click="submitHandler"
                color="primary"
                block=""
                class="mt-2"
                text="Save"
            />
        </v-form>
    </v-card>
</template>

<style scoped>

</style>
