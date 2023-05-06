# test-php-node

symfony check:requirements

composer require symfony/http-client
composer require symfony/orm-pack
composer require --dev symfony/maker-bundle

### SQL

- 'CREATE TABLE joke (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, jokeText VARCHAR(255) NOT NULL)'

### NoSQL - MongoDB

```
db.createCollection("joke", {
  validator: {
    $jsonSchema: {
      required: ["_id", "jokeText"],
      properties: {
        _id: { bsonType: "int", description: "PK id becomes _id " },
        jokeText: { bsonType: "string" },
      },
    }
  },
  autoIndexID: true,
});

```
