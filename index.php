<?php
/*

*/
include 'rapgenius.php';
require 'markov.php';

if (isset($_POST['submit'])) {
    // generate text with markov library
    $order  = $_REQUEST['order'];
    $length = $_REQUEST['length'];
    $input  = $_REQUEST['input'];
    $ptext  = $_REQUEST['text'];

    if ($input) $text = $input;
    if ($ptext) $text = file_get_contents("text/".$ptext.".txt");

    if(isset($text)) {
        $markov_table = generate_markov_table($text, $order);
        $markov = generate_markov_text($length, $markov_table, $order);

        if (get_magic_quotes_gpc()) $markov = stripslashes($markov);
    }
}
?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <title>PHP Markov chain rap generator</title>
    <link rel="stylesheet" type="text/css" href="http://static.haykranen.nl/common/style.css" />    
</head>
<body>
    <h1>PHP Markov chain rap generator</h1>

    <?php if (isset($markov)) : ?>
        <h2>Output text</h2>
        <textarea rows="20" cols="80" readonly="readonly"><?php echo $markov; ?></textarea>
    <?php endif; ?>

    <h2>Input text</h2>
    <form method="post" action="" name="markov">
        <textarea rows="20" cols="80" name="input"><?php ?></textarea>
        <br />
        <select name="text">
            <option value="">Or select one of the input texts here below</option>
            <option value="alice">Alice's Adventures in Wonderland, by Lewis Carroll</option>
        </select>
        <br />
        <label for="order">Order</label>
        <input type="text" name="order" value="4" />
        <label for="length">Text size of output</label>
        <input type="text" name="length" value="800" />
        <br />
        <input type="submit" name="submit" value="GO" />
    </form>

</body>
</html>