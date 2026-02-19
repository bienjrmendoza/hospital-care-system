<head>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<div style="font-family: 'Poppins', sans-serif!important;">
    <div style="border-bottom: 10px solid #155edb; padding:0; margin:0;"></div>

    <h2 style="font-family: 'Poppins', sans-serif!important; color: #155edb;">New Contact Message</h2>

    <hr style="border: 1px solid #d1d1d1;" />

    <p style="font-family: 'Poppins', sans-serif!important; font-size: 16px; line-height: 1.5;"><strong>Name:</strong> {{ $data['first_name'] }} {{ $data['last_name'] }}</p>
    <p style="font-family: 'Poppins', sans-serif!important; font-size: 16px; line-height: 1.5;"><strong>Email:</strong> {{ $data['email'] }}</p>
    <p style="font-family: 'Poppins', sans-serif!important; font-size: 16px; line-height: 1.5;"><strong>Phone:</strong> {{ $data['phone'] }}</p>
    <p style="font-family: 'Poppins', sans-serif!important; font-size: 16px; line-height: 1.5;"><strong>Address:</strong> {{ $data['address'] }}</p>
    <p style="font-family: 'Poppins', sans-serif!important; font-size: 16px; line-height: 1.5;"><strong>Message:</strong></p>
    <p style="font-family: 'Poppins', sans-serif!important; font-size: 16px; line-height: 1.5;">{{ $data['message'] }}</p>

    <div style="border-bottom: 10px solid #155edb; padding:0; margin:0;"></div>
</div>