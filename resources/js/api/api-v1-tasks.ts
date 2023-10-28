import {apiDelete, apiGet, apiPost, apiPut} from "./api-caller";
import {Task} from "../types/Task";

const urlPrefix = '/api/v1/tasks';

export function apiFetchTaskList() {
    return apiGet(urlPrefix);
}

export function apiDeleteTask(task: Task) {
    return apiDelete(`${urlPrefix}/${task.id}`);
}

export function apiCompleteTask(task: Task) {
    return apiPut(`${urlPrefix}/${task.id}?complete=1`);
}

export function apiFetchTask(id: Number) {
    return apiGet(`${urlPrefix}/${id}`);
}

export function apiCreateOrUpdateTask(task: Task) {
    if (null === task.id) {
        return apiPost(urlPrefix, task)
    }
    return apiPut(`${urlPrefix}/${task.id}`, task)
}
