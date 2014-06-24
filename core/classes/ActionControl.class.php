<?PHP
class ActionControl
{
	public $actions;
	public $where;
	
	function __construct($where)
	{
		$this->where = $where;
		$this->actions = array();
	}
	
	function add_action($action)
	{
		array_push($this->actions, $action);
	}
}
?>