<?php
class ConteudoRecord extends TActiveRecord
{
    const TABLE = 'conteudos';

    public $conteudo;
    public $nome;
    public $titulo;
    public $introducao;
    public $texto;
    public $imagemIntroducao;
    public $imagemTexto;
    public $destaque;
    public $conteudoPai;
    public $fixo;

    public $subConteudos=array();

    public static $RELATIONS=array
    (
        'conteudoPai' => array(self::BELONGS_TO, 'ConteudoRecord', 'conteudoPai'),
        'subConteudos' => array(self::HAS_MANY, 'ConteudoRecord', 'conteudoPai')
    );

    /**
     * @return TActiveRecord active record finder instance
     */
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }
}
?>