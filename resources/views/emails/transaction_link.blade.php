 <!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Transaction Access</title>
</head>
<body>
    <p>Hello,</p>

    <p>You have been invited to access a transaction on Eskro.</p>

    <p>
        Click the link below to view and verify the transaction:<br>
        <a href="{{ $link }}" target="_blank">{{ $link }}</a>
    </p>

    <p>Your One-Time Password (OTP) is: <strong>{{ $otp }}</strong></p>

    <p>If you didn’t expect this email, you can safely ignore it.</p>

    <br>
    <p>Regards,<br>Eskro Transaction System</p>
</body>
</html>

