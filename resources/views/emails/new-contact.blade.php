<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Portfolio Contact</title>
    <!-- Google Fonts: Space Grotesk & Jaro -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jaro:opsz@6..72&family=Space+Grotesk:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Space Grotesk', Arial, sans-serif;
            background-color: #09090b; /* zinc-950 */
            color: #fafafa; /* zinc-50 */
            padding: 40px 20px;
            margin: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #18181b; /* zinc-900 */
            border: 1px solid #27272a; /* zinc-800 */
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
            font-family: 'Jaro', sans-serif;
            font-size: 36px;
            color: #ff6b00; /* Theme accent */
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
            color: #a1a1aa; /* zinc-400 */
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
        }
        .message-content {
            background: #09090b;
            border: 1px solid #27272a;
            padding: 20px;
            border-radius: 8px;
            white-space: pre-wrap;
            line-height: 1.7;
            font-size: 15px;
            color: #e4e4e7; /* zinc-200 */
        }
        .footer {
            margin-top: 40px;
            font-size: 12px;
            color: #71717a; /* zinc-500 */
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="logo">IX Media</h1>
            <h2 class="title">New Inquiry Received</h2>
            <p class="subtitle">A visitor has sent a message via your portfolio contact form.</p>
        </div>
        
        <table>
            <tr>
                <td class="label">Name</td>
                <td class="value">{{ $contactData['name'] }}</td>
            </tr>
            <tr>
                <td class="label">Email</td>
                <td class="value">
                    <a href="mailto:{{ $contactData['email'] }}">{{ $contactData['email'] }}</a>
                </td>
            </tr>
            <tr>
                <td class="label">Subject</td>
                <td class="value">{{ $contactData['subject'] ?? '(No Subject)' }}</td>
            </tr>
        </table>

        <div class="message-section">
            <div class="message-label">Message Content</div>
            <div class="message-content">{{ $contactData['message'] }}</div>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} IX Media Portfolio. Automated Alert.</p>
        </div>
    </div>
</body>
</html>
