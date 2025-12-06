<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Password Reset</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 30px;">
    <div style="max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px;">
        <h2 style="color: #061936;">Hello {{ $user->name ?? 'User' }},</h2>
        <p>You recently requested to reset your password for your account. Click the button below to reset it.</p>
        <p style="text-align: center;">
            <a href="{{ $link }}" style="background-color: #0d6efd; color: white; padding: 12px 20px; text-decoration: none; border-radius: 5px;">
                Reset Password
            </a>
        </p>
        <p>If you did not request a password reset, please ignore this email or contact support.</p>
        <p>Thanks,<br>Your Support Team</p>
    </div>
</body>
</html>
