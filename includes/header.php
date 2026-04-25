<!DOCTYPE html>
<html>
<head>
    <title>Quad Solutions</title>

   
 <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- GLOBAL CSS -->
   <link rel="stylesheet" href="assets/css/style.css">
    <style>
    body{margin:0;font-family:Poppins;}

    /* LOADER */
    #loader{
        position:fixed;
        width:100%;
        height:100vh;
        background:#0f2027;
        display:flex;
        justify-content:center;
        align-items:center;
        z-index:9999;
        color:white;
        flex-direction:column;
    }

    .spinner{
        width:50px;
        height:50px;
        border:5px solid #fff;
        border-top:5px solid #00c6ff;
        border-radius:50%;
        animation:spin 1s linear infinite;
    }

    @keyframes spin{
        100%{transform:rotate(360deg);}
    }
    </style>

    <script>
        window.onload = function(){
            document.getElementById("loader").style.display="none";
        }
    </script>
</head>
<body>

<div id="loader">
    <div class="spinner"></div>
    <p>Loading Quad Solutions...</p>
</div>