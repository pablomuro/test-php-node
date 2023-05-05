const gcd = (a: number, b: number): number => {
  if (b === 0) {
    return a;
  }
  return gcd(b, a % b);
};

const lcm = (a: number, b: number): number => {
  return (a * b) / gcd(a, b);
};

export const arrayLcm = (arr: number[]): number => {
  let result = arr[0];
  for (let i = 1; i < arr.length; i++) {
    result = lcm(result, arr[i]);
  }
  return result;
};
