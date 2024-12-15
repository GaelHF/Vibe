<?php
    session_start();
    include 'bdd.php';

    if(!isset($_SESSION['auth']))
    {
        header('Location: index.php');
    }

    if(isset($_POST['change_to_dark']))
    {
        $_SESSION['theme'] = 'dark';
    }
    
    if(isset($_POST['change_to_light']))
    {
        $_SESSION['theme'] = 'light';
    }

    if(isset($_POST['logout']))
    {
        session_destroy();
        setcookie('auth', '', time() - 3600, '/');
        setcookie('pseudo', '', time() - 3600, '/');
        setcookie('theme', '', time() - 3600, '/');
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
        .timeline-wrapper {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
        }

        <?php
            if($_SESSION['theme'] == 'dark')
            {
                ?>
                #timeline {
                    background-color: rgb(0, 0, 0);
                    padding: 10px;
                    border: 2px solidrgb(0, 0, 0);
                    border-radius: 5px;
                }
                <?php
            }
            else
            {
                ?>
                #timeline {
                    background-color: rgb(255, 255, 255);
                    padding: 10px;
                    border: 2px solidrgb(0, 0, 0);
                    border-radius: 5px;
                }
                <?php
            }
        ?>

        audio::-webkit-media-controls-play-button {
            display: none;
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>VIBE - Bibliothèque</title>
</head>
<body style="background-color: rgb(42, 6, 50);">
    <br>
    <h1 class="text-center text-white">Bonjour, <?= $_SESSION['pseudo']; ?></h1>
    <?php
        if($_SESSION['pseudo'] == $vibe_username)
        {
            ?>
            <div style="margin: 5%;" class="text-center">
                <button type="button" class="btn btn-info" onclick="manager()">Gestionnaire</button>
            </div>
            <?php
        }
    ?>
    <?php
        if($_SESSION['theme'] == 'light')
        {
            ?>
                <form method="POST" action="" align="center">
                    <input type="submit" name="change_to_dark" value="Mode Sombre" class="btn btn-dark">
                </form>
            <?php
        }
        else
        {
            ?>
                <form method="POST" action="" align="center">
                    <input type="submit" name="change_to_light" value="Mode Clair" class="btn btn-light">
                </form>
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
                if($_SESSION['theme'] == 'dark')
                {
                    ?>
                        <div class="card" style="margin: 1%; background-color: black;">
                            <div class="card-body d-flex align-items-center" >
                                <button id="<?= $song['spacename']; ?>" onclick="play('<?= $song['spacename']; ?>', 'music/<?= $song['path']; ?>/<?= $song['spacename']; ?>.mp3')" type="button" class="btn btn-primary me-2">▶</button>
                                <p id="<?= $song['spacename']; ?>-data" class="mb-0 flex-grow-1 text-white"><strong><?= $song['title']; ?></strong> | <?= $song['author']; ?><br><i class="bi bi-youtube clipyt" onclick="load_exrt('<?= $song['yt_url']; ?>')"></i> | <i class="bi bi-spotify clipsfy" onclick="load_exrt('<?= $song['sfy_url']; ?>')"></i></p>
                                <p class="mb-0 flex-grow-2 text-white"><?= $song['time']; ?></p>
                            </div>
                        </div>
                    <?php
                }
                else
                {
                    ?>
                        <div class="card" style="margin: 1%;">
                            <div class="card-body d-flex align-items-center" >
                                <button id="<?= $song['spacename']; ?>" onclick="play('<?= $song['spacename']; ?>', 'music/<?= $song['path']; ?>/<?= $song['spacename']; ?>.mp3')" type="button" class="btn btn-primary me-2">▶</button>
                                <p id="<?= $song['spacename']; ?>-data" class="mb-0 flex-grow-1"><strong><?= $song['title']; ?></strong> | <?= $song['author']; ?><br><i class="bi bi-youtube clipyt" onclick="load_exrt('<?= $song['yt_url']; ?>')"></i> | <i class="bi bi-spotify clipsfy" onclick="load_exrt('<?= $song['sfy_url']; ?>')"></i></p>
                                <p class="mb-0 flex-grow-2"><?= $song['time']; ?></p>
                            </div>
                        </div>
                    <?php
                }
            }
        ?>
    </section>
    <br><br><br>
    <section class="timeline-wrapper">
        <section id="timeline">
            <div class="text-center">
                <?php
                    if($_SESSION['theme'] == 'dark')
                    {
                        ?>
                            <p id="currentSong" class="text-white"></p>
                        <?php
                    }
                    else
                    {
                        ?>
                            <p id="currentSong"></p>
                        <?php
                    }
                ?>
                <audio controls id="line">
                    <source src="" type="audio/mpeg">
                </audio>
            </div>
        </section>
    </section>
    <script>
        var oldSource = ""
        var lastSong = ""

        document.getElementById('autoPlayCheckbox').addEventListener('change', function() {
            autoPlayEnabled = this.checked;
        });

        function manager(){
            window.location.replace('manager.php');
        }

        function titleGood(str) {
            if (str.length <= 17) {
                return "";
            }
            
            return str.slice(8, str.length - 9);
        }

        function changeAudioSource(newSource, title, author, media) {
            const audioPlayer = document.getElementById('line');
            const currentSong = document.getElementById('currentSong');
            if(oldSource !== newSource) 
            {
                currentSong.innerHTML = "<strong>" + title + "</strong> | " + author + " | " + media;

                oldSource = newSource
                audioPlayer.src = newSource;
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
                changeAudioSource(path, info[0], info[1], info[2]);
                document.title = titleGood(info[0]) + " - " + info[1];
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

        function load_exrt(link)
        {
            window.open(link);
        }
        document.getElementById('line').addEventListener('ended', function() {
        if (lastSong !== "") {
            const button = document.getElementById(lastSong);
            button.innerHTML = '▶';
            document.title = 'VIBE - Bibliothèque';
            button.classList.add('btn-primary');
            button.classList.remove('btn-secondary');
            lastSong = "";
        }
        });
    </script>
</body>
</html>