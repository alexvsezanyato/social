interface Author {
    id: number;
    public: string;
}

interface Picture {
    id: number;
    name: string;
    source: string;
    mime: string;
}

interface Document {
    id: number;
    name: string;
    source: string;
    mime: string;
}

interface Comment {
    id: number;
    text: string;
    author: Author;
}

export default interface Post {
    id: number;
    text: string;
    author: Author;
    createdAt: { date: string, time: string };
    pictures: Picture[];
    documents: Document[];
    comments: Comment[];
}