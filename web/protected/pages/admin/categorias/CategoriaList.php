<?php
Prado::using("Application.Commom");

class CategoriaList extends TPage
{
	/**
	 * 
	 * @param mixed event parameter
	 */
	public function onLoadComplete($param)
	{
		parent::onLoadComplete($param);
		
		$this->CategoriaGrid->DataSource = $this->getData($this->pg->Value-1, $this->CategoriaGrid->PageSize);
		$this->CategoriaGrid->dataBind();
		
		$this->paging->Text = Commom::paging($this->CategoriaGrid->PageSize, $this->pg->Value, CategoriaRecord::finder()->count(), $this->pg->ClientID, true);
	}
	
	protected function getData($pageIndex, $pageSize)
    {
    	$criteria = new TActiveRecordCriteria;
    	$criteria->Limit = $pageSize;
    	$criteria->Offset = $pageIndex * $pageSize;
    	
    	$categorias = CategoriaRecord::finder()->findAll($criteria);
    	
    	return $categorias;
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
		$id = $this->CategoriaGrid->DataKeys[$item->ItemIndex];
		CategoriaRecord::finder()->deleteByPk($id);
		$this->Response->redirect($this->Service->constructUrl("admin.categorias.CategoriaList"));
	}
	
	public function editButtonClicked($sender, $param)
	{
		$item = $param->Item;
		$id = $this->CategoriaGrid->DataKeys[$item->ItemIndex];
		$this->Response->redirect($this->Service->constructUrl("admin.categorias.CategoriaForm", array("action"=>"edit", "id"=>$id)));
	}
	
	public function itemCreated($sender, $param)
	{
		$item = $param->Item;
		if ($item->ItemType === 'Item' || $item->ItemType === 'AlternatingItem')
		{
			$dataItem = $item->DataItem;
			if(isset($dataItem))
			{
				//$item->DataItem->nome = utf8_encode($item->DataItem->nome);
				//$item->DataItem->descricao = utf8_encode($item->DataItem->descricao);
			}
		}
	}
}
?>