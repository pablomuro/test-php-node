import { describe, expect, test } from "@jest/globals";
import { expressAppSetup, app } from "../src/app";
import * as request from "supertest";
import { DataSource } from "typeorm";
import { Joke } from "../src/entity/Joke";
import "reflect-metadata";

const AppDataSource: DataSource = new DataSource({
  type: "sqlite",
  database: ":memory:",
  synchronize: true,
  logging: false,
  entities: [Joke],
  migrations: [],
  subscribers: [],
});

const URL_PATH = "/math";

describe("MathController.spec test", () => {
  beforeAll(async () => {
    await expressAppSetup(AppDataSource);
  });

  test("Test Request With A Valid Number", async () => {
    const validNumber = 1;

    const res = await request(app).get(`${URL_PATH}?number=${validNumber}`);

    const { body } = res;
    expect(res.statusCode).toBe(200);
    expect(body).toBe(2);
  });

  test("Test Request With A Invalid Number", async () => {
    const invalidNumber = "a";

    const res = await request(app).get(`${URL_PATH}?number=${invalidNumber}`);

    const { body } = res;

    expect(res.statusCode).toBe(400);
    expect(body).toBe("Invalid query parameter number: Not a number");
  });

  test("Test Request With A Valid Numbers Array", async () => {
    const validNumbersArray = "[2,7,3,9,4]";

    const res = await request(app).get(
      `${URL_PATH}?numbers=${validNumbersArray}`
    );

    const { body } = res;

    expect(res.statusCode).toBe(200);
    expect(body).toBe(252);
  });

  test("Test Request With A Invalid Numbers Array", async () => {
    let invalidNumbers = "[2,7,3,9,'a']";

    let res = await request(app).get(`${URL_PATH}?numbers=${invalidNumbers}`);

    let { body } = res;

    expect(res.statusCode).toBe(400);
    expect(body).toBe("Invalid query parameter numbers: Not a list");

    invalidNumbers = '3,9,"a"';

    res = await request(app).get(`${URL_PATH}?numbers=${invalidNumbers}`);

    body = res.body;

    expect(res.statusCode).toBe(400);
    expect(body).toBe("Invalid query parameter numbers: Not a list");
  });
});
