<!DOCTYPE html>
<html>
<head>
    <title>Welcome to the System</title>
</head>
<body>
    <h2>Hello {{ $user->name }},</h2>

    <p>Your account has been created successfully.</p>

    <p><strong>Login Credentials:</strong></p>
    <ul>
        <li><strong>Email:</strong> {{ $user->email }}</li>
        <li><strong>Password:</strong> {{ $password }}</li>
    </ul>

    <p>You can log in using the following link:</p>
    <p><a href="{{ $loginUrl }}" style="color: blue; font-weight: bold;">Click here to log in</a></p>

    <p>For security reasons, please change your password after logging in.</p>

    <p>Thank you!</p>
</body>
</html>
