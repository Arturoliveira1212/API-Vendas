<?php

namespace core;

use core\Controller;
use app\services\Service;
use app\dao\DAO;
use app\exceptions\NaoEncontradoException;
use app\views\View;

abstract class ClassFactory {

    const CAMINHO_CONTROLLER = 'app\\controllers\\';
    const CAMINHO_SERVICE = 'app\\services\\';
    const CAMINHO_DAO = 'app\\dao\\';
    const CAMINHO_VIEW = 'app\\views\\';

    /**
     * Método responsável por fabricar intâncias de controllers.
     *
     * @param string $nomeController
     * @throws NaoEncontradoException
     * @return Controller
     */
    public static function makeController( string $nomeController ){
        $controller = self::CAMINHO_CONTROLLER . $nomeController . 'Controller';
        if( ! class_exists( $controller ) ){
            throw new NaoEncontradoException( "Controller $nomeController não encontrado." );
        }

        return new $controller();
    }

    /**
     * Método responsável por fabricar intâncias de services.
     *
     * @param string $nomeService
     * @throws NaoEncontradoException
     * @return Service
     */
    public static function makeService( string $nomeService ){
        $service = self::CAMINHO_SERVICE . $nomeService . 'Service';
        if( ! class_exists( $service ) ){
            throw new NaoEncontradoException( "Service $nomeService não encontrado." );
        }

        return new $service();
    }

    /**
     * Método responsável por fabricar intâncias de DAOs.
     *
     * @param string $nomeDAO
     * @throws NaoEncontradoException
     * @return DAO
     */
    public static function makeDAO( string $nomeDAO ){
        $DAO = self::CAMINHO_DAO . $nomeDAO . 'DAO';
        if( ! class_exists( $DAO ) ){
            throw new NaoEncontradoException( "DAO $nomeDAO não encontrado." );
        }

        return new $DAO();
    }

    /**
     * Método responsável por fabricar intâncias de Views.
     *
     * @param string $nomeView
     * @return View
     */
    public static function makeView( string $nomeView ){
        $view = self::CAMINHO_VIEW . $nomeView . 'View';
        if( ! class_exists( $view ) ){
            throw new NaoEncontradoException( "View $nomeView não encontrado" );
        }

        return new $view();
    }
}