import {http, api} from '@/services/http-client';
import IPost from '@/types/post';

const prefix = '/posts';

export async function getPosts(params: {
    authorId?: number,
    limit?: number,
    from?: number
} = {}): Promise<IPost[]> {
    return api<IPost[]>(http.get(prefix, {
        params: params,
    }));
}

export function getPost(id: number): Promise<IPost> {
    return api<IPost>(http.get(`${prefix}/${id}`));
}

export function createPost(data: any): Promise<number> {
    return api<number>(http.postForm(prefix, data));
}

export function deletePost(id: number): Promise<void> {
    return api<void>(http.delete(`${prefix}/${id}`));
}