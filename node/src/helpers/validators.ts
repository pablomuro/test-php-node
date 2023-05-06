import * as yup from "yup";
import { INVALID_ID_MSG, INVALID_JOKE_MSG } from "./constants";

const ID_SCHEMA = yup.number().required().positive().integer();

const TEXT_SCHEMA = yup.string().required().trim();

export const validateId = (id: any): void => {
  try {
    ID_SCHEMA.validateSync(id);
  } catch (error) {
    throw Error(INVALID_ID_MSG);
  }
};

export const validateJokeText = (text: any): void => {
  try {
    TEXT_SCHEMA.validateSync(text);
  } catch (error) {
    throw Error(INVALID_JOKE_MSG);
  }
};
