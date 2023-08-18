<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Verification</title>
</head>
<body>
    <h2>Account Verification</h2>
    <p>Hello,</p>
    <p>Thank you for registering an account with us. To complete your registration, please click the link below to verify your email address:</p>
    <p>
        <a href="{{ $verificationUrl }}">Verify Email Address</a>
    </p>
    <p>If you did not create an account, you can safely ignore this email.</p>
    <p>Best regards,</p>
    <p>Your Application Team</p>
</body>
</html>
