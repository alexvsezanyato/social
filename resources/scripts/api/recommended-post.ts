import {http, api} from '@/services/http-client';
import IPost from '@/types/post';

const prefix = '/recommended-posts';

export async function getRecommendedPosts(params: {
    limit?: number,
    from?: number
} = {}): Promise<IPost[]> {
    return api<IPost[]>(http.get(prefix, {
        params: params,
    }));
}