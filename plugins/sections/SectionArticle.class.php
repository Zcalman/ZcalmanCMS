<?PHP
class SectionArticle
{
	private $result;
	private $slug;
	private $sql;
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
			$this->slug = safeText(OPT2); 
			if(!empty($this->slug))
			{
				$this->sql = mysql_query("SELECT * FROM sections_news WHERE slug = '{$this->slug}' AND section_id = {$this->sectionid} LIMIT 1");
				$this->result = mysql_fetch_assoc($this->sql);
			}
			else
			{
				code_error(404);
			}
		}
		else
		{
			code_error(404);
		}
	}
	
	public function have_article()
	{
		if(!empty($this->result))
		{
			if(mysql_num_rows($this->sql) == 1)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		return false;
	}
	
	public function article_title()
	{
		echo $this->result['title'];
	}
	
	public function get_article_title()
	{
		return $this->result['title'];
	}
	
	public function article_content($short = false, $length = 100)
	{
		$text = stripslashes($this->result['text']);
		if($short)
		{
			$text = cutText(strip_tags($text, "<br />"), $length);
		}
		echo $text;
	}
	
	public function get_article_content($short = false, $length = 100)
	{
		$text = stripslashes($this->result['text']);
		if($short)
		{
			$text = cutText(strip_tags($text, "<br />"), $length);
		}
		return $text;
	}
	
	public function article_date($format = "j/n - Y H:i")
	{
		echo splitToDate($this->result['date'] . $this->result['time'], $format);
	}
	
	public function get_article_date($format = "j/n - Y H:i")
	{
		return splitToDate($this->result['date'] . $this->result['time'], $format);
	}
}
?>