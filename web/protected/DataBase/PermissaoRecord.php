<?php
class PermissaoRecord extends TActiveRecord
{
    const TABLE = 'permissoes';

    public $permissao;
    public $nome;
    public $visualiza;
    public $insere;
    public $atualiza;
    public $exclui;
    public $importa;
    public $exporta;
    public $imprime;
    public $especial;
    public $grupo;
    public $usuario;
    public $acao;
    public static $RELATIONS = array
    (
        'grupo' => array(self::BELONGS_TO, 'GrupoRecord', 'grupo'),
        'usuario' => array(self::BELONGS_TO, 'UsuarioRecord', 'usuario'),
        'acao' => array(self::BELONGS_TO, 'AcaoRecord', 'acao')
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