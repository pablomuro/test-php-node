import "reflect-metadata";
import { DataSource } from "typeorm";
import { Joke } from "./entity/Joke";

export const AppDataSource = new DataSource({
  type: "sqlite",
  database: "database.sqlite",
  synchronize: true,
  logging: false,
  entities: [Joke],
  migrations: [],
  subscribers: [],
});
