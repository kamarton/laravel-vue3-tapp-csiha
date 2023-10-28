import TaskList from "./components/TaskList.vue";
import {createWebHistory, createRouter} from "vue-router";
import TaskForm from "./components/TaskForm.vue";

const routes = [
    {path: '/', component: TaskList},
    {
        path: '/tasks',
        name: 'task.list',
        component: TaskList,
    },
    {
        path: '/tasks/:id([1-9]\\d*)',
        name: 'task.view',
        component: TaskForm,
    },
    {
        path: '/tasks/new',
        name: 'task.new',
        params: {id: null},
        component: TaskForm,
    },
]
const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;

