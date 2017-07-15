<?php

require 'src/Hexer.php';
require 'src/DeHexer.php';
require 'src/Enums.php';

$state = false;
$data = false;
$message = "";
$decoded = "";

if (!empty($_FILES['file']) && !empty($_POST['decodeChannel'])) {
    $file = $_FILES['file']['tmp_name'];
    $channel = $_POST['decodeChannel'];
    if (exif_imagetype($file) === IMAGETYPE_PNG && Channel::isValidChannel($channel)) {
        $decoded = DeHexer::parseImage($file, $channel);
        $state = Result::SubmittedImage;
    } else {
        $state = Result::ErrorWrongFileType;
    }
}

if (!empty($_POST['message']) && !empty($_POST['encodeChannel'])) {
    $message = htmlspecialchars($_POST['message']);
    $channel = $_POST['encodeChannel'];

    if (!Channel::isValidChannel($channel)) {
        throw new TypeError("Not a valid Color Channel");
    }

    $image = Hexer::CreateImage($message, $channel);

    /*
    Save image to buffer, rather than to disk
    */
    ob_start();
    imagepng($image);
    $contents = ob_get_contents();
    ob_end_clean();
    $data = base64_encode($contents);
    $state = Result::SubmittedMessage;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Message Hexer</title>
    <style>
        .result, input[type=file] {
            display: block
        }

        .error {
            color: rgb(192, 57, 43)
        }

        button, hr {
            border: none
        }

        body {
            font-size: 20px;
            line-height: 1.5;
            font-family: "Lucida Sans Typewriter", "Lucida Console", monaco, "Bitstream Vera Sans Mono", monospace
        }

        hr {
            padding: 0;
            border-top: medium double #333;
            color: #333;
            text-align: center;
            height: 0
        }

        h1, h2, p {
            margin: 0
        }

        .container {
            max-width: 960px;
            margin: 0 auto;
            padding: 24px
        }

        .form-area {
            margin-top: 48px
        }

        .result {
            height: inherit;
            image-rendering: pixelated;
            width: 100%
        }

        textarea {
            height: 80px;
            width: 100%;
            max-width: 100%
        }

        button {
            width: 80px;
            padding: 12px;
            margin-top: 12px
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Message Hexer</h1>
        <p>Encode & Decode Messages with Hexadecimals</p>
        <hr>
        <?php if ($state == Result::SubmittedMessage): ?>
            <h2>Encoded Message:</h2>
            <img src="data:image/png;base64,<?= $data; ?>" alt="message" class="result"/>
            <small><strong>Original Text:</strong><?= $message; ?></small>
        <?php elseif ($state === Result::SubmittedImage): ?>
            <h2>Decoded Message:</h2>
            <p><?= $decoded; ?></p>
        <?php elseif ($state === Result::ErrorWrongFileType): ?>
            <h2>Error:</h2>
            <p class="error">Please submit a .png image</p>
        <?php endif; ?>
        <div class="form-area">
            <form method="post">
                <h2>Encode a Message:</h2>
                <label for="message"> Message:</label>
                <textarea id="message" name="message" required></textarea>
                <fieldset>
                    <legend>Color Channel</legend>
                    <input value="red" id="radioEncodeRed" name="encodeChannel" type="radio"><label
                            for="radioEncodeRed">Red</label>
                    <input value="green" id="radioEncodeGreen" name="encodeChannel" type="radio"><label
                            for="radioEncodeGreen">Green</label>
                    <input value="blue" id="radioEncodeBlue" name="encodeChannel" type="radio"><label
                            for="radioEncodeBlue">Blue</label>
                    <input value="all" id="radioEncodeAll" name="encodeChannel" type="radio" checked><label
                            for="radioEncodeAll">All</label>
                </fieldset>
                <button type="submit"> Encode</button>
            </form>
            <form method="post" enctype="multipart/form-data">
                <h2>Decode an Image:</h2>
                <label for="file"> Image to Decode: (must be .png)</label>
                <input type="file" id="file" name="file" required>
                <fieldset>
                    <legend>Color Channel</legend>
                    <input value="red" id="radioDecodeRed" name="decodeChannel" type="radio"><label
                            for="radioDecodeRed">Red</label>
                    <input value="green" id="radioDecodeGreen" name="decodeChannel" type="radio"><label
                            for="radioDecodeGreen">Green</label>
                    <input value="blue" id="radioDecodeBlue" name="decodeChannel" type="radio"><label
                            for="radioDecodeBlue">Blue</label>
                    <input value="all" id="radioDecodeAll" name="decodeChannel" type="radio" checked><label
                            for="radioDecodeAll">All</label>
                </fieldset>
                <button type="submit"> Decode</button>
            </form>
        </div>
    </div>
</body>
</html>