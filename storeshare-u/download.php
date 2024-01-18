<?php 
    // Authenticate if user is logged in
    include ("../auth_session.php");

    
    if($_SESSION["role"] != "user"){
        header("Location: /Capstone_System/404.php");
        exit();
    }

    if(!isset($_GET["file"])){
        header("Location: root.php");
        exit();
    }

    if(!empty($_GET["file"])){
        $filepath = $_GET["file"];
        $fileDirectory = "../storeshare/folders/{$filepath}";

        if(!empty($filepath) && file_exists($fileDirectory)){
            // Define headers
            header("Cache-control: public");
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=".basename($filepath)."");
            header("Content-Type: application");
            header("Content-Transfer-Encoding: binary");
            ob_clean();
            flush();
            readfile($fileDirectory);
            exit;
        }
        else{
            header("Location: root.php");
            exit();
        }
    }
     

?>