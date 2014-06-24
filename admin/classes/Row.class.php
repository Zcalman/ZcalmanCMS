<?PHP
class Row
{
	public $numField;
	public $fieldValues;
	public $id;
	public $marked;
	
	/**
	* $numField = Numbers of columns
	* $fieldValues = The values of the columns, seperated with a comma (,)
	*/
	function __construct($numField, $id, $fieldValues, $marked)
	{
		$this->numField = $numField;
		$this->id = $id;
		$this->marked = $marked;
		$this->fieldValues = explode("|" , $fieldValues, $this->numField);
	}
}
?>