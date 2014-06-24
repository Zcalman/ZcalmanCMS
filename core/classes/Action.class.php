<?PHP
class Action
{
	public $plugin;
	public $where;
	public $func;
	
	function __construct($plugin, $where, $func)
	{
		$this->func = $func;
		$this->where = $where;
		$this->plugin = $plugin;
	}
}
?>