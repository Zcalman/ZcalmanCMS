<?PHP
class NewsFeed
{
	public $pagenr;
	public $pageNav;
	private $result;
	
	function __construct()
	{
		$this->startup();
	}
	
	private function startup()
	{
		if (defined("OPT1"))
		{ 
			$this->pagenr = (int)mysql_real_escape_string(trim(OPT1)); 
			if($this->pagenr == 0)
			{
				$this->pagenr = 1;
			}
		} 
		else
		{
			$this->pagenr = 1;
		}
		$this->pageNav = new PageNav(15, 10, $this->pagenr, "news", "goto", HOST . "news/", "", "ORDER BY id DESC");
	}
	
	public function print_navigation()
	{
		$this->pageNav->printNav();
	}
	
	public function have_news()
	{
		return $this->result = mysql_fetch_assoc($this->pageNav->sqlres);
	}
	
	public function news_title()
	{
		echo stripslashes($this->result['title']);
	}
	
	public function get_news_title()
	{
		return stripslashes($this->result['title']);
	}
	
	public function news_content($short = false, $length = 100)
	{
		$text = stripslashes($this->result['text']);
		if($short)
		{
			$text = cutText(strip_tags($text, "<br />"), $length);
		}
		echo $text;
	}
	
	public function get_news_content($short = false, $length = 100)
	{
		$text = stripslashes($this->result['text']);
		if($short)
		{
			$text = cutText(strip_tags($text, "<br />"), $length);
		}
		return $text;
	}
	
	public function news_date($format = "j/n - Y H:i")
	{
		echo splitToDate($this->result['date'] . $this->result['time'], $format);
	}
	
	public function get_news_date($format = "j/n - Y H:i")
	{
		return splitToDate($this->result['date'] . $this->result['time'], $format);
	}
	
	public function news_link()
	{
		echo HOST . "article/" . $this->result['slug'];
	}
	
	public function get_news_link()
	{
		return HOST . "article/" . $this->result['slug'];
	}
}
?>