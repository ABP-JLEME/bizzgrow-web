<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Welcome - BizzGrow</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-around; /* Space out content vertically */
            align-items: center; /* Center horizontally */
            background-color: #0F172A; /* Using colorbackground from Flutter ref */
            color: white;
            padding: 20px; /* Overall padding for content */
            box-sizing: border-box; /* Include padding in element's total width and height */
            overflow-y: auto; /* Allow scrolling if content overflows */
        }

        .welcome-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            max-width: 600px; /* Limit content width for readability */
            padding: 0 16px; /* Horizontal padding for text */
        }

        .welcome-content h1 {
            font-size: 32px;
            font-weight: 800; /* InterExtraBold */
            color: #FFFFFF; /* fontwhite from Flutter ref */
            margin-bottom: 8px; /* Consistent spacing */
            line-height: 1.3;
        }

        .welcome-content p {
            font-size: 18px;
            font-weight: 400; /* InterRegular */
            color: #FFFFFF; /* fontwhite from Flutter ref */
            margin-top: 0; /* Remove default paragraph top margin */
            line-height: 1.5;
        }

        .welcome-image {
            margin-top: 40px; /* Space above image */
            margin-bottom: 40px; /* Space below image */
            max-width: 100%; /* Ensure image is responsive */
            height: auto;
            max-height: 400px; /* Limit image height like in Flutter */
            object-fit: contain; /* Ensure image fits without cropping */
        }

        .button-group {
            width: 100%;
            max-width: 350px; /* Max width for buttons */
            display: flex;
            flex-direction: column;
            gap: 10px; /* Space between buttons */
            padding: 0 16px; /* Horizontal padding for buttons */
            box-sizing: border-box;
        }

        .btn {
            padding: 13.5px 20px;
            border: none;
            border-radius: 4px; /* Consistent with onboarding buttons */
            font-size: 16px;
            font-weight: 500; /* InterMedium */
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
            width: 100%;
            box-sizing: border-box;
            text-decoration: none; /* For anchor tags */
            display: block; /* Ensure it takes full width */
            text-align: center;
        }

        .btn-register {
            background-color: #1E40AF; /* blue from Flutter ref */
            color: #FFFFFF; /* white from Flutter ref */
        }

        .btn-register:hover {
            background-color: #1a368b; /* Darker blue on hover */
        }

        .btn-login {
            background-color: transparent;
            color: #1E40AF; /* accentBlueColor from Flutter ref */
            border: 1px solid #1E40AF; /* Border for transparent button */
        }

        .btn-login:hover {
            background-color: rgba(30, 64, 175, 0.1); /* Slight blue background on hover */
            color: #1E40AF; /* Keep color */
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .welcome-content h1 {
                font-size: 28px;
            }
            .welcome-content p {
                font-size: 16px;
            }
            .welcome-image {
                max-height: 300px;
            }
            .button-group {
                max-width: 300px;
            }
        }

        @media (max-width: 480px) {
            .welcome-content h1 {
                font-size: 24px;
            }
            .welcome-content p {
                font-size: 15px;
            }
            .welcome-image {
                max-height: 250px;
            }
            body {
                padding: 15px;
            }
        }
    </style>
</head>

<body>

    <div class="welcome-content">
        <h1>Selamat Datang di BizzGrow!</h1>
        <p>Saatnya membawa bisnismu ke level berikutnya. Kelola keuangan dengan mudah, cepat, dan cerdas — semua cukup dalam satu aplikasi.</p>
    </div>

    <img src="{{ asset('images/welcome.png') }}" alt="Welcome Image" class="welcome-image">

    <div class="button-group">
        <a href="{{ route('register') }}" class="btn btn-register">Register</a>
        <a href="{{ route('login') }}" class="btn btn-login">Masuk</a>
    </div>

</body>

</html>