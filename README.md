# Test Pablo for Squadmakers

## Database

SQLite

- I choose the SQLite database, because is a in memory DB and it's easier to run.
- Another reason is that for Node and Symfony the database abstraction is the same for any SQL DB.

### SQL

```
CREATE TABLE joke (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, jokeText VARCHAR(255) NOT NULL)
```

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

### OpenAPI Schema

- File: openApi.yaml

## Projects

### Node

How to run:

- `$ cd node; npm i`
- `$ npm run build`
- `$ node build/app.js`

How to test:

- `$ cd node; npm i`
- `$ npm run testApp`

### Symfony

How to run:

- `$ cd symfony; composer install`
- `$ symfony server:start `

How to test:

- `$ cd symfony; composer install`
- `$ php bin/phpunit`
