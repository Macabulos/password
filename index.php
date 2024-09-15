

<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['btnsubmit'])) {
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/responsive.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
</head>
<body>   
    <div class="container">
        <img src="./img/received_1165837761442678.jpeg" alt="profile">
            <span>i'</span>
            <span>m&nbsp;</span>
            <span>J</span>
            <span>o</span>
            <span>h</span>
            <span>n&nbsp;</span>
            <span>L</span>
            <span>e</span>
            <span>s</span>
            <span>t</span>
            <span>e</span>
            <span>r&nbsp;</span>
            <span>M.&nbsp;</span>
            <span>M</span>
            <span>a</span>
            <span>c</span>
            <span>a</span>
            <span>b</span>
            <span>u</span>
            <span>l</span>
            <span>o</span>
            <span>s</span>
        <h2>An BSIT Student at Asian Development College. </h2>
        <p>your Personal Web Designer.</p>
        <div>
            <form method="POST" action="">
                <button type="submit" name="btnsubmit">Get Started</button>       
            </form>    
        </div>
    </div>
</body>
</html>
