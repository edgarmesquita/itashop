<?php
class AplicacaoRecord extends TActiveRecord {
    const TABLE = 'aplicacoes';

    public $aplicacao;
    public $nome;

    /**
     * @return TActiveRecord active record finder instance
     */
    public static function finder($className=__CLASS__) {
        return parent::finder($className);
    }
}
?>
