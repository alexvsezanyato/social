import User from "../types/user";

let user: User;

export async function getUser(id?: number): Promise<User> {
    if (user !== undefined) {
        return user;
    }

    const uri = new URL('/api/profile/get', window.location.origin);

    if (id) {
        uri.searchParams.set('id', String(id));
    }

    try {
        const response = await fetch(uri);
        user = await response.json();
    } catch (e) {
        console.error('Failed to fetch user data', e);
        return null;
    }

    return user;
}