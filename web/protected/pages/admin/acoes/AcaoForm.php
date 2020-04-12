<?php
class AcaoForm extends TPage
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

                $this->vAplicacao->Text = utf8_encode($item->aplicacao->nome);
                $this->vNome->Text = utf8_encode($item->nome);
                $this->vDescricao->Text = utf8_encode($item->descricao);
                $this->vUrl->Text = $item->url;
                $this->vPosicao->Text = $item->posicao;
                $this->vMenu->Text = $item->menu==1?'Sim':'Não';
                $this->vAtivo->Text = $item->ativo==1?'Ativo':'Inativo';
                $this->vRestrito->Text = $item->restrito==1?'Sim':'Não';

                if(!empty($item->conteudo))
                {
                    $this->ConteudoId->Value = $item->conteudo->conteudo;
                    $this->vConteudo->Text = utf8_encode($item->conteudo->nome);
                }

                if(!empty($item->acaopai))
                {
                    $this->AcaoPaiId->Value = $item->acaopai->acao;
                    $this->vAcaoPai->Text = utf8_encode($item->acaopai->nome);
                }

                break;
            case 'edit' :
                $item = $this->Post;
                $this->id = $this->Request['id'];

                $this->pnView->Visible = false;
                $this->pnForm->Visible = true;

                $this->ddlAplicacao->SelectedValue = $item->aplicacao->aplicacao;
                $this->fNome->Text = utf8_encode($item->nome);
                $this->fDescricao->Text = utf8_encode($item->descricao);
                $this->fUrl->Text = $item->url;
                $this->fPosicao->Text = $item->posicao;

                if(!empty($item->conteudo))
                {
                    $this->ConteudoId->Value = $item->conteudo->conteudo;
                    $this->fConteudo->Text = utf8_encode($item->conteudo->nome);
                }

                if(!empty($item->acaopai))
                {
                    $this->AcaoPaiId->Value = $item->acaopai->acao;
                    $this->fAcaoPai->Text = utf8_encode($item->acaopai->nome);
                }

                $this->fAtivo->SelectedValue = $item->ativo;
                $this->fRestrito->SelectedValue = $item->restrito;
                
                $this->fMenu->SelectedValue = $item->menu;
                break;
            default :
                $this->pnView->Visible = false;
                $this->pnForm->Visible = true;
                $this->fAtivo->Items[0]->Selected = true;
                break;
        }
    }

    /**
     * Returns the post data to be editted.
     * @return PostRecord the post data to be editted.
     * @throws THttpException if the post data is not found.
     */
    protected function getPost()
    {
        $id = ( int ) $this->Request['id'];
        $record = AcaoRecord::finder()->withAplicacao()->withAcaoPai()->withConteudo()->findByPk($id);
        if ($record === null)
            throw new THttpException(500, 'Item não encontrado.');
        return $record;
    }

    public function salvarClick($sender, $param)
    {
        if ($this->IsValid) // when all validations succeed
        {
            if ($this->Request['action'] == 'edit')
                $item = $this->Post;
            else
                $item = new AcaoRecord();

            $item->aplicacao = $this->ddlAplicacao->SelectedValue;
            $item->nome = htmlentities(utf8_decode($this->fNome->Text));
            $item->descricao = htmlentities(utf8_decode($this->fDescricao->Text));
            $item->url = $this->fUrl->Text;
            $item->posicao = $this->fPosicao->Text;
            $item->ativo = $this->fAtivo->SelectedValue;
            $item->restrito = $this->fRestrito->SelectedValue;
            $item->menu = $this->fMenu->SelectedValue;
            
            $conteudo = $this->ConteudoId->Value;
            if(!empty($conteudo))
                $item->conteudo = $conteudo;
            else
                $item->conteudo = null;

            $acaopai = $this->AcaoPaiId->Value;
            if(!empty($acaopai))
                $item->acaopai = $acaopai;
            else
                $item->acaopai = null;
            
            $item->save();

            if(empty($item->acao)) $url = $this->Service->constructUrl('admin.acoes.AcaoList');
            else $url = $this->Service->constructUrl('admin.acoes.AcaoForm', array('action' => 'view', 'id' => $item->acao));
            $this->Response->redirect($url);
        }
    }
}
?>