<?php

namespace app\traits;

use app\models\Model;
use DateTime;
use ReflectionClass;

trait ConversorDados {

    public function converterEmObjeto( string $nomeClasse, array $dados ){
        $classe = new $nomeClasse();
        if( ! $classe instanceof Model ){
            return;
        }

        $reflection = new ReflectionClass( $classe );
        $atributos = $reflection->getProperties();
        foreach( $atributos as $atributo ){
            $nomeAtributo = $atributo->getName();
            if( ! isset( $dados[ $nomeAtributo ] ) ){
                continue;
            }

            $tipoAtributo = $atributo->getType();
            if( $tipoAtributo ){
                $metodo = 'set' . ucfirst( $nomeAtributo );
                if( method_exists( $classe, $metodo ) ){
                    if( ! $tipoAtributo->isBuiltin() ){
                        $nomeTipo = $tipoAtributo->getName();
                        $objeto = new $nomeTipo();
                        if( $objeto instanceof Model ){
                            $objeto = $this->converterEmObjeto( $nomeTipo, $dados[ $nomeAtributo ] );
                            $classe->$metodo( $objeto );
                        } elseif( $objeto instanceof DateTime ){
                            $classe->$metodo( DateTime::createFromFormat( 'Y-m-d', $dados[$nomeAtributo] ) );
                        }
                    } else {
                        $classe->$metodo( $dados[$nomeAtributo] );
                    }
                }
            }
        }

        return $classe;
    }

    public function converterEmArray( Model $classe ) {
        $array = [];

        $reflection = new ReflectionClass( $classe );
        $atributos = $reflection->getProperties();
        foreach( $atributos as $atributo ){
            $nomeAtributo = $atributo->getName();
            $tipoAtributo = $atributo->getType();
            if( $tipoAtributo ){
                $metodo = 'get' . ucfirst( $nomeAtributo );
                if( method_exists( $classe, $metodo ) ){
                    if( ! $tipoAtributo->isBuiltin() ){
                        $nomeTipo = $tipoAtributo->getName();
                        $objeto = new $nomeTipo();
                        if( $objeto instanceof Model ){
                            $array[ $nomeAtributo ] = $classe->$metodo()->getId();
                        } elseif( $objeto instanceof DateTime ){
                            $array[ $nomeAtributo ] = $classe->$metodo( 'Y-m-d H:i:s' );
                        }
                    } else {
                        $array[ $nomeAtributo ] = $classe->$metodo();
                    }
                }
            }
        }

        return $array;
    }
}
