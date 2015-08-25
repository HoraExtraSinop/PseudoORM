<?php
define("DB_USERNAME", "postgres");
define("DB_PASSWORD", "postgres");
define("DB_HOST", 'localhost');
define("DB_PORT", 5432);
define("DB_NAME", 'meu_db');
define("SCHEMA", '');
define('ENCODING', "SET NAMES 'utf8';");
define("DB_DSN", "pgsql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME.";");
define("SHOW_SQL_ERROR", PDO::ERRMODE_EXCEPTION);
// PATHS
define('MODELS', '../app/models/');
define('DAOS', MODELS . 'DAO/impl/');
define('EXCEPTIONS', MODELS . 'exception/');

use PseudoORM\Entity\Usuario;
use PseudoORM\Factory\AppFactory;

$composer_autoload = 'vendor/autoload.php';
if (false === file_exists($composer_autoload)) {
        throw new RuntimeException('Por favor instalar as dependências do composer.');
}

include $composer_autoload;

/**
 * Exemplo de uso
 */

$dao = AppFactory::getRepository(new Usuario());

$usuario = $dao->create();
$usuario->nome = 'Zé da Silva';
$usuario->idade = 25;

$dao->insert($usuario);


$usuarios = $dao->getList();

foreach ($usuarios as $usuario) {
    echo $usuario->nome.'<br>';
}