import { JokeController } from "./controller/JokeController";
import { MathController } from "./controller/MathController";

export const Routes = [
  {
    method: "get",
    route: "/joke/:apiName?",
    action: "index",
    controller: JokeController,
  },
  {
    method: "post",
    route: "/joke",
    action: "create",
    controller: JokeController,
  },
  {
    method: "put",
    route: "/joke",
    action: "update",
    controller: JokeController,
  },
  {
    method: "delete",
    route: "/joke",
    action: "delete",
    controller: JokeController,
  },
  {
    route: "/math",
    method: "get",
    action: "index",
    controller: MathController,
  },
];
