<?PHP
class MenuLink
{
	public $link;
	public $title;
	public $locallink;
	
	function __construct($title, $link, $locallink)
	{
		$this->link = $link;
		$this->title = $title;
		$this->locallink = $locallink;
	}
}
?>