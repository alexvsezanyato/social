import {getUser} from "@/api/user";

export default {
    user: await getUser(),
};