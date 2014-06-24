<?PHP
class PageNav
{
	private $limit;
	private $numLinks;
	private $page;
	private $sql;
	private $sqlcount;
	private $offset;
	private $table;
	public $sqlres;
	private $numrows;
	private $sqlstring;
	private $numPages;
	private $startLink;
	private $stopLink;
	private $stings;
	private $laststring;
	private $nextstring;
	private $link;
	private $afterlink;
	private $clickFunction;
	
	/**
	*/
	function __construct($limit, $numLinks, $page, $table, $clickFunction, $link, $afterlink = "", $sqlstring = "", $strings = "F&ouml;reg&aring;ende,N&auml;sta")
	{
		$this->limit = $limit;
		$this->numLinks = $numLinks;
		$this->page = $page;
		$this->table = $table;
		$this->sqlstring = $sqlstring;
		$this->strings = $strings;
		$this->link = $link;
		$this->afterlink = $afterlink;
		$this->clickFunction = $clickFunction;
		$this->calc();
	}
	
	function calc()
	{
		$this->offset = ($this->page - 1) * $this->limit; 
		$this->sql = "SELECT * FROM ". $this->table . " " . $this->sqlstring . " LIMIT " . $this->offset . ", ". $this->limit . "";
		$this->sqlcount = "SELECT COUNT(*) AS num_rows FROM ". $this->table ." " . $this->sqlstring . "";
		$this->sqlres = mysql_query($this->sql);
		$this->sqlcountres = mysql_query($this->sqlcount);
		$temp = mysql_fetch_array($this->sqlcountres);
		$this->numrows = $temp['num_rows'];
		
		if ($this->numrows > 0 ) 
		{ 
			$this->numPages = (ceil($this->numrows / $this->limit) ); 
		}
		else 
		{
			$this->numPages = 0;
		}
		
		// beräknar startsidan 
		if ($this->numPages > $this->numLinks)
		{ 
			$this->startLink = $this->page - floor($this->numLinks / 2); 
			if ($this->startLink >($this->numPages - $this->numLinks))
			{ 
				$this->startLink = $this->numPages - $this->numLinks + 1; 
			} 
		} 
		else 
		{
			$this->startLink = 1;
		}
		
		// beräknar sista sidan 
		if ($this->startLink < 1)
		{
			$this->startLink = 1;
		}
		$this->stopLink = $this->startLink + $this->numLinks - 1; 
		if ($this->stopLink > $this->numPages) 
		{
			$this->stopLink = $this->numPages;
		}
		
		// fixar textsträngarna
		$temp = explode(",",$this->strings, 2);
		$this->laststring = $temp[0];
		$this->nextstring = $temp[1];
	}
	
	function printNav()
	{
		if($this->numrows > $this->limit)
		{
			echo "<div class=\"pageNav\">";
			if($this->page > 1)
			{
				echo "<div class=\"lastlink\" onclick='javascript: ". $this->clickFunction . "(\"" . $this->link . ($this->page - 1) . $this->afterlink . "\")'><p>". $this->laststring . "</p></div>";
			}
			
			if($this->numPages > 0)
			{
				for ($i = $this->startLink; $i <= $this->stopLink; $i++)
				{ 
					if ($i == $this->page)
					{ 
						echo "<div class=\"currentpagelink\"><p>".$i."</p></div>"; 
					}  
					else 
					{ 
						echo "<div class=\"pagelink\" onclick='javascript: ". $this->clickFunction . "(\"" . $this->link . $i . $this->afterlink . "\")'><p>".$i."</p></div>"; 
					}
				} 
			}
			
			if($this->page < $this->numPages)
			{
				echo "<div class=\"nextlink\" onclick='javascript: ". $this->clickFunction . "(\"" . $this->link . ($this->page + 1) . $this->afterlink . "\")'><p>". $this->nextstring . "</p></div>";
			}
			
			echo "</div>";
		}
	}
}
?>