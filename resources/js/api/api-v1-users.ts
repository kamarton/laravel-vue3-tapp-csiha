import {apiGet} from "./api-caller";

const urlPrefix = '/api/v1/users';

export function apiFetchUserList() {
    return apiGet(urlPrefix);
}
