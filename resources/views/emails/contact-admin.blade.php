<head>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<div style="font-family: 'Poppins', sans-serif!important; max-width:600px; margin:0 auto; border:1px solid #e0e0e0; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.1);">

    <div style="background-color:#155edb; text-align:center; padding:20px;">
        <a href="https://tabhcare.online/" target="_blank"><img src="https://tabhcare.online/assets/images/white-logo.png" alt="TabhCare" style="max-width:250px; height:auto;"></a>
    </div>
    <div style="padding:20px; text-align:center;">
        <h2 style="color:#155edb; margin:0; font-size:24px;">New Contact Message</h2>
    </div>

    <hr style="border:none; border-top:1px solid #d1d1d1; margin:0 20px;">

    <div style="padding:20px; background-color:#f9f9f9;">
        <p style="font-size:16px; line-height:1.5;"><strong>Name:</strong> {{ $data['first_name'] }} {{ $data['last_name'] }}</p>
        <p style="font-size:16px; line-height:1.5;"><strong>Email:</strong> <a href="mailto:{{ $data['email'] }}" style="color:#155edb; text-decoration:none;">{{ $data['email'] }}</a></p>
        <p style="font-size:16px; line-height:1.5;"><strong>Phone:</strong> <a href="tel:{{ $data['phone'] }}" style="color:#155edb; text-decoration:none;">{{ $data['phone'] }}</a></p>
        <p style="font-size:16px; line-height:1.5;"><strong>Address:</strong> {{ $data['address'] }}</p>
        <p style="font-size:16px; line-height:1.5;"><strong>Message:</strong></p>
        <p style="font-size:16px; line-height:1.5; background-color:#ffffff; padding:10px; border-radius:4px; border:1px solid #ddd;">{{ $data['message'] }}</p>
    </div>

    <div style="background-color:#155edb; height:10px;"></div>
</div>