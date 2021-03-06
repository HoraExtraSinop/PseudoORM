<?php
namespace PseudoORM\Factory;

use PseudoORM\Entity\EntidadeBase;
use PseudoORM\DAO\GenericDAO;
use \Exception;

class AppFactory
{
    private static $instance;

    /**
     * Set the factory instance for DI
     * @param App_DaoFactory $factory
     */
    public static function setFactory(AppDaoFactory $factory)
    {
        self::$instance = $factory;
    }

    /**
     * Get a factory instance.
     * @return App_DaoFactory
     */
    public static function getFactory()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public static function getRepository(EntidadeBase $objeto)
    {
        try {
            $class = get_class($objeto);
            $respositoryPath = DAOS . $class . 'DAO.php';
            if (!file_exists($respositoryPath)) {
                return new GenericDAO($class);
            }
            require_once $respositoryPath;
            $repository = $class . 'DAO';
            return new $repository;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
