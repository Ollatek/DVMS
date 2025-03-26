const express = require("express");
const bodyParser = require("body-parser");
const cors = require("cors");
const axios = require("axios");
const mysql = require("mysql2");
require("dotenv").config();

const app = express();
const port = 5000;

app.use(cors());
app.use(bodyParser.json());

// Database Connection
const db = mysql.createConnection({
  host: "localhost",
  user: "root",
  password: "Olakunle",
  database: "hmisphp",
});

db.connect((err) => {
  if (err) {
    console.error("Database connection failed:", err);
  } else {
    console.log("Connected to DVMS database");
  }
});

// AI Chat API Endpoint
app.post("/api/chat", async (req, res) => {
  const userMessage = req.body.message;

  if (!userMessage) {
    return res.status(400).json({ error: "Message is required" });
  }

  try {
    // Extract possible drug name
    const words = userMessage.split(" ");
    const drugName = words.find((word) => word.length > 3);

    if (drugName) {
      db.query("SELECT * FROM drugphp WHERE name = ?", [drugName], async (err, results) => {
        if (err) {
          return res.status(500).json({ error: "Database query error" });
        }

        if (results.length > 0) {
          const drugData = results[0];
          return res.json({ reply: `✅ Drug Found: ${drugData.name}, Manufacturer: ${drugData.manufacturer}, Expiry: ${drugData.expiry_date}` });
        } else {
          // If drug not found, ask AI
          const response = await axios.post(
            "https://api.openai.com/v1/chat/completions",
            {
              model: "gpt-3.5-turbo",
              messages: [
                { role: "system", content: "You are an AI assistant for drug verification." },
                { role: "user", content: userMessage }
              ],
            },
            {
              headers: {
                "Authorization": `Bearer ${process.env.OPENAI_API_KEY}`,
                "Content-Type": "application/json",
              },
            }
          );

          res.json({ reply: response.data.choices[0].message.content });
        }
      });
    } else {
      res.json({ reply: "Please provide a drug name for verification." });
    }
  } catch (error) {
    console.error("Error communicating with AI:", error);
    res.status(500).json({ error: "Failed to generate response" });
  }
});

// Start Server
app.listen(port, () => {
  console.log(`Chat AI backend running on http://localhost:${port}`);
});
