<head>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<div style="font-family: 'Poppins', sans-serif!important; color: #333;">

    <div style="border-bottom: 10px solid #155edb; padding:0; margin:0;"></div>

    <h2 style="font-family: 'Poppins', sans-serif!important; color: #155edb;">Hello, {{ $data['first_name'] }} {{ $data['last_name'] }}!</h2>

    <p style="font-family: 'Poppins', sans-serif!important; font-size: 16px; line-height: 1.5;">
        Thank you for reaching out to <strong>TABH Care</strong>. We have received your message and one of our team members will contact you shortly to assist you.
    </p>

    <p style="font-family: 'Poppins', sans-serif!important; font-size: 16px; font-weight:600;">Your Submitted Details:</p>
    <ul style="font-family: 'Poppins', sans-serif!important; font-size: 16px; line-height: 1.5; padding-left: 20px;">
        <li>Email: {{ $data['email'] }}</li>
        <li>Phone: {{ $data['phone'] }}</li>
        <li>Address: {{ $data['address'] }}</li>
    </ul>

    <p style="font-family: 'Poppins', sans-serif!important; font-size: 16px; font-weight:600;">Your Message:</p>
    <p style="font-family: 'Poppins', sans-serif!important; font-size: 16px; line-height: 1.5;">{{ $data['message'] }}</p>

    <p style="font-family: 'Poppins', sans-serif!important; font-size: 16px; line-height: 1.5;">
        We appreciate your trust in us and look forward to assisting you.
    </p>

    <div style="border-bottom: 10px solid #155edb; padding:0; margin:0;"></div>
</div>