const express = require("express");
const cors = require("cors");
const mongoose = require("./db");
const routes = require("./routes");

const app = express();
app.use(express.json());
app.use(cors());

app.use("/api", routes);

app.listen(5000, () => console.log("Server running on port 5000"));
