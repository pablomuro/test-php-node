import { Request } from "express";
import { Joke } from "../entity/Joke";
import { validateId, validateJokeText } from "../helpers/validators";
import {
  JOKE_REMOVED_MSG,
  NOT_FOUND_MSG,
  NO_JOKE_FOUND_MSG,
} from "../helpers/constants";
import axios from "axios";

const API_ENDPOINTS = {
  Chuck: "https://api.chucknorris.io/jokes/random",
  Dad: "https://icanhazdadjoke.com/",
};

const REQUEST_HEADERS = {
  Accept: "application/json",
  "User-Agent": "test-node-PabloMuro",
};

export class JokeController {
  async index(request: Request) {
    let apiName: string | undefined = request.params.apiName;
    const randIndex: number = Math.round(Math.random());

    apiName = apiName ? apiName : Object.keys(API_ENDPOINTS)[randIndex];

    const apiUrl = API_ENDPOINTS[apiName];

    if (!apiUrl) throw Error(NO_JOKE_FOUND_MSG);

    const { data, status } = await axios.get(apiUrl, {
      headers: REQUEST_HEADERS,
    });

    if (status !== 200) throw Error(NO_JOKE_FOUND_MSG);

    const { value = null, joke = null } = data;

    const response = value ? value : joke;

    if (!response) throw Error(NO_JOKE_FOUND_MSG);

    return response;
  }

  async create(request: Request) {
    const { joke: jokeText } = request.body;

    validateJokeText(jokeText);

    const joke = Object.assign(new Joke(), {
      jokeText,
    });

    return joke.save();
  }
  async update(request: Request) {
    const { number, joke: jokeText } = request.body;
    validateId(parseInt(number));
    validateJokeText(jokeText);

    const id = parseInt(number);

    const joke = await Joke.findOneBy({ id });

    if (!joke) {
      throw Error(NOT_FOUND_MSG);
    }

    joke.jokeText = jokeText;
    await joke.save();

    return joke;
  }

  async delete(request: Request) {
    const { number } = request.body;
    validateId(parseInt(number));

    const id = parseInt(number);

    const joke = await Joke.findOneBy({ id });

    if (!joke) {
      throw Error(NOT_FOUND_MSG);
    }

    await joke.remove();

    return JOKE_REMOVED_MSG;
  }
}
