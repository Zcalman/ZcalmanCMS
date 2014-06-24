<?PHP
class Menu
{
	public $id;
	public $title;
	public $onlyadminaccess;
	public $links;
	
	function __construct($id, $title, $onlyadminaccess)
	{
		$this->id = $id;
		$this->title = $title;
		$this->onlyadminaccess = $onlyadminaccess;
		$this->links = array();
	}
	
	function addLink($title, $link, $locallink)
	{
		array_push($this->links, new MenuLink($title, $link, $locallink));
	}
}
?>