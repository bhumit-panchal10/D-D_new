<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Thank You</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CDN (optional) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .thankyou-container {
            max-width: 600px;
            margin: 80px auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .thankyou-container img {
            width: 100px;
            margin-bottom: 20px;
        }

        .thankyou-container h1 {
            color: #28a745;
            font-size: 2.5rem;
        }

        .thankyou-container p {
            font-size: 1.1rem;
            color: #555;
        }

        .thankyou-container .btn {
            margin-top: 25px;
        }
    </style>
</head>

<body>

    <div class="thankyou-container">
        <img src="{{ asset('assets/images/thank-you.png') }}" alt="Thank You">
        <h1>Thank You!</h1>
        <p>Your signature has been submitted successfully.</p>
        <p>We appreciate your cooperation.</p>
        {{-- <a href="{{ url('/') }}" class="btn btn-primary">Return to Home</a> --}}
    </div>

</body>

</html>
