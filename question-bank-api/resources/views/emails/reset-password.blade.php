<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: white;
                     border-radius: 12px; padding: 40px; }
        .btn { display: inline-block; padding: 14px 28px;
               background-color: #5a8dee; color: white; text-decoration: none;
               border-radius: 8px; font-size: 16px; margin-top: 20px; }
        .footer { margin-top: 30px; font-size: 12px; color: #999; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Bonjour {{ $userName }},</h2>
        <p>Vous avez demandé la réinitialisation de votre mot de passe.</p>
        <p>Cliquez sur le bouton ci-dessous pour choisir un nouveau mot de passe.
           Ce lien est valable <strong>15 minutes</strong>.</p>

        <a href="{{ $resetUrl }}" class="btn">Réinitialiser mon mot de passe</a>

        <p style="margin-top: 20px;">Si vous n'avez pas fait cette demande,
           ignorez cet email.</p>

        <div class="footer">
            <p>Ce lien expirera dans 15 minutes.</p>
        </div>
    </div>
</body>
</html>