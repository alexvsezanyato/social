import axios, {AxiosResponse} from 'axios';

export const http = axios.create({
    baseURL: '/api',
    headers: {
        'Content-Type': 'multipart/form-data',
    },
});

http.interceptors.response.use(
    response => {
        return response;
    },
    error => {
        console.error(error);
        return Promise.reject(error);
    },
);

export async function api<T>(promise: Promise<AxiosResponse<T>>): Promise<T> {
  const response = await promise;
  return response.data;
}