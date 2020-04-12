<?php
class NoticiaForm extends TPage
{
    public $id;
    public $categorias = array();
    
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

                $this->vNome->Text = htmlentities($item->nome);
                $this->vTitulo->Text = htmlentities($item->titulo);
                $this->vIntroducao->Text = $item->introducao;
                $this->vTexto->Text = $item->texto;

                if(!empty($item->imagemintroducao))
                {
                    $this->ImagemIntroducaoId->Value = $item->imagemIntroducao->arquivo;
                    $this->vImagemIntroducao->Text = $item->imagemIntroducao->nome;
                }
                else
                    $this->vImagemIntroducao->Text = "- - -";

                if(!empty($item->imagemTexto))
                {
                    $this->ImagemTextoId->Value = $item->imagemTexto->arquivo;
                    $this->vImagemTexto->Text = $item->imagemTexto->nome;
                }
                else
                    $this->vImagemTexto->Text = "- - -";
                
                if(!empty($item->publicarEm))
                    $this->vDataPublicacao->Text = date("d/m/Y", strtotime($item->publicarEm));
                else
                    $this->vDataPublicacao->Text = "- - -";

                if(!empty($item->expirarEm))
                    $this->vDataExpiracao->Text = date("d/m/Y", strtotime($item->expirarEm));
                else
                    $this->vDataExpiracao->Text = "- - -";

                break;
            case 'edit' :
                $item = $this->Post;
                $this->id = $this->Request['id'];

                $this->pnView->Visible = false;
                $this->pnForm->Visible = true;

                $this->loadCategorias();

                $this->fNome->Text = htmlentities($item->nome);
                $this->fTitulo->Text = htmlentities($item->titulo);
                $this->fIntroducao->Text = $item->introducao;
                $this->fTexto->Text = $item->texto;

                if(!empty($item->categoria))
                    $this->ddlCategoria->SelectedValue = $item->categoria;
                
                if(!empty($item->imagemIntroducao))
                {
                    $this->ImagemIntroducaoId->Value = $item->imagemIntroducao->arquivo;
                    $this->fImagemIntroducao->Text = $item->imagemIntroducao->nome;
                }
                $this->fImagemIntroducao->ReadOnly = true;

                if(!empty($item->imagemTexto))
                {
                    $this->ImagemTextoId->Value = $item->imagemTexto->arquivo;
                    $this->fImagemTexto->Text = $item->imagemTexto->nome;
                }
                $this->fImagemTexto->ReadOnly = true;

                foreach($this->rblDestaque->Items as $destaqueItem)
                {
                    if($destaqueItem->Value == $item->destaque)
                        $destaqueItem->Selected = true;
                }

                if(!empty($item->publicarem))
                    $this->fDataPublicacao->Text = date("d/m/Y", strtotime($item->publicarEm));

                if(!empty($item->expirarem))
                    $this->fDataExpiracao->Text = date("d/m/Y", strtotime($item->expirarEm));

                break;
            default :
                $this->loadCategorias();
                $this->rblDestaque->Items[1]->Selected = true;
                $this->pnView->Visible = false;
                $this->pnForm->Visible = true;
                break;
        }
    }

    private function loadCategorias()
    {
        $criteria = new TActiveRecordCriteria;
        $criteria->Condition = 'ativo = 1';
        $criteria->OrdersBy['nome'] = 'asc';
        
        $datasource = array("0" => "« Nenhuma »");
        $categorias = CategoriaRecord::finder()->findAll($criteria);

        foreach($categorias as $c)
            $datasource[$c->categoria] = $c->nome;
        
        $this->ddlCategoria->DataSource = $datasource;
        $this->ddlCategoria->dataBind();


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
        $record = NoticiaRecord::finder()->findByPk($id);
        if(!empty($record->imagemIntroducao))
            $record->imagemIntroducao = ArquivoRecord::finder()->findByPk($record->imagemIntroducao);
        if(!empty($record->imagemTexto))
            $record->imagemTexto = ArquivoRecord::finder()->findByPk($record->imagemTexto);

        if ($record === null)
            throw new THttpException(500, 'Item não encontrado.');
        return $record;
    }

    public function salvarClick($sender, $param)
    {
        if ($this->IsValid) // when all validations succeed
        {
            if ($this->Request['action'] == 'edit')
            {
                $item = $this->Post;
                $item->alteradoEm = date("Y-m-d H:i:s");
                $item->alteradoPor = $_SESSION['usuario']->usuario;
            }
            else
            {
                $item = new NoticiaRecord();
                $item->criadoEm = date("Y-m-d H:i:s");
                $item->criadoPor = $_SESSION['usuario']->usuario;
            }

            $item->nome = $this->fNome->Text;
            $item->titulo = $this->fTitulo->Text;
            $item->introducao = $this->fIntroducao->Text;
            $item->texto = $this->fTexto->Text;

            $imagemintroducao = $this->ImagemIntroducaoId->Value;
            if(!empty($imagemintroducao))
                $item->imagemIntroducao = $imagemintroducao;
            else
                $item->imagemIntroducao = null;

            $imagemtexto = $this->ImagemTextoId->Value;
            if(!empty($imagemtexto))
                $item->imagemTexto = $imagemtexto;
            else
                $item->imagemTexto = null;

            $item->destaque = $this->rblDestaque->SelectedValue;

            $publicarem = $this->fDataPublicacao->Text;
            $expirarem = $this->fDataExpiracao->Text;

            if(!empty($publicarem))
                $item->publicarEm = date("Y-m-d", $this->fDataPublicacao->TimeStamp);
            if(!empty($expirarem))
                $item->expirarEm = date("Y-m-d", $this->fDataExpiracao->TimeStamp);

            $categoria = $this->ddlCategoria->SelectedValue;
            if(!empty($categoria))
                $item->categoria = $categoria;
            
            $item->save();

            if(empty($item->noticia)) $url = $this->Service->constructUrl('admin.Noticias.NoticiaList');
            else $url = $this->Service->constructUrl('admin.noticias.NoticiaForm', array('action' => 'view', 'id' => $item->noticia));
            $this->Response->redirect($url);
        }
    }
}
?>