<?PHP
class Calendar
{
	/* 	
		Written by Zcalman
		Visit zcalman.se!
	*/
	public $month;
	public $year;
	public $numDays;
	public $startAt;
	public $firstWeek;
	public $lastMonthDays;
	public $numFakeDays;
	public $premonth;
	public $preyear;
	public $nextmonth;
	public $nextyear;
	private $days = array();
	
	function __construct($month = false, $year = false)
	{
		if(!$month)
		{
			$this->month = date("n");
		}
		else
		{
			$this->month = $month;
		}
		
		if(!$year)
		{
			$this->year = date("Y");
		}
		else
		{
			$this->year = $year;
		}
		$this->init();
	}
	
	function init()
	{
		$this->numDays = date("t", mktime(0,0,0,$this->month,1,$this->year));
		$f = $this->firstDay();
		$this->startAt = $f['day'];
		$this->firstWeek = $f['week'];
		$this->lastMonthDays = $this->lastMonthDays();
		$this->lastMonthStart = $this->lastMonthDays - ($this->startAt-2);
		$this->numFakeDays = $this->startAt-1;
		$k = 1;
		$j = $this->numFakeDays;
		for($i = 1; $i <= ($this->numDays + $this->numFakeDays); $i++)
		{
			if($j > 0)
			{
				$this->days[$i] = new CalDay($this->lastMonthStart + ($i - 1), true);
				$j--;
			}
			else
			{
				$this->days[$i] = new CalDay($k);
				$k++;
			}
		}
		$this->setToDay();
		$this->premonth = date("n", mktime(0,0,0,$this->month-1,1,$this->year));
		$this->preyear = date("Y", mktime(0,0,0,$this->month-1,1,$this->year));
		$this->nextmonth = date("n", mktime(0,0,0,$this->month+1,1,$this->year));
		$this->nextyear = date("Y", mktime(0,0,0,$this->month+1,1,$this->year));
	}
	
	function printCal($string = "V.,Måndag,Tisdag,Onsdag,Torsdag,Fredag,Lördag,Söndag")
	{
		$strings = explode("," , $string, 8);
		echo "<div class=\"calendarContent\">\n";
		echo "\t<div class=\"calendarTop\">\n";
		echo "\t\t<div class=\"weekLabel\">{$strings[0]}</div>\n";
		for($i = 1; $i < 8; $i++)
		{
			echo "\t\t<div class=\"dayName\">{$strings[$i]}</div>\n";
		}
		echo "\t</div>\n";
		$v = 0;
		$day = 1;
		$fakedate = 1;
		for($j = $this->numDays + $this->numFakeDays; $j > 0;)
		{
			if($v == 0)
			{
				$week = $this->firstWeek + $v;
			}
			else
			{
				$week = (int)date("W", mktime(0,0,0,$this->month,$this->days[$day]->date,$this->year));
			}
			echo "\t<div class=\"weekNumber\">{$week}</div>\n";
			for($d = 0; $d < 7; $d++)
			{
				if($day <= $this->numDays + $this->numFakeDays)
				{
					$tempday = $this->days[$day];
					if($tempday->fakeDay)
					{
						echo "\t<div class=\"day fakeDay\" onclick=\"javascript: calendarDayClick({$this->preyear}, {$this->premonth}, {$tempday->date}, 'fakeDay');\">". $tempday->date . "</div>\n";
					}
					else
					{
						if($tempday->note && $tempday->toDay)
						{
							echo "\t<div class=\"day toDayNoteDay\" onclick=\"javascript: calendarDayClick({$this->year}, {$this->month}, {$tempday->date}, 'toDayNoteDay');\">". $tempday->date . "</div>\n";
						}
						elseif($tempday->toDay)
						{
							echo "\t<div class=\"day toDay\" onclick=\"javascript: calendarDayClick({$this->year}, {$this->month}, {$tempday->date}, 'toDay');\">". $tempday->date . "</div>\n";
						}
						elseif($tempday->note)
						{
							echo "\t<div class=\"day noteDay\" onclick=\"javascript: calendarDayClick({$this->year}, {$this->month}, {$tempday->date}, 'noteDay');\">". $tempday->date . "</div>\n";
						}
						else
						{
							echo "\t<div class=\"day regularDay\" onclick=\"javascript: calendarDayClick({$this->year}, {$this->month}, {$tempday->date}, 'regularDay');\">". $tempday->date . "</div>\n";
						}
					}
					$day++;
					$j--;
				}
				else
				{
					echo "\t<div class=\"day fakeDay\" onclick=\"javascript: calendarDayClick({$this->nextyear}, {$this->nextmonth}, {$fakedate}, 'fakeDay');\">". $fakedate . "</div>\n";
					$fakedate++;
				}
					
			}
			$v++;
		}
		echo "</div>\n";
	}
	
	function setToDay()
	{
		$m = date("n");
		$y = date("Y");
		$d = date("j");
		if($m == $this->month && $y == $this->year)
		{
			$this->days[$d+$this->numFakeDays]->setToDay();
		}
	}
	
	function firstDay()
	{
		$timestamp =  mktime(0,0,0,$this->month,1,$this->year);
		$fday = array("day" => date("N", $timestamp), "week" => date("W", $timestamp));
		return $fday;
	}
	
	function lastMonthDays()
	{
		return date("t", mktime(0,0,0,$this->month-1,1,$this->year));
	}
	
	function addNote($day)
	{
		$noteday = $day + $this->numFakeDays;
		$this->days[$noteday]->setNote();
	}
}
?>