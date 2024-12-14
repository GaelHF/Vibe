<?php
    session_start();
    include 'bdd.php';
    if(isset($_SESSION['auth']))
    {
        header('Location: list.php');
    }
    if(isset($_POST['connexion']))
    {
        if(!empty($_POST['pseudo']) && !empty($_POST['mdp']))
        {
            if($_POST['pseudo'] == $vibe_username && $_POST['mdp'] == $vibe_password)
            {
                $_SESSION['pseudo'] = $_POST['pseudo'];
                $_SESSION['auth'] = True;
                header('Location: list.php');
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @font-face {
            font-family: customFont;
            src: url('assets/customFont.otf');
        }
    </style>
    <link rel="icon" type="image/x-icon" href="assets/icon.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>VIBE - Connexion</title>
</head>
<body style="background-color: rgb(42, 6, 50);">
    <div class="text-center text-white">
        <image width="300" height="300" src="assets/VibeSquare.png">
        <div style="margin: 5%;">
            <form method="POST" action="" align="center">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-person-fill"></i></span>
                    <input type="text" class="form-control" name="pseudo" placeholder="Nom d'utilisateur" aria-label="Nom d'utilisateur" aria-describedby="basic-addon1">
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-lock-fill"></i></span>
                    <input id="mdp" type="password" name="mdp" class="form-control" placeholder="Mot de passe" aria-label="Mot de passe" aria-describedby="basic-addon1">
                    <button id="mdp-label" class="btn btn-secondary" type="button" onclick="showPassword()"><i class="bi bi-eye-fill"></i></button>
                </div>
                <input class="btn btn-primary" type="submit" name="connexion" value="Se connecter">
        </form>
        </div>
    </div>
    <script>
        function showPassword()
        {
            var elem = document.getElementById("mdp");
            var elem_label = document.getElementById("mdp-label");

            if(elem.type == "password")
            {
                elem.type = "text";
                elem_label.innerHTML = '<i class="bi bi-eye-slash-fill"></i>';
            }
            else if(elem.type == "text")
            {
                elem.type = "password";
                elem_label.innerHTML = '<i class="bi bi-eye-fill"></i>';
            }
        }
    </script>
</body>
</html>