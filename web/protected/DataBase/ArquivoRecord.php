<?php
class ArquivoRecord extends TActiveRecord
{	
	const TABLE = 'arquivos';
	
	public $arquivo;
	public $nome;
	public $tamanho;
	public $extensao;
	
	/**
     * @return TActiveRecord active record finder instance
     */
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }
}
?>