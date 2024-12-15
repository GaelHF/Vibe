<?php
    session_start();
    include 'bdd.php';
    if($_SESSION['pseudo'] !== "GaelHF" || !$_SESSION['auth'])
    {
        header('Location: list.php');
    }

    if(isset($_POST['add_music']))
    {
        if(!empty($_POST['title']) && !empty($_POST['author']) && !empty($_POST['time']) && !empty($_POST['path']) && !empty($_POST['spacename']) && !empty($_POST['url_yt']) && !empty($_POST['url_spotify']))
        {
            $addNewMusic = $bdd->prepare("INSERT INTO songs (title, author, time, path, spacename, url_yt, url_sfy) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $addNewMusic->execute(array($_POST['title'], $_POST['author'], $_POST['time'], $_POST['path'], $_POST['spacename'], $_POST['url_yt'], $_POST['url_sfy']));
        }
    }

    if(isset($_POST['delete']))
    {
        $getId = $_POST['song_id'];
        $deleteMusic = $bdd->prepare("DELETE FROM songs WHERE id = ?");
        $deleteMusic->execute(array($getId));
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

        .clipyt {
            cursor: default;
        }

        .clipyt:hover {
            cursor: pointer;
            color: red;
        }

        .clipsfy {
            cursor: default;
        }

        .clipsfy:hover {
            cursor: pointer;
            color: lime;
        }
    </style>
    <link rel="icon" type="image/x-icon" href="assets/icon.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>VIBE - Gestionnaire</title>
</head>
<body style="background-color: rgb(42, 6, 50);">
    <div class="text-center text-white">
        <image width="300" height="300" src="assets/VibeSquare.png">
        <div style="margin: 5%;" class="text-center">
                <button type="button" class="btn btn-info" onclick="liste()">Liste</button>
        </div>
        <section id="addMusic">
            <div style="margin: 5%;">
                <h1>Ajouter une nouvelle musique</h1>
                <form method="POST" action="" align="center">
                    <input type="text" class="form-control" placeholder="Titre" name="title"><br>
                    <input type="text" class="form-control" placeholder="Auteur" name="author"><br>
                    <input type="text" class="form-control" placeholder="Durée (0:00)" name="time"><br>
                    <input type="text" class="form-control" placeholder="Chemin (dossier de la musique)" name="path"><br>
                    <input type="text" class="form-control" placeholder="Code ID (ma-musique)" name="spacename"><br>
                    <input type="text" class="form-control" placeholder="Lien Clip YouTube" name="url_yt"><br>
                    <input type="text" class="form-control" placeholder="Lien Spotify" name="url_sfy"><br>
                    <input type="submit" class="btn btn-primary" value="Ajouter" name="add_music">
                </form>
            </div>
        </section>
        <section id="allMusics" style="margin:5%;">
            <?php
                $fetchSongs = $bdd->prepare('SELECT * FROM songs');
                $fetchSongs->execute();
                while($song = $fetchSongs->fetch())
                {
                    ?>
                    <div class="card" style="margin: 1%">
                        <div class="card-body d-flex align-items-center">
                            <form method="POST" action="">
                                <input type="hidden" name="song_id" value="<?= $song['id']; ?>">
                                <input type="submit" class="btn btn-danger me-2" name="delete" value="Retirer">
                            </form>
                            <p id="<?= $song['spacename']; ?>-data" class="mb-0 flex-grow-1"><strong><?= $song['title']; ?></strong> | <?= $song['author']; ?> (<?= $song['path']; ?>/<?= $song['spacename']; ?>) [<?= $song['id']; ?>] | <i class="bi bi-youtube clipyt" onclick="load_exrt('<?= $song['yt_url']; ?>')"></i> | <i class="bi bi-spotify clipsfy" onclick="load_exrt('<?= $song['sfy_url']; ?>')"></i></p>
                            <p class="mb-0 flex-grow-2"><?= $song['time']; ?></p>
                        </div>
                    </div>
                    <?php
                }
            ?>
        </section>
    </div>
    <script>
        function load_exrt(link)
        {
            window.open(link);
        }

        function liste()
        {
            window.location.replace('list.php');
        }
		if ( window.history.replaceState ) {
			window.history.replaceState( null, null, window.location.href );
		}
	</script>
</body>
</html>