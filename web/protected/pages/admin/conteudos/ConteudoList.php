<?php
class ConteudoList extends TPage
{
    /**
     *
     * This method is invoked by the framework when initializing the page
     * @param mixed event parameter
     */
    public function onInit($param)
    {
        parent::onInit($param);

        $this->ConteudoGrid->DataSource = ConteudoRecord::finder()->findAll();
        $this->ConteudoGrid->dataBind();
    }

    /**
     * Deletes a specified record.
     * This method responds to the datagrid's OnDeleteCommand event.
     * @param TDataGrid the event sender
     * @param TDataGridCommandEventParameter the event parameter
     */
    public function deleteButtonClicked($sender, $param)
    {
        $item = $param->Item;
        $id = $this->ConteudoGrid->DataKeys[$item->ItemIndex];
        ConteudoRecord::finder()->deleteByConteudoAndFixo($id, 0);
        $this->Response->redirect($this->Service->constructUrl("admin.conteudos.ConteudoList"));
    }

    public function editButtonClicked($sender, $param)
    {
        $item = $param->Item;
        $id = $this->ConteudoGrid->DataKeys[$item->ItemIndex];
        $this->Response->redirect($this->Service->constructUrl("admin.conteudos.ConteudoForm", array("action"=>"edit", "id"=>$id)));
    }

    public function itemCreated($sender, $param)
    {
        $item = $param->Item;
        if ($item->ItemType === 'Item' || $item->ItemType === 'AlternatingItem')
        {
            $item->DataItem->nome = htmlentities($item->DataItem->nome);
            $item->DataItem->titulo = htmlentities($item->DataItem->titulo);

            if($item->DataItem->fixo == 1)
            {
                $item->btcDelete->Enabled = false;
                $item->btcDelete->CssClass = "delete-disabled";
            }
        }
    }
}
?>