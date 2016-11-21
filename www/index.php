<?php
include '../lib/Twig/Autoloader.php';
Twig_Autoloader::register();

// подключение к бд
try {
    $dbh = new PDO('mysql:dbname=photoGallery;host=localhost', 'root', '');
} catch (PDOException $e) {
    echo "Error: Could not connect. " . $e->getMessage();
}

// установка error режима
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// выполняем запрос
try {
    // формируем SELECT запрос
    // в результате каждая строка таблицы будет объектом
    $sql = "SELECT * FROM images ORDER BY popularity DESC;";
    $sth = $dbh->query($sql);
    while ($row = $sth->fetchObject()) {
        $data[] = $row;
    }

    // закрываем соединение
    unset($dbh);

    $loader = new Twig_Loader_Filesystem('templates');

    $twig = new Twig_Environment($loader);

    //$template = $twig->loadTemplate('countries2.tmpl');
    $template = $twig->loadTemplate('index.tmpl');
     //echo "<pre>";
     //var_dump($data);
     //echo "</pre>";die();

    echo $template->render(array (
        'data' => $data
    ));

} catch (Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}
?>

