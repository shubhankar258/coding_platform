const express = require("express");
const Lab = require("./models");

const router = express.Router();

router.get("/labs", async (req, res) => {
    const labs = await Lab.find();
    res.json(labs);
});

router.post("/labs", async (req, res) => {
    const newLab = new Lab(req.body);
    await newLab.save();
    res.json({ message: "Lab submitted" });
});

module.exports = router;