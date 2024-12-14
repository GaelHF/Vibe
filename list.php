<?php
    session_start();
    include 'bdd.php';
    if(!isset($_SESSION['auth']))
    {
        header('Location: index.php');
    }
    if(isset($_POST['logout']))
    {
        session_destroy();
        header('Location: index.php');
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
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>VIBE - Liste</title>
</head>
<body style="background-color: rgb(42, 6, 50);">
    <?php
        if($_SESSION['pseudo'] == "GaelHF")
        {
            ?>
            <div style="margin: 5%;" class="text-center">
                <button type="button" class="btn btn-info" onclick="manager()">Gestionnaire</button>
            </div>
            <?php
        }
    ?>
    <form method="POST" action="" align="center" style="margin: 5%;">
        <input type="submit" name="logout" value="Se déconnecter" class="btn btn-danger">
    </form>
    <section id="song" style="margin: 5%">
        <?php
            $fetchSongs = $bdd->prepare('SELECT * FROM songs');
            $fetchSongs->execute();
            while($song = $fetchSongs->fetch())
            {
                ?>
                <div class="card" style="margin: 1%">
                    <div class="card-body d-flex align-items-center">
                        <button id="<?= $song['spacename']; ?>" onclick="play('<?= $song['spacename']; ?>', 'music/<?= $song['path']; ?>/<?= $song['spacename']; ?>.mp3')" type="button" class="btn btn-primary me-2">▶</button>
                        <p id="<?= $song['spacename']; ?>-data" class="mb-0 flex-grow-1"><strong><?= $song['title']; ?></strong> | <?= $song['author']; ?></p>
                        <p class="mb-0 flex-grow-2"><?= $song['time']; ?></p>
                    </div>
                </div>
                <?php
            }
        ?>
    </section>
    <br><br><br>
    <section class="timeline-wrapper">
        <section id="timeline">
            <div class="text-center">
                <p id="currentSong"></p>
                <audio controls id="line">
                    <source src="" type="audio/mpeg">
                </audio>
            </div>
        </section>
    </section>
    <script>
        var oldSource = ""
        var lastSong = ""

        function manager(){
            window.location.replace('manager.php');
        }

        function titleGood(str) {
            if (str.length <= 17) {
                return "";
            }
            
            return str.slice(8, str.length - 9);
        }

        function changeAudioSource(newSource, title, author) {
            const audioPlayer = document.getElementById('line');
            const currentSong = document.getElementById('currentSong');
            if(oldSource !== newSource) 
            {
                currentSong.innerHTML = "<strong>" + title + "</strong> | " + author;

                oldSource = newSource
                audioPlayer.src = newSource;
                document.title = titleGood(title) + " - " + author;
                audioPlayer.load();
            }
        }

        function play(song, path) {
            const button = document.getElementById(song);
            const data = document.getElementById(song+"-data").innerHTML;
            if(button.innerHTML == '▶') 
            {
                //Play
                button.innerHTML = '⏸️';
                button.classList.remove('btn-primary');
                button.classList.add('btn-secondary');
                var info = data.split(" | ");
                changeAudioSource(path, info[0], info[1]);
                document.getElementById('line').play();
                if(lastSong !== song)
                {
                    if(document.getElementById(lastSong) !== null)
                    {
                        var lbtn = document.getElementById(lastSong);
                        lbtn.innerHTML = '▶';
                        lbtn.classList.add('btn-primary');
                        lbtn.classList.remove('btn-secondary');
                    }
                    
                }
                lastSong = song;
            }
            else
            {
                //Stop
                button.innerHTML = '▶';
                button.classList.add('btn-primary');
                button.classList.remove('btn-secondary');
                document.getElementById('line').pause();
            }
            
        }

        document.getElementById('line').addEventListener('ended', function() {
        if (lastSong !== "") {
            const button = document.getElementById(lastSong);
            button.innerHTML = '▶';
            button.classList.add('btn-primary');
            button.classList.remove('btn-secondary');
            lastSong = ""; // Reset lastSong since no song is playing
        }
        });
    </script>
</body>
</html>