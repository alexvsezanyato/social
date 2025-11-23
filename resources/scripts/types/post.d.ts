import IUser from "@/types/user";
import IPicture from "@/types/picture";
import IDocument from "@/types/document";
import IPostComment from "@/types/post-comment";
import ICreatedAt from "@/types/created-at";

export default interface IPost {
    id: number;
    text: string;
    author: IUser;
    createdAt: ICreatedAt;
    pictures: IPicture[];
    documents: IDocument[];
    comments: IPostComment[];
}