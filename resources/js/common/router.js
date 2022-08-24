import route from "ziggy";
import { Ziggy } from "../ziggy";

export const Router = {
    route: (name, params, absolute, config = Ziggy) => route(name, params, absolute, config),
};
