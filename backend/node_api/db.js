const mongoose = require("mongoose");

mongoose.connect("mongodb://localhost:27017/coding_platform", {
    useNewUrlParser: true,
    useUnifiedTopology: true,
});

module.exports = mongoose;
