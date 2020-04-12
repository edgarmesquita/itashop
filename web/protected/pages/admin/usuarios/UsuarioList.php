<?php
class UsuarioList extends TPage
{
    /**
     *
     * This method is invoked by the framework when initializing the page
     * @param mixed event parameter
     */
    public function onInit($param)
    {
        parent::onInit($param);

        $this->UsuarioGrid->DataSource = UsuarioRecord::finder()->findAll();
        $this->UsuarioGrid->dataBind();
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
        $id = $this->UsuarioGrid->DataKeys[$item->ItemIndex];
        UsuarioRecord::finder()->deleteByPk($id);
        $this->Response->redirect($this->Service->constructUrl("admin.usuarios.UsuarioList"));
    }

    public function editButtonClicked($sender, $param)
    {
        $item = $param->Item;
        $id = $this->UsuarioGrid->DataKeys[$item->ItemIndex];
        $this->Response->redirect($this->Service->constructUrl("admin.usuarios.UsuarioForm", array("action"=>"edit", "id"=>$id)));
    }

    public function itemCreated($sender, $param)
    {
        $item = $param->Item;
        if ($item->ItemType === 'Item' || $item->ItemType === 'AlternatingItem')
        {
            if($item->DataItem->apelido == 'admin')
            {
                $item->btDelete->Enabled = false;
            }
            //$item->DataItem->nome = htmlentities($item->DataItem->nome);
        }
    }
}
?>