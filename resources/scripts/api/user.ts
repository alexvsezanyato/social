import {http, api} from '@/services/http-client';
import IUser from "@/types/user";

const prefix = '/users';

export async function getUsers(): Promise<IUser[]> {
    return api<IUser[]>(http.get(`${prefix}`));
}

export async function getUser(id: number = 0): Promise<IUser> {
    return api<IUser>(http.get(`${prefix}/${id}`));
}

export async function patchUser(id: number = 0, data: any): Promise<void> {
    return api<void>(http.patch(`${prefix}/${id}`, data));
}

export async function getFriends(userId: number): Promise<IUser[]> {
    return api<IUser[]>(http.get(`${prefix}/${userId}/friends`));
}

export async function addFriend(userId: number, friendId: number): Promise<void> {
    return api<void>(http.put(`${prefix}/${userId}/friends/${friendId}`));
}

export async function removeFriend(userId: number, friendId: number): Promise<void> {
    return api<void>(http.delete(`${prefix}/${userId}/friends/${friendId}`));
}

export async function getSuggestedFriends(userId: number): Promise<IUser[]> {
    return api<IUser[]>(http.get(`${prefix}/${userId}/friends/suggestions`));
}