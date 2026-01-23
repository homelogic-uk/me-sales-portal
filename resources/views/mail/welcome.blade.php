<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #444444;
            line-height: 1.6;
            background-color: #f7f9fc;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            width: 100%;
            background-color: #f7f9fc;
            padding: 30px 0;
        }

        .container {
            max-width: 580px;
            margin: 0 auto;
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand {
            font-size: 28px;
            font-weight: bold;
            color: #2d3748;
            text-decoration: none;
        }

        h1 {
            color: #1a202c;
            font-size: 24px;
            margin-top: 0;
            text-align: center;
        }

        p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .contract-card {
            background-color: #f0f7ff;
            border: 1px solid #bee3f8;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }

        .contract-card p {
            margin: 0;
            font-size: 14px;
            color: #2c5282;
        }

        .contact-footer {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin-top: 30px;
        }

        .phone-number {
            font-size: 18px;
            color: #2d3748;
            font-weight: bold;
            text-decoration: none;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #a0aec0;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <div class="brand">MyEnergy</div>
            </div>

            <h1>Welcome!</h1>

            <p>We’re delighted to have you with us. Whether you’re looking to improve your home or simply exploring our range of products, we’re here to make the process as smooth as possible.</p>

            <p>For your records, we have attached a copy of your contract to this email. Please take a moment to review it and save a copy for your files.</p>

            <p>Our team is already working on the next steps for you. If you have any questions at all, we’d love to hear from you.</p>

            <div class="contact-footer">
                <p style="margin-bottom: 5px; font-size: 14px;">Need help? Give us a call:</p>
                <a href="tel:08001700680" class="phone-number">0800 1700 680</a>
                <p style="margin-top: 5px; font-size: 13px; color: #718096;">Or simply reply to this email</p>
            </div>

            <p style="margin-top: 30px;">Best regards,<br>
                <strong>The MyEnergy Team</strong>
            </p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} MyEnergy. All rights reserved.<br>
            If you didn't expect this email, please let us know.
        </div>
    </div>
</body>