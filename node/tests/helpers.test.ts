import { describe, expect, test } from "@jest/globals";
import { validateJokeText, validateId } from "../src/helpers/validators";
import { arrayLcm } from "../src/helpers/math";

describe.skip("helpers test", () => {
  test("test validateId", () => {
    expect(() => {
      validateId(null);
    }).toThrowError();
    expect(() => {
      validateId(1);
    }).not.toThrowError();
  });

  test("test validateJokeText", () => {
    expect(() => {
      validateJokeText(null);
    }).toThrowError();
    expect(() => {
      validateJokeText("teste");
    }).not.toThrowError();
  });

  test("test arrayLcm", () => {
    expect(arrayLcm([2, 7, 3, 9, 4])).toBe(252);
  });
});
