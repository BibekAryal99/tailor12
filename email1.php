<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Send Email</title>
</head>
<body>

<div class="container">
  <h1>Send Email</h1>
  <form id="emailForm">
    <div class="form-group">
      <label for="recipient">Recipient:</label>
      <input type="email" id="recipient" name="recipient" required>
    </div>
    <div class="form-group">
      <label for="subject">Subject:</label>
      <input type="text" id="subject" name="subject" required>
    </div>
    <div class="form-group">
      <label for="message">Message:</label>
      <textarea id="message" name="message" required></textarea>
    </div>
    <button type="submit">Send Email</button>
    <div id="status"></div>
  </form>
</div>
<style>
    body {
  font-family: Arial, sans-serif;
}

.container {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
}

.form-group {
  margin-bottom: 20px;
}

label {
  display: block;
  margin-bottom: 5px;
}

input[type="email"],
input[type="text"],
textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

button {
  padding: 10px 20px;
  background-color: #007bff;
  color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button:hover {
  background-color: #0056b3;
}

#status {
  margin-top: 10px;
  font-weight: bold;
}

</style>
<script>
    document.getElementById('emailForm').addEventListener('submit', function(event) {
  event.preventDefault();

  var recipient = document.getElementById('recipient').value;
  var subject = document.getElementById('subject').value;
  var message = document.getElementById('message').value;

  // Replace 'YOUR_SENDGRID_API_KEY' with your actual SendGrid API key
  var apiKey = 'YOUR_SENDGRID_API_KEY';

  // Send email using SendGrid API
  fetch('https://api.sendgrid.com/v3/mail/send', {
    method: 'POST',
    headers: {
      'Authorization': 'Bearer ' + apiKey,
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      personalizations: [
        {
          to: [{ email: recipient }],
          subject: subject
        }
      ],
      from: { email: 'aryalb652@gmail.com' }, // Replace with your email address
      content: [
        {
          type: 'text/plain',
          value: message
        }
      ]
    })
  })
  .then(response => {
    if (response.ok) {
      document.getElementById('status').textContent = 'Email sent successfully!';
    } else {
      document.getElementById('status').textContent = 'Failed to send email. Please try again later.';
    }
  })
  .catch(error => {
    console.error('Error:', error);
    document.getElementById('status').textContent = 'An error occurred. Please try again later.';
  });
});

</script>

</body>
</html>
