<?PHP
class SectionNewsFeed
{
	public $pagenr;
	public $pageNav;
	private $result;
	public $sectionid;
	public $sectionslug;
	
	function __construct($id, $slug)
	{
		$this->sectionid = $id;
		$this->sectionslug = $slug;
		$this->startup();
	}
	
	private function startup()
	{
		if (defined("OPT2"))
		{ 
			$this->pagenr = (int)mysql_real_escape_string(trim(OPT2)); 
			if($this->pagenr == 0)
			{
				$this->pagenr = 1;
			}
		} 
		else
		{
			$this->pagenr = 1;
		}
		$this->pageNav = new PageNav(15, 10, $this->pagenr, "sections_news", "goto", HOST . $this->sectionslug . "/news/", "", "WHERE section_id = ". $this->sectionid ." ORDER BY id DESC");
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
		echo HOST . $this->sectionslug . "/article/" . $this->result['slug'];
	}
	
	public function get_news_link()
	{
		return HOST . $this->sectionslug . "article/" . $this->result['slug'];
	}
}
?>