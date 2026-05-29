<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="margin: 0; padding: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #fcfcfc; color: #222;">

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="padding: 80px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="450" cellspacing="0" cellpadding="0" border="0" style="background: #ffffff;">
                    
                    <tr>
                        <td align="center" style="padding-top: 50px;">
                            <div style="font-size: 11px; letter-spacing: 6px; color: #333; text-transform: uppercase;">Zen Sushi</div>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 60px 40px;">
                            <h1 style="font-weight: 300; font-size: 20px; color: #333; margin: 0 0 40px 0; letter-spacing: 1px;">
                                Gebruik de code hieronder om uw aanvraag te bevestigen.
                            </h1>
                            
                            <div style="font-size: 48px; font-weight: 500; color: #000; letter-spacing: 12px; margin-bottom: 40px;">
                                {{ $captcha }}
                            </div>

                            <p style="font-size: 12px; color: #888; letter-spacing: 1px;">
                                Code is 5 minuten geldig.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-bottom: 50px;"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>