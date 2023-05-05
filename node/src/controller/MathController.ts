import { Request } from "express";
import { arrayLcm } from "../helpers/math";

export class MathController {
  async index(request: Request) {
    const { number = null, numbers = null } = request.query;

    if (number) {
      if (Number.isInteger(Number(number))) {
        return Number(number) + 1;
      } else {
        return "Invalid query parameter number: Not a number";
      }
    }

    if (numbers) {
      try {
        const numbersArray = JSON.parse(numbers);
        if (numbersArray && Array.isArray(numbersArray)) {
          return arrayLcm(numbersArray);
        } else {
          throw "error";
        }
      } catch (error) {
        return "Invalid query parameter numbers: Not a list";
      }
    }

    return "No query parameter passed";
  }
}
