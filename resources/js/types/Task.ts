import {User} from "./User";

export type Task = {
    id: number | null,
    name: string,
    description: string,
    updated_at: string,
    created_at: string,
    assigned_user: User | null,
    deleted_at: string | null | undefined,
    spent_time: number | null,
    estimated_time: number | null,
    completed_at: string | null,
}
