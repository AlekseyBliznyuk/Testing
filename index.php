<?php
// <iframe src="http://localhost/testing/" width="100%" height="700" frameborder="0"></iframe>
ini_set("display_errors", 1);
error_reporting(-1);
require_once 'config.php';
require_once 'functions.php';

if( isset($_POST['test']) ){
    $test = (int)$_POST['test'];
    unset($_POST['test']);
    $result = get_correct_answers($test);
    if( !is_array($result) ) exit('Ошибка!');
    // данные теста
    $test_all_data = get_test_data($test);
    // 1 - массив вопрос/ответы, 2 - правильные ответы, 3 - ответы пользователя
    $test_all_data_result = get_test_data_result($test_all_data, $result, $_POST);
    // print_r($_POST);
    // print_r($result);
    echo print_result($test_all_data_result);
    die;
}

// список тестов
$tests = get_tests();

if( isset($_GET['test']) ){
    $test_id = (int)$_GET['test'];
    $test_data = get_test_data($test_id);
    if( is_array($test_data) ){
        $count_questions = count($test_data);
        $pagination = pagination($count_questions, $test_data);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Система тестирования</title>
    <link href="css/tab.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body background="https://oir.mobi/uploads/posts/2021-03/1616430087_38-p-zadnii-fon-dlya-saita-44.jpg">
<ul class="nav">
    <li><a href="index.html">Главная</a></li>
    <li><a href="index.php">Система тестирования</a></li>
    <li><a href="about.php">О PHP</a></li>
    <li><a href="contact.php">Обратная связь</a></li>
    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOEAAADhCAMAAAAJbSJIAAAAclBMVEX///9hgbZef7VcfbRXerJSdrH19/pWebKZrM36+/xZfLNcfrRVeLJWerK1wtpkhLjp7fTa4Oxzj77K1OV7lcFpiLqktdPv8veVqczg5vCLocjM1ebj6PGPpMm/y+BvjLysu9aCmsO5xt1Hb615k7+puNWflkwiAAAK2klEQVR4nO2c25rqKgyAlaLYg9aetWqrbuf9X3G31ZkhQAs96ez95b9YF2sskAAhJMBigSAIgiAIgiAIgiAIgiAIgiAIgiAIgiAIgiAIgiAIgiAIgiAIgiCIMbadHYOo2F03h/wWh2WS+D7x/SQpw/iWHzbXXREFx8y2P93Q3mT7oNht8kfirBijlDqW5VqELGte/5Lqfyyn+htjKyd55JtrEeyzTzfcgCy4p3noU6+Syn0KYwJxK2kZ9cM8rQT9tBBt7KM09gmllmUumUJSyiw/Tovjp8UB2NnpK2YeHSGaIKjFGIvTKPsTE3RfHELGnKmE48Ssxm15KD47ZrPoUFrTdZ1CSos65SX6kAU678Jqzswn3Q/VkA2v756XdpAm3gwjsw3ieEkarN8mX7ApPedt0n1jeckmeId4+2tJrbeL9xKSJV8zWx47CleDBifjoGOEdFZlMd9oPac+Gzb3SMBRjBvhhJH0PIt8Qe4MbRp58AUVozpx2ayU+Wly+aKEuYObZKV8UZsJprHLymhK8ewi8cYsDRS0Jp5klSEsKSZz6qJy4PT7xuMN4NqfQsAaVhaTyHcK6UilE4cv78ymka8umIbj5+P+NrL/6obc+BJHGxpQtPcYZ1fX6Qj78gM0NOm0/oLlbUasj5E/ib4pmC63qd1Zxx9qVrN8ovHEwECaYTtC80HdGE3mfiZ8/XtvolJ5LNa/G9eb8RbmBTQ00ZSG5rcO1nc27svpGmJt+JKvM+27nLKXUY0m68ClZGjm2nqRbY+R+jXKRxNxQARiOVtkgHhXUwE3245yrFUbXrX1c+QwMPGBofmH+6CCqQJZbnsdnkdp6xK9PZgJeOuags6XvW4h2wfRLrfEAAcJ+cJt+MXxdE9j5sGR6+YddRyj3cVftTiS9NYmFN+ER6cp2GodwfM1AbMYGholQQpcC7rTfZDtQvVa5sTa/cY67rZ1lkmcJAq5BjOTDYBd+L8tZibudHBTevBOrFs1bhpj7htUXjV497uue2ahzvWvB+WZRYAjoupGRzNQL5plkJgM9Jrgx2auDL9Y5N/KTQw/yEqViPTS9U2q27s5X6bt3SevTGFp+sUifH7h5qYf2KFKRJa2fxFpN6fUfFU9P5cBA0PzTfAcQM7V+It1qbKp7U7qXr8Yez1co6LRF+0RaXiQ7gbKBMpZRdrMYajf7Xrmlb9CTqxHTuXps7I+YW1l4M4N1T/e6QMoMPB5//FP2DK87CRRgtqgOvwCdf71aDwnuYjR61PTI2CzFfzWQcr8GojLXabsRKZcUfcGW9PWwCchLmUPcR2rTIcQDAZKdFkCtdKMOQuY6/uvBGTpOl55F+pQevJEuWxfDJx+OKnEwGe1SYNFVqMOGhoxRmPB5fUpIdDiwRLriGEd6v2mpVgyApMgH5hUtuwcsC+xTAp0HorDBLoDTRuguU6kOihcTDJ1zIDJibibQVCNEH6KHBU6YcAdySqVAxGkL6Cb2/QH2GytiTx1trDxsg5qXMk1UTVXlrBjUj2Bw9iulite5LOkbyhhbUvJEhgaxRh04IreErtj4romjncl3ZNK/sXiYQG7Lc8ZD/THxRU3W6rwMTRFbS23hL2ibRRq10yqpeRxPSioR9bJKhNLhDpSLXcktHU/aRoLFxa9v1YDp6/qE0G/MQM6kbNOW/7PWT3pus11I2FpJKHgGuUmwXvdpFpKfRgCndi+2F4CTH9jSsG4VeaphD5sm1+wKWu1QZKK5r9RLkRwjNmlBXQitQU62bu6xBXwgVTjRNjB5W0WBPhGR6PoqAuWUWWGBQYgMh9s9WSdUGBKD/VAAl+ozLVoQ1Tm4Fn4UVeSpvnKVC4MQJwZ1InkIniyodGYJqkZi9agBJjRZhkvOKmUA9sDvvRpBQyN7EIm/JBcNx4N/EKpRWDv2vMgYN28GGUJne5JtRRdgkW6AmZD0gk0Bo2hgVpUeDRC/LUjDwImlVGonSxByaqwsRDkiFcgGCxNBfjzxtAwbZ5KMNftJzvAymV0PAKudV+q8Q9dpYwBs3GSJISztvFowFqn7B9hnWs/9wCWooeJhNC0q4y04O4WK2A25KyTA3ZxdVsNzDUYSV2rAJgyRn0IvOR1qfiFsGV5wK227FWU/JBcr5aiFlWKFwLMHcePQB8azUNwKkaeVFXl0O0+r6DE0niCw/5paMC4VdQhuIVdh3PAT01sKXH5ouVJtXSE+M/ForzxlbeqcBvUxCssjRbdBMbDdx2uChjxJush1J48qegNOvNHCh1IWSdUMjTLbkNDaAkF7DxfBRRocooHahxOKuLSpRjeurnQbMg6geGiUtKiYK4JtTZCqG3TlWUBPo2JXwq3NaS+7fOCrUh+F7MpldKg2ZDmOlxfszrRAbUY83V4TrwTA2jdgQngl5rsLcBaZ193L+5FFChyRXtKBNMq+SeKrROMQt35OvZyYtBOOpcAsLcw2R/6fY5z1FtBAoLre2mYKDwap9d5iu4VQPB99JsLErfUoxSwqRxknU66rZNVB3L7CLjpbrPg+6y1EvbIIb1SgVCJO8korMC0qld30pJvUAuosR1CnEYfa+uRQ3plygUvT5wHhPB/bmI0VkfmT+TQdWBkqYh6a8+1mueQguSpLTgKJS9P5dEYa/H80Fl/KV6qi3nDKFRX3YfvkyAgS7aXTCk0NI1HY6rFLHV0Q06OeevyFobJ6lP+UzcchXLxcGVoPGjP6Gh6cCD6Wy2KvIUm9ySGkGXs9Wmz5Y4UC4ZGGlVgr9vEaAwMjR18LU0uzKlyT5r8IQj/2BuRw+VWMqHqkP9BKBd54P9efwnN9dcBVlHXEW7NrpSp84fdOWDQ6/utJaK61sz/XVGkK/4dRqGkKur74CbiLdtywN15fBBEM8o19gdoMVsNL6gtj991FgN6yV27suEQi9eiYgNqXlLrWYf2UqHZmOLmkqJdYDs5Qotdx1Vaz0RB/2Sam0sicDtplNBU0nUmqv1cG4ifrOc55wuj9crTTkbFdJ5raz2bCBIMs1woEBfpoV2oO5vYdr4UJBhOGqd3GHA7eR44DZ2bdhtrq0S0NBuhSYAB8mESGpwRXijPeUMv2SyP0xddLM8Eo3PeC9VZfd0pnimA5nqQFk3P6lcKFO9bwOOCI7yNDuB2sjWz206P+xb10g8rSHRnoSYAJA0y6VCDll53ZqoFgT9t/5a7WUvG19H/Ki3td++p3iBx3Shsa2YxpTCW11eLxBPD4QZEv5GCd9zNguHunloccv9wUd+A+LapTmD/MpMprbTI1dF6SEbJdtgd0orT92Udxt+umkO+CsrX0UdAOvge8GKqu9yzYrF03Fsn+3jKy5aTQ7x4/Dsnp8fYNxVmY5I3FWpGv4sxExO+b2IXf09G4k34tknNqfxTNsel075P0xBcBr8xNDUOzed59eucJn9gsBLmz/ROVI19ioe99TUZziqMZn4v8n/+XtuT//mbew32MU28NzoCz3cT3/2a6Xn3EPNp80j3kbcvX2TRJpz//dJD9L5HL1Xsi8ODzvUGbfjpN2i/qd8R3nq0EnMaQSvhvO2feUf4h5+3oHu8ci2J9nwL+pZGf6PrFGRBkeaP+j3vXpLW73mzP/+eN8e6fmNlwJvsn7UoQ7Dtdf2u/v2aHi7Su/qXQ3qtj1Ue1//Bd/URBEEQBEEQBEEQBEEQBEEQBEEQBEEQBEEQBEEQBEEQBEEQBEEQ5IP8C80drjqsnf1RAAAAAElFTkSuQmCC" align="right" width="200" height="200">
</ul>
<div class="wrap"><br>

    <?php if( $tests ): ?>
        <h1>Варианты тестов</h1>
        <?php foreach($tests as $test): ?>
            <font><a href="?test=<?=$test['id']?>" style="color: aqua"><font size="5"><?=$test['test_name']?></a></font></p>
        <?php endforeach; ?>

        <br><hr><br>
        <div class="content">

            <?php if( isset($test_data) ): ?>

                <p><font size="5">Всего вопросов: <?=$count_questions?></font></p>
                <?=$pagination?>
                <span class="none" id="test-id"><?=$test_id?></span>

                <div class="test-data">

                    <?php foreach($test_data as $id_question => $item): // получаем каждый конкретный вопрос + ответы ?>

                        <div class="question" data-id="<?=$id_question?>" id="question-<?=$id_question?>">

                            <?php foreach($item as $id_answer => $answer): // проходимся по массиву вопрос/ответы ?>

                                <?php if( !$id_answer ): // выводим вопрос ?>
                                    <p class="q" ><font size="5"><?=$answer?></font></p>
                                <?php else: // выводим варианты ответов ?>

                                    <p class="a"><font size="5">
                                        <input type="radio" id="answer-<?=$id_answer?>" name="question-<?=$id_question?>" value="<?=$id_answer?>">
                                        <label for="answer-<?=$id_answer?>"><?=$answer?></label>
                                        </font>
                                    </p>

                                <?php endif; // $id_answer ?>

                            <?php endforeach; // $item ?>

                        </div> <!-- .question -->

                    <?php endforeach; // $test_data ?>

                </div> <!-- .test-data -->

                <div class="buttons">
                    <button class="center btn" id="btn">Закончить тест</button>
                </div>

            <?php else: // isset($test_data) ?>
                <p><font size="5">Выберите тест</font></p>
            <?php endif; // isset($test_data) ?>

        </div> <!-- .content -->

    <?php else: // $tests ?>
        <h3>Нет тестов</h3>
    <?php endif; // $tests ?>

</div> <!-- .wrap -->

<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="js/scripts.js"></script>
</body>
</html>