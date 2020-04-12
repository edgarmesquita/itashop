<?php
class UsuarioForm extends TPage
{
    public $id;
    public $grupo = 1;

    private $acoes = array();
    private $permissoes = array();

    private $acao;
    /**
     *
     * This method is invoked by the framework when initializing the page
     * @param mixed event parameter
     */
    public function onInit($param)
    {
        parent::onInit($param);

        $criteria = new TActiveRecordCriteria;
        $criteria->Condition = 'grupo <> 0';
        $criteria->OrdersBy['nome'] = 'asc';

        $this->ddlGrupo->DataTextField='nome';
        $this->ddlGrupo->DataValueField='grupo';
        $this->ddlGrupo->DataSource = GrupoRecord::finder()->findAll($criteria);
        $this->ddlGrupo->dataBind();

        $this->acao = $this->Request['action'];
        switch ( $this->acao )
        {
            case 'view' :
                $item = $this->Post;
                $this->id = $this->Request['id'];

                $this->pnView->Visible = true;
                $this->pnForm->Visible = false;

                $this->vNome->Text = $item->nome;
                $this->vLogin->Text = $item->apelido;

                $this->grupo = $item->grupo;
                break;
            case 'edit' :
                $item = $this->Post;
                $this->id = $this->Request['id'];

                $this->pnView->Visible = false;
                $this->pnForm->Visible = true;

                $this->fNome->Text = $item->nome;
                $this->fLogin->Text = $item->apelido;
                $this->fSenha->Text = $item->senha;
                if($item->apelido == 'admin')
                    $this->fLogin->ReadOnly = true;

                $this->grupo = $item->grupo;
                $this->rfvSenha->Enabled = false;
                break;
            default :
                $this->pnView->Visible = false;
                $this->pnForm->Visible = true;

                $this->rfvSenha->Enabled = true;
                break;
        }
    }

    public function onLoad($param)
    {
        parent::onLoad($param);

        $this->permissoesDataBind();
    }

    private function permissoesDataBind()
    {
        $criteria = new TActiveRecordCriteria;
        $criteria->Condition = "menu = 1 AND acaopai IS NULL";

        $this->rptPermissoes->DataSource = AcaoRecord::finder()->findAll($criteria);
        $this->rptPermissoes->dataBind();
    }

    protected function ddlGrupoTextChanged($sender, $param)
    {
        $this->grupo = $this->ddlGrupo->SelectedValue;
        $this->permissoesDataBind();
    }
    protected function repeaterDataBound($sender, $param)
    {
        $item = $param->Item;
        if($item->ItemType==='AlternatingItem')
        {
            $item->trPermissao->CssClass = 'alt';
        }
        if($item->ItemType==='Item' || $item->ItemType==='AlternatingItem')
        {
            $c = new TActiveRecordCriteria;
            $c->Condition = "acao = :acao AND usuario = :usuario";
            $c->Parameters[':acao'] = $item->DataItem->acao;
            $c->Parameters[':usuario'] = $this->id;
            $permissao = PermissaoRecord::finder()->find($c);

            if($permissao != null)
            {
                $item->ckbVisualiza->Checked = $permissao->visualiza;
                $item->ckbInsere->Checked = $permissao->insere;
                $item->ckbAtualiza->Checked = $permissao->atualiza;
                $item->ckbExclui->Checked = $permissao->exclui;
                $item->ckbImporta->Checked = $permissao->importa;
                $item->ckbExporta->Checked = $permissao->exporta;
                $item->ckbImprime->Checked = $permissao->imprime;
                $item->ckbEspecial->Checked = $permissao->especial;
            }
            else
            {
                $c = new TActiveRecordCriteria;
                $c->Condition = "acao = :acao AND grupo = :grupo";
                $c->Parameters[':acao'] = $item->DataItem->acao;
                $c->Parameters[':grupo'] = $this->grupo;
                $permissao = PermissaoRecord::finder()->find($c);

                if($permissao != null)
                {
                    $item->ckbVisualiza->Checked = $permissao->visualiza;
                    $item->ckbInsere->Checked = $permissao->insere;
                    $item->ckbAtualiza->Checked = $permissao->atualiza;
                    $item->ckbExclui->Checked = $permissao->exclui;
                    $item->ckbImporta->Checked = $permissao->importa;
                    $item->ckbExporta->Checked = $permissao->exporta;
                    $item->ckbImprime->Checked = $permissao->imprime;
                    $item->ckbEspecial->Checked = $permissao->especial;
                }
            }
            $item->ltAcao->Text = $item->DataItem->nome;
            $item->hdfAcao->Value = $item->DataItem->acao;
        }
    }

