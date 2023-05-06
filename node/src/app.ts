import * as express from "express";
import * as bodyParser from "body-parser";
import { Request, Response } from "express";
import { AppDataSource } from "./data-source";
import { Routes } from "./routes";
import {
  serve as swaggerServe,
  setup as swaggerSetup,
} from "swagger-ui-express";
import * as YAML from "yaml";
import { readFileSync } from "fs";
import { join } from "path";
import * as sanitizer from "express-html-sanitizer";

export const expressApp = async () => {
  await AppDataSource.initialize();
  const app = express();
  app.use(bodyParser.json());
  app.use(express.urlencoded({ extended: true, limit: "1mb" }));
  const sanitizeReqBody = sanitizer();
  app.use(sanitizeReqBody);

  const file = readFileSync(join(__dirname, "../openApi.yaml"), "utf8");
  const swaggerDocument = YAML.parse(file);

  app.use("/api-docs", swaggerServe, swaggerSetup(swaggerDocument));

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
            .catch((error) => res.status(400).json(error.message));
        } else if (result !== null && result !== undefined) {
          res.json(result);
        }
      }
    );
  });

  app.listen(8000);

  console.log(
    "Express server has started on port 3000. Open http://localhost:3000/ to see results"
  );
  return app;
};

expressApp();
