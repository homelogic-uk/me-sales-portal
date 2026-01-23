<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #444444;
            line-height: 1.6;
            background-color: #f7f9fc;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: none;
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

        .logo {
            max-width: 200px;
            height: auto;
        }

        h1 {
            color: #1a202c;
            font-size: 24px;
            margin-top: 20px;
            text-align: center;
        }

        p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .contact-footer {
            background-color: #f0f7ff;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin-top: 30px;
            border: 1px solid #d1e7ff;
        }

        .phone-number {
            font-size: 18px;
            color: #007bff;
            font-weight: bold;
            text-decoration: none;
        }

        .team-name {
            color: #ee3124;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #a0aec0;
            margin-top: 30px;
        }

        /* Mobile Responsiveness */
        @media only screen and (max-width: 600px) {
            .container {
                width: 100% !important;
                padding: 20px !important;
                border-radius: 0 !important;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <img src="data:image/png;base64,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" alt="MyEnergy" class="logo">
            </div>

            <h1>Welcome!</h1>

            <p>We’re delighted to have you with us. Whether you’re looking to improve your home or simply exploring our range of products, we’re here to make the process as smooth as possible.</p>

            <p>For your records, we have attached a copy of your contract to this email. Please take a moment to review it and save a copy for your files.</p>

            <p>Our team is already working on the next steps for you. If you have any questions at all, we’d love to hear from you.</p>

            <div class="contact-footer">
                <p style="margin-bottom: 5px; font-size: 14px; color: #444444;">Need help? Give us a call:</p>
                <a href="tel:08001700680" class="phone-number">0800 1700 680</a>
                <p style="margin-top: 5px; font-size: 13px; color: #718096;">Or simply reply to this email</p>
            </div>

            <p style="margin-top: 30px;">Best regards,<br>
                <strong class="team-name">The MyEnergy Team</strong>
            </p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} MyEnergy. All rights reserved.<br>
            If you didn't expect this email, please let us know.
        </div>
    </div>
</body>

</html>