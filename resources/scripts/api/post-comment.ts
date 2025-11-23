import {http, api} from '@/services/http-client';
import type IPostComment from '@/types/post-comment';

const prefix = '/post-comments';

export async function getPostComments(params: {
    postId: number,
    authorId?: number,
    limit?: number,
    from?: number,
}): Promise<IPostComment[]> {
    return api<IPostComment[]>(http.get(prefix, {
        params: params,
    }));
}

export function getPostComment(id: number): Promise<IPostComment> {
    return api<IPostComment>(http.get(`${prefix}/${id}`));
}

export function createPostComment(data: {
    postId: number,
    text: string,
}): Promise<number> {
    return api<number>(http.post(prefix, data));
}

export function deletePostComment(id: number): Promise<void> {
    return api<void>(http.delete<void>(`${prefix}/${id}`));
}