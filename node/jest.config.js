/** @type {import('ts-jest').JestConfigWithTsJest} */
module.exports = {
  preset: "ts-jest",
  testEnvironment: "node",
  testMatch: ["**/tests/**/*.spec.ts"],
  extensionsToTreatAsEsm: [".ts", ".tsx"],
  testPathIgnorePatterns: [".d.ts", ".js"],
  transform: {
    "^.+\\.(t|j)sx?$": ["@swc-node/jest"],
  },
};
