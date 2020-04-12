<?php
Prado::using("Application.Commom");

class NoticiaList extends TPage
{
    /**
     *
     * @param mixed event parameter
     */
    public function onLoadComplete($param)
    {
        parent::onLoadComplete($param);

        $this->NoticiaGrid->DataSource = $this->getData($this->pg->Value-1, $this->NoticiaGrid->PageSize);
        $this->NoticiaGrid->dataBind();

        $this->paging->Text = Commom::paging($this->NoticiaGrid->PageSize, $this->pg->Value, CategoriaRecord::finder()->count(), $this->pg->ClientID, true);
    }

    protected function getData($pageIndex, $pageSize)
    {
        $criteria = new TActiveRecordCriteria;
        $criteria->Limit = $pageSize;
        $criteria->Offset = $pageIndex * $pageSize;

        $datasource = NoticiaRecord::finder()->withAlteradopor()->findAll($criteria);

        return $datasource;
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
        $id = $this->NoticiaGrid->DataKeys[$item->ItemIndex];
        NoticiaRecord::finder()->deleteByPk($id);
        $this->Response->redirect($this->Service->constructUrl("admin.noticias.NoticiaList"));
    }

    public function editButtonClicked($sender, $param)
    {
        $item = $param->Item;
        $id = $this->NoticiaGrid->DataKeys[$item->ItemIndex];
        $this->Response->redirect($this->Service->constructUrl("admin.noticias.NoticiaForm", array("action"=>"edit", "id"=>$id)));
    }
}
?>