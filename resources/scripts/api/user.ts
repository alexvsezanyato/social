import {http, api} from '@/services/http-client';
import IUser from "@/types/user";

const prefix = '/users';

export async function getUser(id: number = 0): Promise<IUser> {
    return api<IUser>(http.get(`${prefix}/${id}`));
}

export async function patchUser(id: number = 0, data: any): Promise<void> {
    return api<void>(http.patch(`${prefix}/${id}`, data));
}