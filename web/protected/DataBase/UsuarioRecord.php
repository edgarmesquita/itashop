<?php
class UsuarioRecord extends TActiveRecord
{
    const TABLE = 'usuarios';

    public $usuario;
    public $nome;
    public $email;
    public $apelido;
    public $senha;
    public $telefone;
    public $celular;
    public $nascimento;
    public $sexo;
    public $cidade;
    public $estado;
    public $grupo;
    public $permissao;

    /**
     * @return TActiveRecord active record finder instance
     */
    public static function finder($className=__CLASS__)
    {
        return parent::finder($className);
    }

}
?>