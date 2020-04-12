<?php
class CategoriaRecord extends TActiveRecord
{
	const TABLE = 'categorias';
	
	public $categoria;
	public $nome;
	public $descricao;
	public $categoriaPai;
	public $posicao;
	public $ativo;
	
	public $pai;
	public $subItens=array();
	
	public static $RELATIONS=array
    (
        'pai' => array(self::BELONGS_TO, 'CategoriaRecord', 'categoriaPai'),
    	'subItens' => array(self::HAS_MANY, 'CategoriaRecord', 'categoriaPai')
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