<?php
class NoticiaRecord extends TActiveRecord
{
    const TABLE = 'noticias';

    public $noticia;
    public $nome;
    public $titulo;
    public $introducao;
    public $texto;
    public $imagemIntroducao;
    public $imagemTexto;
    public $destaque;
    public $categoria;
    public $publicarEm;
    public $expirarEm;
    public $criadoEm;
    public $criadoPor;
    public $alteradoEm;
    public $alteradoPor;
    public $excluidoEm;
    public $excluidoPor;
    public static $RELATIONS = array
    (
        'categoria' => array(self::BELONGS_TO, 'CategoriaRecord', 'categoria'),
        'criadoPor' => array(self::BELONGS_TO, 'UsuarioRecord', 'criadoPor'),
        'alteradoPor' => array(self::BELONGS_TO, 'UsuarioRecord', 'alteradoPor'),
        'excluidoPor' => array(self::BELONGS_TO, 'UsuarioRecord', 'excluidoPor')
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