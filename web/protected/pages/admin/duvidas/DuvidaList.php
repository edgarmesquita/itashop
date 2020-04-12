<?php
Prado::using("Application.Commom");

class DuvidaList extends TPage
{
	/**
	 * 
	 * @param mixed event parameter
	 */
	public function onLoadComplete($param)
	{
		parent::onLoadComplete($param);
		
		$this->DuvidaGrid->DataSource = $this->getData($this->pg->Value-1, $this->DuvidaGrid->PageSize);
		$this->DuvidaGrid->dataBind();
		
		$this->paging->Text = Commom::paging($this->DuvidaGrid->PageSize, $this->pg->Value, FaqRecord::finder()->count(), $this->pg->ClientID, true);
	}
	
	protected function getData($pageIndex, $pageSize)
    {
    	$criteria = new TActiveRecordCriteria;
    	$criteria->Limit = $pageSize;
    	$criteria->Offset = $pageIndex * $pageSize;
    	
    	$duvidas = FaqRecord::finder()->findAll($criteria);
    	
    	return $duvidas;
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
		$id = $this->DuvidaGrid->DataKeys[$item->ItemIndex];
		FaqRecord::finder()->deleteByPk($id);
		$this->Response->redirect($this->Service->constructUrl("admin.duvidas.DuvidaList"));
	}
	
	public function editButtonClicked($sender, $param)
	{
		$item = $param->Item;
		$id = $this->DuvidaGrid->DataKeys[$item->ItemIndex];
		$this->Response->redirect($this->Service->constructUrl("admin.duvidas.DuvidaForm", array("action"=>"edit", "id"=>$id)));
	}
	
	public function itemCreated($sender, $param)
	{
		$item = $param->Item;
		if ($item->ItemType === 'Item' || $item->ItemType === 'AlternatingItem')
		{
			$dataItem = $item->DataItem;
		}
	}
}
?>