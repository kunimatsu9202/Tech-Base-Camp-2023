
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ポケモン図鑑</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>ポケモン図鑑</h1>

        <?php

        /* offsetの設定 */
        if (!empty($_GET)) {  // memo : issetでは空の配列にも反応してしまうため、!emptyを使用
            if (isset($_GET["next_offset"]) && isset($_GET["next_limit"])) {
                $limit = $_GET["next_limit"];
                $offset = $_GET["next_offset"] + $limit;
            }
            if (isset($_GET["previous_offset"]) && isset($_GET["previous_limit"])) {
                $limit = $_GET["previous_limit"];
                $offset = $_GET["previous_offset"] - $limit;
            }
            if (isset($_GET["ten"])) {
                $offset = $_GET["ten"];
                $limit = 10;
            }
            if (isset($_GET["twenty"])) {
                $offset = $_GET["twenty"];
                $limit = 20;
            }
            if (isset($_GET["fifty"])) {
                $offset = $_GET["fifty"];
                $limit = 50;
            }
        }else{
            $offset = 0;
            $limit = 10; // 1ページに表示するポケモンの数
        }

        /*  */
        if ($offset < 0) {
            $offset = 0;
        }

        $url = 'https://pokeapi.co/api/v2/pokemon/?limit=' . $limit . '&offset=' . $offset;
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        echo "<div class='all-pokemon-card'>";

        foreach ($data['results'] as $pokemon){
            $pokemonUrl = $pokemon['url'];
            $pokemonResponse = file_get_contents($pokemonUrl);
            $pokemonData = json_decode($pokemonResponse, true); // ポケモン１体のデータ

            echo "<div class='pokemon-card'>";
            echo "<h2>" . $pokemonData['name'] . "</h2>";
            echo "<img src='" . $pokemonData['sprites']['front_default'] . "' alt='" . $pokemonData['name'] . "'>";
            echo "<p>たかさ: " . $pokemonData['height']/10 . "m </p>";
            echo "<p>おもさ: " . $pokemonData['weight']/10 . "kg </p>";
            echo "<p>タイプ: ";
            foreach ($pokemonData['types'] as $type) {
                echo "<span class='type type-" . $type['type']['name'] . "'>" . $type['type']['name'] . "</span> ";
            }
            echo "</p>";
            echo "</div>";
        }

        echo "</div>";

        if ($limit == 10) {
            echo "<form action='pokemon.php' method='get'>
            <button id='twenty' name='twenty' value='{$offset}'>20件</button>
            <button id='fifty' name='fifty' value='{$offset}'>50件</button>
        </form>";
        } elseif ($limit == 20) {
            echo "<form action='pokemon.php' method='get'>
            <button id='ten' name='ten' value='{$offset}'>10件</button>
            <button id='fifty' name='fifty' value='{$offset}'>50件</button>
        </form>";
        } elseif ($limit == 50) {
            echo "<form action='pokemon.php' method='get'>
            <button id='ten' name='ten' value='{$offset}'>10件</button>
            <button id='twenty' name='twenty' value='{$offset}'>20件</button>
        </form>";
        }

        if ($data["next"] != NULL) {
            echo "<form action='pokemon.php' method = 'get'>
            <button id='next' type='submit' name='next_offset' value='{$offset}'>次のページ</button>
            <input type='hidden' name='next_limit' value='{$limit}'>
            </form>";
        }

        if ($data["previous"] != NULL) {
            echo "<form action='pokemon.php' method = 'get'>
            <button id='previous' name='previous_offset' value='{$offset}'>前のページ</button>
            <input type='hidden' name='previous_limit' value='{$limit}'>
            </form>";
        }
        ?>

    </div>
</body>
</html>
