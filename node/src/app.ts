import * as express from "express";
import * as bodyParser from "body-parser";
import { Request, Response } from "express";
import { AppDataSource } from "./data-source";
import { Routes } from "./routes";
import { Joke } from "./entity/Joke";

export const expressApp = async () => {
  await AppDataSource.initialize();
  const app = express();
  app.use(bodyParser.json());
  app.use(express.urlencoded({ extended: true, limit: "1mb" }));

  Routes.forEach((route) => {
    (app as any)[route.method](
      route.route,
      (req: Request, res: Response, next: Function) => {
        const result = new (route.controller as any)()[route.action](
          req,
          res,
          next
        );
        if (result instanceof Promise) {
          result
            .then((result) =>
              result !== null && result !== undefined
                ? res.json(result)
                : undefined
            )
            .catch((error) => res.json(error.message));
        } else if (result !== null && result !== undefined) {
          res.json(result);
        }
      }
    );
  });

  app.listen(3000);

  // const hasJoke = await Joke.findOneBy({ id: 1 });
  // if (!hasJoke) {
  //   const joke = new Joke();
  //   joke.jokeText = "Timber joke";
  //   await joke.save();
  // }

  console.log(
    "Express server has started on port 3000. Open http://localhost:3000/ to see results"
  );
  return app;
};

expressApp();