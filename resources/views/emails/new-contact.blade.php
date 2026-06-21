<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Portfolio Contact</title>
    <!--[if mso]>
    <style type="text/css">
        body, table, td, h1, h2, p, div {font-family: Arial, sans-serif !important;}
    </style>
    <![endif]-->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Jaro:opsz@6..72&family=Space+Grotesk:wght@400;700&display=swap');
        
        body {
            font-family: 'Space Grotesk', Arial, sans-serif !important;
            background-color: #09090b;
            color: #fafafa;
            padding: 40px 20px;
            margin: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #18181b;
            border: 1px solid #27272a;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #27272a;
        }
        .logo {
            font-family: 'Jaro', sans-serif !important;
            font-size: 36px;
            color: #ff6b00;
            letter-spacing: 1px;
            margin: 0;
            text-transform: uppercase;
        }
        .title {
            margin-top: 15px;
            margin-bottom: 5px;
            font-size: 20px;
            font-weight: 700;
            color: #fafafa;
        }
        .subtitle {
            margin: 0;
            color: #a1a1aa;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        td {
            padding: 12px 10px;
            border-bottom: 1px solid #27272a;
            font-size: 15px;
            font-family: 'Space Grotesk', Arial, sans-serif !important;
        }
        .label {
            width: 100px;
            font-weight: 700;
            color: #a1a1aa;
        }
        .value {
            color: #fafafa;
        }
        a {
            color: #ff6b00;
            text-decoration: none;
        }
        .message-section {
            margin-top: 35px;
        }
        .message-label {
            margin-bottom: 12px;
            color: #a1a1aa;
            text-transform: uppercase;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.5px;
            font-family: 'Space Grotesk', Arial, sans-serif !important;
        }
        .message-content {
            background: #09090b;
            border: 1px solid #27272a;
            padding: 20px;
            border-radius: 8px;
            white-space: pre-wrap;
            line-height: 1.7;
            font-size: 15px;
            color: #e4e4e7;
            font-family: 'Space Grotesk', Arial, sans-serif !important;
        }
        .footer {
            margin-top: 40px;
            font-size: 12px;
            color: #71717a;
            text-align: center;
            font-family: 'Space Grotesk', Arial, sans-serif !important;
        }
    </style>
</head>
<body style="font-family: 'Space Grotesk', Arial, sans-serif; background-color: #09090b; color: #fafafa; padding: 40px 20px; margin: 0;">
    <div class="container" style="max-width: 600px; margin: 0 auto; background: #18181b; border: 1px solid #27272a; padding: 40px; border-radius: 12px; font-family: 'Space Grotesk', Arial, sans-serif;">
        <div class="header" style="text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #27272a;">
            <h1 class="logo" style="font-family: 'Jaro', sans-serif; font-size: 36px; color: #ff6b00; margin: 0; text-transform: uppercase;">IX Media</h1>
            <h2 class="title" style="margin-top: 15px; margin-bottom: 5px; font-size: 20px; font-weight: 700; color: #fafafa; font-family: 'Space Grotesk', Arial, sans-serif;">New Inquiry Received</h2>
            <p class="subtitle" style="margin: 0; color: #a1a1aa; font-size: 14px; font-family: 'Space Grotesk', Arial, sans-serif;">A visitor has sent a message via your portfolio contact form.</p>
        </div>
        
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <tr>
                <td class="label" style="width: 100px; font-weight: 700; color: #a1a1aa; padding: 12px 10px; border-bottom: 1px solid #27272a; font-family: 'Space Grotesk', Arial, sans-serif;">Name</td>
                <td class="value" style="color: #fafafa; padding: 12px 10px; border-bottom: 1px solid #27272a; font-family: 'Space Grotesk', Arial, sans-serif;">{{ $contactData['name'] }}</td>
            </tr>
            <tr>
                <td class="label" style="width: 100px; font-weight: 700; color: #a1a1aa; padding: 12px 10px; border-bottom: 1px solid #27272a; font-family: 'Space Grotesk', Arial, sans-serif;">Email</td>
                <td class="value" style="color: #fafafa; padding: 12px 10px; border-bottom: 1px solid #27272a; font-family: 'Space Grotesk', Arial, sans-serif;">
                    <a href="mailto:{{ $contactData['email'] }}" style="color: #ff6b00; text-decoration: none;">{{ $contactData['email'] }}</a>
                </td>
            </tr>
            <tr>
                <td class="label" style="width: 100px; font-weight: 700; color: #a1a1aa; padding: 12px 10px; border-bottom: 1px solid #27272a; font-family: 'Space Grotesk', Arial, sans-serif;">Subject</td>
                <td class="value" style="color: #fafafa; padding: 12px 10px; border-bottom: 1px solid #27272a; font-family: 'Space Grotesk', Arial, sans-serif;">{{ $contactData['subject'] ?? '(No Subject)' }}</td>
            </tr>
        </table>

        <div class="message-section" style="margin-top: 35px;">
            <div class="message-label" style="margin-bottom: 12px; color: #a1a1aa; text-transform: uppercase; font-size: 12px; font-weight: 700; letter-spacing: 1.5px; font-family: 'Space Grotesk', Arial, sans-serif;">Message Content</div>
            <div class="message-content" style="background: #09090b; border: 1px solid #27272a; padding: 20px; border-radius: 8px; white-space: pre-wrap; line-height: 1.7; font-size: 15px; color: #e4e4e7; font-family: 'Space Grotesk', Arial, sans-serif;">{{ $contactData['message'] }}</div>
        </div>
        
        <div class="footer" style="margin-top: 40px; font-size: 12px; color: #71717a; text-align: center; font-family: 'Space Grotesk', Arial, sans-serif;">
            <p>&copy; {{ date('Y') }} IX Media Portfolio. Automated Alert.</p>
        </div>
    </div>
</body>
</html>
