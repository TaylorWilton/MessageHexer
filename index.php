<?php

require 'src/Hexer.php';
require 'src/DeHexer.php';

$submittedMessage = false;
$submittedImage = false;
$data = false;
$message = "";
$decoded = "";

if (!empty($_FILES)) {
    $decoded = DeHexer::parseImage($_FILES['file']['tmp_name']);
    $submittedImage = true;
}

if (!empty($_POST)) {
    $message = htmlspecialchars($_POST['message']);
    $image = Hexer::CreateImage($message);

    /*
    Save image to buffer, rather than to disk
    */
    ob_start();
    imagepng($image);
    $contents = ob_get_contents();
    ob_end_clean();
    $data = base64_encode($contents);
    $submittedMessage = true;
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
        .result,input[type=file]{display:block}button,hr{border:none}body{font-size:20px;line-height:1.5;font-family:"Lucida Sans Typewriter","Lucida Console",monaco,"Bitstream Vera Sans Mono",monospace}hr{padding:0;border-top:medium double #333;color:#333;text-align:center;height:0}h1,h2,p{margin:0}.container{max-width:960px;margin:0 auto;padding:24px}.form-area{margin-top:48px}.result{height:inherit;image-rendering:pixelated;width:100%}textarea{height:80px;width:100%;max-width:100%}button{width:80px;padding:12px;margin-top:12px}
    </style>
</head>
<body>
    <div class="container">
        <h1>Message Hexer</h1>
        <p>Encode & Decode Messages with Hexadecimals</p>
        <hr>
        <?php if ($submittedMessage): ?>
            <h2>Encoded Message:</h2>
            <img src="data:image/png;base64,<?= $data; ?>" alt="message" class="result"/>
            <small><strong>Original Text:</strong><?= $message; ?></small>
        <?php elseif ($submittedImage): ?>
            <h2>Decoded Message:</h2>
            <p><?= $decoded; ?></p>
        <?php endif; ?>
        <div class="form-area">
            <form method="post">
                <h2>Encode a Message:</h2>
                <label for="message"> Message:</label>
                <textarea id="message" name="message" required></textarea>
                <button type="submit"> Encode</button>
            </form>
            <form method="post" enctype="multipart/form-data">
                <h2>Decode an Image:</h2>
                <label for="file"> Image to Decode: </label>
                <input type="file" id="file" name="file" required>
                <button type="submit"> Decode</button>
            </form>
        </div>
    </div>
</body>
</html>