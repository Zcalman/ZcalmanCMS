<?PHP
class Table
{
	public $numField;
	public $fieldNames;
	public $fieldWidth;
	private $rows = array();
	public $numRows;
	public $totWidth;
	private $onClickFunction;
	private $norowstext;
	
	/**
	* $numField = Numbers of columns
	* $fieldNames = The labels of the columns, seperated with a comma (,)
	* $fieldWidth = Thw width of the columns, seperated with a comma (,)
	* $onClickFunction = The javascript function to call on click, the function get the id
	*/
	function __construct($numField,$fieldNames,$fieldWidth,$onClickFunction = false, $norowstext = "Här var det tomt...")
	{
		$this->totWidth = 0;
		$this->onClickFunction = $onClickFunction;
		$this->numField = $numField;
		$this->fieldNames = explode("|" , $fieldNames, $this->numField);
		$this->fieldWidth = explode("|" , $fieldWidth, $this->numField);
		$this->numRows = 0;
		$this->norowstext = $norowstext;
		foreach($this->fieldWidth as $v)
		{
			$this->totWidth += (int)$v+1;
		}
	}
	
	function printTable()
	{
		echo "<div class=\"table\">";
		echo "<div class=\"titlerow\">";
		for($i = 0; $i < $this->numField; $i++)
		{
			echo "<div class=\"titlefield";
				if($i == ($this->numField -1))
				{
					echo " lastfield";
				}
				echo "\" style=\"width:{$this->fieldWidth[$i]}px\"><div class=\"fieldcontent\">{$this->fieldNames[$i]}</div></div>";
		}
		echo "</div>";
		
		$rowstyles = array("row1", "row2");
		
		$j = 0;
		foreach($this->rows as $r)
		{
			$rs = $j%2;
			echo "<div class=\"row {$rowstyles[$rs]}";
			if($j == ($this->numRows -1))
			{
				echo " lastrow";
			}
			if($this->rows[$j]->marked == true)
			{
				echo " marked";
			}
			echo "\" ";
			if($this->onClickFunction != false)
			{
				echo "onclick='javascript: {$this->onClickFunction}(\"{$this->rows[$j]->id}\");'";
			}
			echo ">";
			for($i = 0; $i < $this->numField; $i++)
			{
				echo "<div class=\"field";
				if($i == ($this->numField -1))
				{
					echo " lastfield";
				}
				if($j == ($this->numRows -1))
				{
					echo " lastrowfield";
				}
				echo "\" style=\"width:{$this->fieldWidth[$i]}px\"><div class=\"fieldcontent\">{$this->rows[$j]->fieldValues[$i]}</div></div>";
			}
			echo "</div>";
			$j++;
		}
		if($this->numRows == 0)
		{
			echo "<div class=\"row1 lastrow\"><div class=\"field lastfield lastrowfield\" style=\"width: 100%;\"><div class=\"fieldcontent italic\">{$this->norowstext}</div></div></div>";
		}
		echo "</div>";
	}
	
	function addRow($id, $values, $marked = false)
	{
		$this->rows[$this->numRows] = new Row($this->numField, $id, $values, $marked);
		$this->numRows++;
	}
}
?>