<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Portfolio Contact</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f5; color: #18181b; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <h2 style="margin-top: 0; color: #ff6b00;">New Contact Form Submission</h2>
        <p>You have received a new message from your portfolio website.</p>
        
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #e4e4e7; width: 100px; font-weight: bold; color: #71717a;">Name:</td>
                <td style="padding: 10px; border-bottom: 1px solid #e4e4e7;">{{ $contactData['name'] }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #e4e4e7; font-weight: bold; color: #71717a;">Email:</td>
                <td style="padding: 10px; border-bottom: 1px solid #e4e4e7;">
                    <a href="mailto:{{ $contactData['email'] }}" style="color: #2563eb;">{{ $contactData['email'] }}</a>
                </td>
            </tr>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #e4e4e7; font-weight: bold; color: #71717a;">Subject:</td>
                <td style="padding: 10px; border-bottom: 1px solid #e4e4e7;">{{ $contactData['subject'] ?? '(No Subject)' }}</td>
            </tr>
        </table>

        <div style="margin-top: 30px;">
            <h4 style="margin-bottom: 10px; color: #71717a; text-transform: uppercase; font-size: 12px; letter-spacing: 1px;">Message:</h4>
            <div style="background: #f4f4f5; padding: 15px; border-radius: 6px; white-space: pre-wrap; line-height: 1.6;">{{ $contactData['message'] }}</div>
        </div>
        
        <div style="margin-top: 40px; font-size: 12px; color: #a1a1aa; text-align: center;">
            <p>This email was automatically generated from your portfolio contact form.</p>
        </div>
    </div>
</body>
</html>