    protected function grupoDataBound($sender, $param)
    {
        $items = $sender->Items;
        foreach($items as $item)
        {
            $item->Text = $item->Text;
        }
    }

    /**
     * Returns the post data to be editted.
     * @return PostRecord the post data to be editted.
     * @throws THttpException if the post data is not found.
     */
    protected function getPost()
    {
        // the ID of the post to be editted is passed via GET parameter 'id'
        $id = ( int ) $this->Request['id'];
        // use Active Record to look for the specified post ID
        $record = UsuarioRecord::finder()->findByPk($id);
        if ($record === null)
            throw new THttpException(500, 'Item não encontrado.');
        return $record;
    }

    public function salvarClick($sender, $param)
    {
        if ($this->IsValid) // when all validations succeed

        {
            $senha = $this->fSenha->Text;
            if ($this->Request['action'] == 'edit')
            {
                $item = $this->Post;
                if(trim($senha) != "")
                    $item->senha = md5($senha);
            }
            else
            {
                $item = new UsuarioRecord();
                $item->senha = md5($senha);
            }

            $item->nome = $this->fNome->Text;
            $item->apelido = $this->fLogin->Text;

            $item->email = '';
            $item->telefone = '';
            $item->celular = '';
            $item->nascimento = '';
            $item->sexo = '';
            $item->cidade = '';
            $item->estado = '';
            $item->grupo = $this->ddlGrupo->SelectedValue;

            $item->save();

            foreach($this->rptPermissoes->Items as $p)
            {
                $c = new TActiveRecordCriteria;
                $c->Condition = "acao = :acao AND usuario = :usuario";
                $c->Parameters[':acao'] = $p->hdfAcao->Value;
                $c->Parameters[':usuario'] = $item->usuario;

                $permissao = PermissaoRecord::finder()->find($c);

                if($permissao == null)
                {
                    $permissao = new PermissaoRecord();
                    $permissao->acao = $p->hdfAcao->Value;
                    $permissao->usuario = $item->usuario;
                    $permissao->grupo = $item->grupo;
                }

                $permissao->visualiza = ($p->ckbVisualiza->Checked ? 1 : 0);
                $permissao->insere = ($p->ckbInsere->Checked ? 1 : 0);
                $permissao->atualiza = ($p->ckbAtualiza->Checked ? 1 : 0);
                $permissao->exclui = ($p->ckbExclui->Checked ? 1 : 0);
                $permissao->importa = ($p->ckbImporta->Checked ? 1 : 0);
                $permissao->exporta = ($p->ckbExporta->Checked ? 1 : 0);
                $permissao->imprime = ($p->ckbImprime->Checked ? 1 : 0);
                $permissao->especial = ($p->ckbEspecial->Checked ? 1 : 0);

                $permissao->save();
            }
            if(empty($item->usuario)) $url = $this->Service->constructUrl('admin.usuarios.UsuarioList');
            else $url = $this->Service->constructUrl('admin.usuarios.UsuarioForm', array('action' => 'view', 'id' => $item->usuario));
            $this->Response->redirect($url);
        }
    }
}
?>