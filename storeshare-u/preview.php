<?php 
    // Authenticate if user is logged in
    include ("../auth_session.php");

    if($_SESSION["role"] != "user"){
        header("Location: /Capstone_System/404.php");
        exit();
    }
    $content = $_GET["filepath"];

    

    // Get file extension
    $fileExt = explode('.', $content);    
    $fileExt = strtolower(end($fileExt));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../CSS/style.css" />
    <link rel="stylesheet" href="../CSS/utilities.css" />    
        <iframe src="<?php echo$content?>" frameborder="0"></iframe>    
</head>
<body>
    
</body>
</html>