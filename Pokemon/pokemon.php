
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
            if (isset($_GET["next"])) {
                $offset = $_GET["next"] + 10;
            }
            if (isset($_GET["previous"])) {
                $offset = $_GET["previous"] - 10;
            }
        }else{
            $offset = 0;
        }

        $limit = 10; // 1ページに表示するポケモンの数
        $url = 'https://pokeapi.co/api/v2/pokemon/?limit=' . $limit . '&offset=' . $offset;
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        foreach ($data['results'] as $pokemon){
            $pokemonUrl = $pokemon['url'];
            $pokemonResponse = file_get_contents($pokemonUrl);
            $pokemonData = json_decode($pokemonResponse, true); // ポケモン１体のデータ

            echo "<div class='pokemon-card'>";
            echo "<h2>" . $pokemonData['name'] . "</h2>";
            echo "<img src='" . $pokemonData['sprites']['front_default'] . "' alt='" . $pokemonData['name'] . "'>";
            echo "<p>たかさ: " . $pokemonData['height'] . "</p>";
            echo "<p>おもさ: " . $pokemonData['weight'] . "</p>";
            echo "<p>タイプ: ";
            foreach ($pokemonData['types'] as $type) {
                echo "<span class='type type-" . $type['type']['name'] . "'>" . $type['type']['name'] . "</span> ";
            }
            echo "</p>";
            echo "</div>";
        }

        if ($data["next"] != NULL) {
            echo "<form action='pokemon.php'>
            <button name='next' value='{$offset}'>次のページ</button>
            </form>";
        }

        if ($data["previous"] != NULL) {
            echo "<form action='pokemon.php'>
            <button name='previous' value='{$offset}'>前のページ</button>
            </form>";
        }
        ?>

    </div>
</body>
</html>
