
const express = require('express');
const nodemailer = require('nodemailer');
const bodyParser = require('body-parser');

const app = express();
const port = 3000;

// Middleware to parse the form data
app.use(bodyParser.urlencoded({ extended: true }));

// Serve the static HTML file
app.use(express.static('public'));

// Route to handle form submission
app.post('/send-email', (req, res) => {
  console.log('Received a POST request at /send-email');
  const userEmail = req.body.email;

  // Create a transporter object using the default SMTP transport
  let transporter = nodemailer.createTransport({
    service: 'gmail',
    auth: {
        user: "falihaabdulsalam@gmail.com", // replace with your email
        pass: "behm stsr cuvj pill"  // replace with your app-specific password
        }
  });

  // Setup email data
  let mailOptions = {
    from: 'your-email@gmail.com', // sender address
        to: 'falihaabdulsalam@gmail.com', // receiver's address
    subject: 'New Email from User', // Subject line
    text: `You have received an email from ${userEmail}` // plain text body
  };

  // Send mail with defined transport object
  transporter.sendMail(mailOptions, (error, info) => {
    if (error) {
      return res.status(500).send('Error sending email: ' + error.message);
    }
    res.send('Email sent: ' + info.response);
  });
});

app.listen(port, () => {
  console.log(`Server running at http://localhost:${port}/`);
});
