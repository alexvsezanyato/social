import IUser from "@/types/user";

export default interface IPostComment {
    id: number;
    text: string;
    author: IUser;
}