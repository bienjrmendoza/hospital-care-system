<head>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<div style="font-family: 'Poppins', sans-serif!important; max-width:600px; margin:0 auto; border:1px solid #e0e0e0; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.1); color:#333;">

    <div style="background-color:#155edb; text-align:center; padding:20px;">
        <a href="https://tabhcare.online/" target="_blank"><img src="https://tabhcare.online/assets/images/white-logo.png" alt="TabhCare" style="max-width:250px; height:auto;"></a>
    </div>

    <div style="padding:20px; text-align:left;">
        <h2 style="color:#155edb; font-size:24px; margin:0;">Hello, {{ $data['first_name'] }} {{ $data['last_name'] }}!</h2>
        <p style="font-size:16px; line-height:1.5; margin-top:10px;">
            Thank you for reaching out to <strong>TABH Care</strong>. We have received your message and one of our team members will contact you shortly to assist you.
        </p>
    </div>

    <div style="padding:0 20px 20px 20px; background-color:#f9f9f9; border-top:1px solid #ddd;">
        <p style="font-size:16px; font-weight:600; margin:10px 0;">Your Submitted Details:</p>
        <ul style="font-size:16px; line-height:1.5; padding-left:20px; margin:0;">
            <li>Email: <a href="mailto:{{ $data['email'] }}" style="color:#155edb; text-decoration:none;">{{ $data['email'] }}</a></li>
            <li>Phone: <a href="tel:{{ $data['phone'] }}" style="color:#155edb; text-decoration:none;">{{ $data['phone'] }}</a></li>
            <li>Address: {{ $data['address'] }}</li>
        </ul>

        <p style="font-size:16px; font-weight:600; margin:15px 0 5px 0;">Your Message:</p>
        <p style="font-size:16px; line-height:1.5; background-color:#ffffff; padding:10px; border-radius:4px; border:1px solid #ddd;">
            {{ $data['message'] }}
        </p>

        <p style="font-size:16px; line-height:1.5; margin-top:15px;">
            We appreciate your trust in us and look forward to assisting you.
        </p>
    </div>

    <div style="background-color:#155edb; height:10px;"></div>
</div>