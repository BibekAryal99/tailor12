const express = require("express");
const bodyParser = require("body-parser");
const nodemailer = require("nodemailer");

const app = express();
const port = 3000;

// Middleware to parse JSON bodies
app.use(bodyParser.json());

// Endpoint to handle sending emails
app.post("/send-email", (req, res) => {
  const { recipient, subject, message } = req.body;

  const transporter = nodemailer.createTransport({
    service: "Gmail",
    auth: {
      user: "rai729678@gmail.com",
      pass: "ripshot1$"
    }
  });

  const mailOptions = { 
    from: "rai729678@gmail.com",
    to: "aryalb652@gmail.com",
    subject: subject,
    text: message
  };

  transporter.sendMail(mailOptions, (error, info) => {
    if (error) {
      console.error("Error sending email:", error);
      res.status(500).send("Error sending email. Please try again later.");
    } else {
      console.log("Email sent:", info.response);
      res.status(200).send("Email sent successfully!");
    }
  });
});

// Start the server
app.listen(port, () => {
  console.log(`Server is running on http://localhost:${port}`);
});
