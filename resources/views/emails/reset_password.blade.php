<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .body-div {
            width: 100%;
            padding: 30px;
            background-color: #c8bcbce1;
        }

        .email-logo {
            height: 50px;
            width: 50px;
        }

        .email-logo-div {
            text-align: center;
            padding: 10px 10px 0px 10px;
        }

        .email-salutation {
            margin-top: 0px;
            padding-left: 10px;
        }

        .email-content-div {
            padding: 0px 20px 0px 20px;
        }

        .email-content {
            text-align: justify;
            text-justify: inter-word;
        }

        .buttonLink {
            display: inline-block;
            background-color: #0b88e1e1;
            color: #ffffff;
            padding: 5px 10px 5px 10px;
            text-decoration: none;
            margin: 20px 0px 20px 0px;
        }

        .buttonLink:hover {
            background-color: #1e6da7e1;
        }

        .email-other-content span {
            display: inline-block;
            margin-bottom: 5px;
        }

        .card {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            transition: 0.3s;
            width: 75%;
            margin: auto;
            padding-top: 50px;
            background-color: #ffffff;
            border-radius: 5px;
            padding: 10px;
        }

        .card:hover {
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
        }

        .container {
            padding: 2px;
        }
    </style>
</head>

<body>
    <div class="body-div">
        <div class="card">
            <div class="email-logo-div">
                <center>
                    <h1>(APP NAME)</h1>
                </center>
                {{-- <img src="{{ asset('img/sample_logo.png') }}" alt="Image" class="email-logo" /> --}}
                {{-- <img src="{{ $message->embed(public_path('img/sample_logo.png')) }}" alt="Image" class="email-logo" /> --}}
            </div>
            <hr>
            <div>
                <center>
                    <h2>RESET PASSWORD 👀</h2>
                </center>
            </div>
            <div class="container">
                <h3 class="email-salutation">Hello {{ $first_name }} {{ $last_name }}</h3>
                <div class="email-content-div">
                    <p class="email-content">
                        Tap the button below to reset your account password. Please desregard this message if you did
                        not
                        request a password reset.
                    </p>

                    <center><a href="{{ $url }}" class="buttonLink">Reset Password</a></center>
                    <div class="email-other-content">
                        <span>
                            If the button above does not work, copy and paste the following link into your
                            browser: <br>
                            <a href="{{ $url }}">{{ $url }}</a>
                        </span>
                        <span>
                            <br>
                            Thank you,<br>
                            -- App Name --
                        </span>
                    </div>
                </div>

            </div>
        </div>

    </div>
</body>

</html>
