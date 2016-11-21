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
    if($_SERVER['REQUEST_METHOD']==='GET' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM images WHERE id = '$id';";
        $sth = $dbh->query($sql);
        while ($row = $sth->fetchObject()) {
            $data[] = $row;
        }
        // закрываем соединение
        unset($dbh);
        if(!isset($data)){
           echo 'Картинка не найдена';die();
        }
        $data = $data ? $data[0] : $data;

        $loader = new Twig_Loader_Filesystem('templates');

        $twig = new Twig_Environment($loader);

        //$template = $twig->loadTemplate('countries2.tmpl');
        $template = $twig->loadTemplate('image.tmpl');
         //echo "<pre>";
         //var_dump($data[0]->id);
         //echo "</pre>";die();

        echo $template->render(array(
            'name' => $data->name,
            'title' => $data->title,
            'alt' => $data->alt,
            'popularity' => $data->popularity,
        ));

    }
    
} catch (Exception $e) {
    die ('ERROR: ' . $e->getMessage());
}
?>