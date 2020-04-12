<?php
class ConteudoForm extends TPage
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

                $this->vNome->Text = $item->nome;
                $this->vTitulo->Text = $item->titulo;
                $this->vIntroducao->Text = $item->introducao;
                $this->vTexto->Text = $item->texto;

                if(!empty($item->imagemintroducao))
                {
                    $this->ImagemIntroducaoId->Value = $item->imagemintroducao->arquivo;
                    $this->vImagemIntroducao->Text = $item->imagemintroducao->nome;
                }
                if(!empty($item->imagemtexto))
                {
                    $this->ImagemTextoId->Value = $item->imagemtexto->arquivo;
                    $this->vImagemTexto->Text = $item->imagemtexto->nome;
                }
                if(!empty($item->conteudopai))
                {
                    $this->ConteudoPaiId->Value = $item->conteudopai->conteudo;
                    $this->vConteudoPai->Text = $item->conteudopai->titulo;
                }
                $this->vFixo->Text = $item->fixo==1?'Sim':'Não';
                break;
            case 'edit' :
                $item = $this->Post;
                $this->id = $this->Request['id'];

                $this->pnView->Visible = false;
                $this->pnForm->Visible = true;

                $this->fNome->Text = $item->nome;
                $this->fTitulo->Text = $item->titulo;
                $this->fIntroducao->Text = $item->introducao;
                $this->fTexto->Text = $item->texto;

                if(!empty($item->imagemintroducao))
                {
                    $this->ImagemIntroducaoId->Value = $item->imagemintroducao->arquivo;
                    $this->fImagemIntroducao->Text = $item->imagemintroducao->nome;
                }
                $this->fImagemIntroducao->ReadOnly = true;

                if(!empty($item->imagemtexto))
                {
                    $this->ImagemTextoId->Value = $item->imagemtexto->arquivo;
                    $this->fImagemTexto->Text = $item->imagemtexto->nome;
                }
                $this->fImagemTexto->ReadOnly = true;

                if(!empty($item->conteudopai))
                {
                    $this->ConteudoPaiId->Value = $item->conteudopai->conteudo;
                    $this->fConteudoPai->Text = $item->conteudopai->titulo;
                }

                foreach($this->rblDestaque->Items as $destaqueItem)
                {
                    if($destaqueItem->Value == $item->destaque)
                        $destaqueItem->Selected = true;
                }

                $this->fFixo->SelectedValue = $item->fixo;
                break;
            default :
                $this->rblDestaque->Items[1]->Selected = true;
                $this->pnView->Visible = false;
                $this->pnForm->Visible = true;
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
        // the ID of the post to be editted is passed via GET parameter 'id'
        $id = ( int ) $this->Request['id'];
        // use Active Record to look for the specified post ID
        $record = ConteudoRecord::finder()->withConteudoPai()->findByPk($id);
        if(!empty($record->imagemintroducao))
            $record->imagemintroducao = ArquivoRecord::finder()->findByPk($record->imagemintroducao);
        if(!empty($record->imagemtexto))
            $record->imagemtexto = ArquivoRecord::finder()->findByPk($record->imagemtexto);

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
                $item = new ConteudoRecord();

            $item->nome = $this->fNome->Text;
            $item->titulo = $this->fTitulo->Text;
            $item->introducao = $this->fIntroducao->Text;
            $item->texto = $this->fTexto->Text;

            $imagemintroducao = $this->ImagemIntroducaoId->Value;
            if(!empty($imagemintroducao))
                $item->imagemintroducao = $imagemintroducao;
            else
                $item->imagemintroducao = null;

            $imagemtexto = $this->ImagemTextoId->Value;
            if(!empty($imagemtexto))
                $item->imagemtexto = $imagemtexto;
            else
                $item->imagemtexto = null;

            $conteudopai = $this->ConteudoPaiId->Value;
            if(!empty($conteudopai))
                $item->conteudopai = $conteudopai;
            else
                $item->conteudopai = null;

            $item->destaque = $this->rblDestaque->SelectedValue;
            $item->fixo = $this->fFixo->SelectedValue;
            
            $item->save();

            if(empty($item->conteudo)) $url = $this->Service->constructUrl('admin.conteudos.ConteudoList');
            else $url = $this->Service->constructUrl('admin.conteudos.ConteudoForm', array('action' => 'view', 'id' => $item->conteudo));
            $this->Response->redirect($url);
        }
    }
}
?>