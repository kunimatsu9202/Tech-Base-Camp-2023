
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ポケモン図鑑</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">

        <?php
        
        if (isset($_POST["detail"])){
            echo "<h1>" . $_POST['name'] . "</h1>";
            echo "<img class='img-detail' src='" . $_POST["img_front"] . "' alt='" . $_POST['img_front'] . "'>";
            echo "<img class='img-detail' src='" . $_POST["img_back"] . "' alt='" . $_POST['img_back'] . "'>";
            echo "<img class='img-detail' src='" . $_POST["img_art"] . "' alt='" . $_POST['img_art'] . "'>";
            echo "<h2>たかさ: " . $_POST['height']/10 . "m </p>";
            echo "<h2>おもさ: " . $_POST['weight']/10 . "kg </p>";
            echo "<h2>タイプ: ";
            for ($i=0; $i<count($_POST['types_ja']); $i++) {
                echo "<span class='type type-" . $_POST['types_en'][$i] . "'>" . $_POST['types_ja'][$i] . "</span> ";
        }                
            echo "<h2>せつめい：" . $_POST['data'] . "</p>";
            echo "<form action='pokemon.php' method = 'get'>
            <button name='offset' value='{$_POST['offset']}'>ポケモン一覧へ</button>
            <input type='hidden' name='limit' value='{$_POST['limit']}'>
            </form>";
            exit;
        }

        ?>

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
            if (isset($_GET["offset"]) && isset($_GET["limit"])) {
                $limit = $_GET["limit"];
                $offset = $_GET["offset"];
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
            /* ポケモン１体のデータを取得 */
            $pokemonUrl = $pokemon['url'];
            $pokemonResponse = get_cache_contents($pokemonUrl, $pokemon["name"]);
            $pokemonData = json_decode($pokemonResponse, true);
            
            /* ポケモン１体の日本語なまえを取得 */
            $url_species = 'https://pokeapi.co/api/v2/pokemon-species/' . $pokemonData['id'];
            $response_species = get_cache_contents($url_species, $pokemon["name"]."_janame");
            $species_data = json_decode($response_species, true);

            /* ポケモン一体の日本語タイプを取得 */
            $types_ja = [];
            for ($i=0; $i<count($pokemonData['types']); $i++) {
                $url_type = $pokemonData['types'][$i]['type']['url'];
                $response_type = get_cache_contents($url_type, $pokemon["name"]."_jatype" .$i);
                $type_ja = json_decode($response_type, true);
                array_push($types_ja, $type_ja['names'][0]['name']);
            }

            echo "<div class='pokemon-card'>";
            echo "<h2>" . $species_data['names'][0]['name'] . "</h2>";
            echo "<img src='" . $pokemonData['sprites']['front_default'] . "' alt='" . $pokemonData['name'] . "'>";
            echo "<p>たかさ: " . $pokemonData['height']/10 . "m </p>";
            echo "<p>おもさ: " . $pokemonData['weight']/10 . "kg </p>";
            echo "<p>タイプ: ";
            for ($i=0; $i<count($types_ja); $i++) {
                echo "<span class='type type-" . $pokemonData['types'][$i]['type']['name'] . "'>" . $types_ja[$i] . "</span> ";
            }
            echo "</p>";
            echo "<form action='pokemon.php' method='post'>
            <button id='detail' name='detail' value='detail'>詳細</button>
            <input type='hidden' name='name' value='{$species_data['names'][0]['name']}'>
            <input type='hidden' name='img_front' value='{$pokemonData['sprites']['front_default']}'>
            <input type='hidden' name='img_back' value='{$pokemonData['sprites']['back_default']}'>
            <input type='hidden' name='img_art' value='{$pokemonData['sprites']['other']['official-artwork']['front_default']}'>
            <input type='hidden' name='height' value='{$pokemonData['height']}'>
            <input type='hidden' name='weight' value='{$pokemonData['weight']}'>";

            for ($i=0; $i<count($types_ja); $i++) {
                echo "<input type='hidden' name='types_ja[] 'value='{$types_ja[$i]}'>";
                echo "<input type='hidden' name='types_en[] 'value='{$pokemonData['types'][$i]['type']['name']}'>";
            }
            
            foreach ($types_ja as $type) {
                echo "<input type='hidden' name='types[]' value='{$type}'>";
                echo "<input type='hidden' name='types[]' value='{$type}'>";
            }
            
            echo "
            <input type='hidden' name='data' value='{$species_data['flavor_text_entries'][22]['flavor_text']}'>
            <input type='hidden' name='offset' value='{$offset}'>
            <input type='hidden' name='limit' value='{$limit}'>
            </form>";
            echo "</div>";
        }

        echo "</div>";

        echo "<div class='button-container'>";

        if ($data["previous"] != NULL) {
            echo "<form action='pokemon.php' method = 'get'>
            <button name='previous_offset' value='{$offset}'>前のページ</button>
            <input type='hidden' name='previous_limit' value='{$limit}'>
            </form>";
        } else {
            echo "<div>　　　　　</div>"; // decoy
        }

        if ($limit == 10) {
            echo "<form action='pokemon.php' method='get'>
            <button name='twenty' value='{$offset}'>20件</button>
            <button name='fifty' value='{$offset}'>50件</button>
        </form>";
        } elseif ($limit == 20) {
            echo "<form action='pokemon.php' method='get'>
            <button name='ten' value='{$offset}'>10件</button>
            <button name='fifty' value='{$offset}'>50件</button>
        </form>";
        } elseif ($limit == 50) {
            echo "<form action='pokemon.php' method='get'>
            <button name='ten' value='{$offset}'>10件</button>
            <button name='twenty' value='{$offset}'>20件</button>
        </form>";
        }

        if ($data["next"] != NULL) {
            echo "<form action='pokemon.php' method = 'get'>
            <button type='submit' name='next_offset' value='{$offset}'>次のページ</button>
            <input type='hidden' name='next_limit' value='{$limit}'>
            </form>";
        } else {
            echo "<div>　　　　　</div>"; // decoy
        }

        echo "</div>";

        // file_get_contentsの結果をキャッシュしつつ返す
        function get_cache_contents($url, $pokemon) {
            $cache_path = "./cache/" . $pokemon . ".json" ;
            $cache_limit = 864000;
            if(file_exists($cache_path) && filemtime($cache_path) + $cache_limit > time()) {
            // キャッシュ有効期間内なのでキャッシュの内容を返す
            return file_get_contents($cache_path);
            } else {
            // キャッシュがないか、期限切れなので取得しなおす
            $data = file_get_contents($url);
            file_put_contents($cache_path, $data, LOCK_EX); // キャッシュに保存
            return $data;
            }
        }

        ?>

    </div>
</body>
</html>
