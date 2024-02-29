

<?php
$host = "127.0.0.1"; // Le host est le nom du service, présent dans le docker-compose.yml
$dbname = "db_pro";


try {
    $pdo = new PDO(
        dsn: "pgsql:host=$host;dbname=$dbname;",
        username: "root",
        password: "root",
    );

    

} catch (PDOException $e) {
    var_dump($e);
    throw new PDOException(
        message: $e->getMessage(),
        code: (int)$e->getCode()
    );
}

?>
