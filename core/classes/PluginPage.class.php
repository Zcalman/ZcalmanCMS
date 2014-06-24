<?PHP
class PluginPage
{
	public $plugin;
	public $slug;
	public $pfile;
	public $title;
	public $type;
	
	function __construct($plugin, $slug, $file, $title, $type)
	{
		$this->pfile = $file;
		$this->title = $title;
		$this->plugin = $plugin;
		$this->slug = $slug;
		$this->type = $type;
	}
}
?>