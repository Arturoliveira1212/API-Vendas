<?php


require_once './bootstrap.php';

use core\App;
use app\exceptions\NaoEncontradoException;
use app\models\Categoria;
use app\traits\ConversorDados;
use app\views\AppView;
use core\ClassFactory;




/** @var AppView */
$appView = ClassFactory::makeView( 'App' );

// $c = new ConversorDados;
// $a = $c->converterEmObjeto( Categoria::class, [
//     'id' => 1,
//     'nome' => 'Legging',
//     'descricao' => 'descricao',
//     'data' => new DateTime()
// ]);


// $categoria = new Categoria;
// $categoria->setId(25);
// $categoria->setNome('nome');
// $categoria->setDescricao('descricao');
// $categoria->setData( new DateTime() );

// $c = new ConversorDados;
// $a = $c->converterEmArray( $categoria );

// dd($a);
// echo json_encode( $a );
// die;


try {
    $app = new App();
    $app->executar();
} catch( NaoEncontradoException $e ){
    $appView->recursoNaoEncontrado( $e );
} catch( Throwable $e ){
    $appView->erroInternoAPI();
}