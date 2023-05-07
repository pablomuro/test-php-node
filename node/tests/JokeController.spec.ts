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

const URL_PATH = "/joke";

describe("JokeController.spec test", () => {
  beforeAll(async () => {
    await expressAppSetup(AppDataSource);
  });

  test("Test get method", async () => {
    const res = await request(app).get(`${URL_PATH}`);

    const { body } = res;
    expect(res.statusCode).toBe(200);
    expect(typeof body).toBe("string");
  });

  test("Test get method with a valid parameter", async () => {
    let res = await request(app).get(`${URL_PATH}/Chuck`);

    let { body } = res;
    expect(res.statusCode).toBe(200);
    expect(typeof body).toBe("string");

    res = await request(app).get(`${URL_PATH}/Dad`);

    body = res.body;

    expect(res.statusCode).toBe(200);
    expect(typeof body).toBe("string");
  });

  test("Test create new joke", async () => {
    const res = await request(app)
      .post(`${URL_PATH}`)
      .send({ joke: "New Test Joke" });

    const { body } = res;

    expect(res.statusCode).toBe(200);
    expect(body.jokeText).not.toBe(undefined);
    expect(body.jokeText).toBe("New Test Joke");
  });

  test("Test create new joke with invalid param", async () => {
    const res = await request(app).post(`${URL_PATH}`).send({ joke: "" });

    const { body } = res;

    expect(res.statusCode).toBe(400);
    expect(body).toBe("Invalid Joke Text");
  });

  test("Test edit joke", async () => {
    let res = await request(app)
      .post(`${URL_PATH}`)
      .send({ joke: "New Test Joke" });

    let {
      body: { id },
    } = res;

    res = await request(app)
      .put(`${URL_PATH}`)
      .send({ number: id, joke: "Edited Test Joke" });

    const { body } = res;

    expect(res.statusCode).toBe(200);
    expect(body.id).toBe(id);
    expect(body.jokeText).toBe("Edited Test Joke");
  });

  test("Test edit joke with invalid param", async () => {
    let res = await request(app)
      .put(`${URL_PATH}`)
      .send({ number: "", joke: "Edited Test Joke" });

    let { body } = res;

    expect(res.statusCode).toBe(400);
    expect(body).toBe("Invalid Id");

    res = await request(app)
      .put(`${URL_PATH}`)
      .send({ number: -1, joke: "Edited Test Joke" });

    body = res.body;

    expect(res.statusCode).toBe(400);
    expect(body).toBe("Invalid Id");

    res = await request(app).put(`${URL_PATH}`).send({ number: 1, joke: "" });

    body = res.body;

    expect(res.statusCode).toBe(400);
    expect(body).toBe("Invalid Joke Text");
  });

  test("Test delete joke", async () => {
    let res = await request(app)
      .post(`${URL_PATH}`)
      .send({ joke: "New Test Joke" });

    let {
      body: { id },
    } = res;

    res = await request(app).delete(`${URL_PATH}`).send({ number: id });

    const { body } = res;

    expect(res.statusCode).toBe(200);
    expect(body).toBe("Joke Removed");
  });

  test("Test delete joke with invalid param", async () => {
    let res = await request(app).delete(`${URL_PATH}`).send({ number: "" });

    let { body } = res;

    expect(res.statusCode).toBe(400);
    expect(body).toBe("Invalid Id");

    res = await request(app).delete(`${URL_PATH}`).send({ number: 999 });

    body = res.body;

    expect(res.statusCode).toBe(400);
    expect(body).toBe("Not Found");
  });
});
