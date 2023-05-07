import { app, expressAppSetup } from "./app";
import { AppDataSource } from "./data-source";

(async () => {
  await expressAppSetup(AppDataSource);
  app.listen(8000);
  console.log(
    "Express server has started on port 8000. Open http://localhost:8000/ to see results"
  );
})();
