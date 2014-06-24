<?PHP
class CalDay
{
	/* 	
		Written by Zcalman
		Visit zcalman.se!
	*/
	public $toDay = false;
	public $fakeDay;
	public $date;
	public $note = false;
	
	function __construct($date, $fake = false)
	{
		$this->fakeDay = $fake;
		$this->date = $date;
	}
	
	function setToDay()
	{
		$this->toDay = true;
		return true;
	}
	
	function setNote()
	{
		$this->note = true;
		return true;
	}
}
?>