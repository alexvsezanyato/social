import PostData from './../types/post.d';

export async function getPosts(params: {userId?: number, limit?: number, from?: number} = {}): Promise<PostData[]> {
    const uri = new URL('/api/posts', window.location.origin);
    uri.searchParams.set('limit', String(params.limit || 10));
    uri.searchParams.set('from', String(params.from || 0));

    if (params.userId !== undefined) {
        uri.searchParams.set('user_id', String(params.userId));
    }

    const response = await fetch(uri);
    return await response.json();
}