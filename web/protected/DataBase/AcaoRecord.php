<?php
class AcaoRecord extends TActiveRecord {
    const TABLE = 'acoes';

    public $acao;
    public $aplicacao;
    public $nome;
    public $descricao;
    public $menu;
    public $url;
    public $posicao;
    public $acaoPai;

    public $subAcoes;

    public $conteudo;
    public $ativo;
    public $restrito;
    
    public static $RELATIONS=array
    (
        'acaoPai' => array(self::BELONGS_TO, 'AcaoRecord', 'acaoPai'),
        'subAcoes' => array(self::HAS_MANY, 'AcaoRecord', 'acaoPai'),
        'aplicacao' => array(self::BELONGS_TO, 'AplicacaoRecord', 'aplicacao'),
        'conteudo' => array(self::BELONGS_TO, 'ConteudoRecord', 'conteudo')
    );

    /**
     * @return TActiveRecord active record finder instance
     */
    public static function finder($className=__CLASS__) {
        return parent::finder($className);
    }
}
?>