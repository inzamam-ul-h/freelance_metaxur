<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slot Booking Confirmation</title>
</head>
<body>
    <h3>Dear {{ $name }},</h3>
    <p>Your slot has been booked successfully!</p>
    <p><strong>Date:</strong> {{ $date }}</p>
    <p><strong>Time:</strong> {{ $start_time }} - {{ $end_time }}</p>
    <p>Thank you for using our service!</p>

</body>
</html>
