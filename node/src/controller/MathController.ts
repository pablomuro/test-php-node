import { Request } from "express";
import { arrayLcm } from "../helpers/math";
import {
  INVALID_QUERY_PARAM_LIST_MSG,
  INVALID_QUERY_PARAM_NUMBER_MSG,
  NO_QUERY_PARAMS_MSG,
} from "../helpers/constants";

export class MathController {
  async index(request: Request) {
    const { number = null, numbers = null } = request.query;

    if (number) {
      if (Number.isInteger(Number(number))) {
        return Number(number) + 1;
      } else {
        return INVALID_QUERY_PARAM_NUMBER_MSG;
      }
    }

    if (numbers) {
      try {
        const numbersArray = JSON.parse(numbers as string);
        if (numbersArray && Array.isArray(numbersArray)) {
          return arrayLcm(numbersArray);
        } else {
          throw null;
        }
      } catch (error) {
        return INVALID_QUERY_PARAM_LIST_MSG;
      }
    }

    return NO_QUERY_PARAMS_MSG;
  }
}
