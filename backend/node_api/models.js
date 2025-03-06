const mongoose = require("mongoose");

const LabSchema = new mongoose.Schema({
    student_id: String,
    experiment_no: Number,
    code: String,
    output: String,
    status: { type: String, enum: ["Pending", "Approved"], default: "Pending" }
});

module.exports = mongoose.model("Lab", LabSchema);
