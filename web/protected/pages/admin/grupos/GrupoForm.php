<?php
class GrupoForm extends TPage
{
    public $id;
    /**
     *
     * This method is invoked by the framework when initializing the page
     * @param mixed event parameter
     */
    public function onInit($param)
    {
        parent::onInit($param);

        switch ( $this->Request['action'])
        {
            case 'view' :
                $item = $this->Post;
                $this->id = $this->Request['id'];

                $this->pnView->Visible = true;
                $this->pnForm->Visible = false;

                $this->vNome->Text = utf8_encode($item->nome);
                break;
            case 'edit' :
                $item = $this->Post;
                $this->id = $this->Request['id'];

                $this->pnView->Visible = false;
                $this->pnForm->Visible = true;

                $this->fNome->Text = utf8_encode($item->nome);
                break;
            default :
                $this->pnView->Visible = false;
                $this->pnForm->Visible = true;
                break;
        }
    }

    public function onLoad($param)
    {
        parent::onLoad($param);

        $this->permissoesDataBind();
    }
    
    /**
     * Returns the post data to be editted.
     * @return PostRecord the post data to be editted.
     * @throws THttpException if the post data is not found.
     */
    protected function getPost()
    {
        $id = ( int ) $this->Request['id'];
        $record = GrupoRecord::finder()->findByPk($id);
        if ($record === null)
            throw new THttpException(500, 'Item não encontrado.');
        return $record;
    }

    private function permissoesDataBind()
    {
        $criteria = new TActiveRecordCriteria;
        $criteria->Condition = "menu = 1 AND acaoPai IS NULL";

        $this->rptPermissoes->DataSource = AcaoRecord::finder()->findAll($criteria);
        $this->rptPermissoes->dataBind();
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
            $c->Condition = "acao = :acao AND grupo = :grupo";
            $c->Parameters[':acao'] = $item->DataItem->acao;
            $c->Parameters[':grupo'] = $this->id;
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
            $item->ltAcao->Text = $item->DataItem->nome;
            $item->hdfAcao->Value = $item->DataItem->acao;
        }
    }

    public function salvarClick($sender, $param)
    {
        if ($this->IsValid) // when all validations succeed

        {
            if ($this->Request['action'] == 'edit')
                $item = $this->Post;
            else
                $item = new GrupoRecord();

            $item->nome = $this->fNome->Text;
            $item->save();

            if(empty($item->grupo)) $url = $this->Service->constructUrl('admin.Grupos.GrupoList');
            else $url = $this->Service->constructUrl('admin.Grupos.GrupoForm', array('action' => 'view', 'id' => $item->grupo));
            $this->Response->redirect($url);
        }
    }
}
?>