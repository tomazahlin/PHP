<?php

class Statistics {
	
	private $Language;
	
	public $Number = array();
	public $Duration = array();
	public $AxisNumbers = array();
	public $AxisTexts = array();
	
	private $TitleX;
	private $TitleY;
	
    private $Table = "statistics";
	
	private $Type;
	private $IDT;
	private $Year;
	private $Month;
	private $Day;
	
	private $Where;
	
	private $Title;
	
	private $Imagefile;
	private $ImagefileFull;
	private $Textfile;
	private $TextfileFull;
	
	private $Folder;
	
	private $Months;
	
	function __construct() {
		
		$this->Language = @$_SESSION['Language'];
		$this->Folder = "source/lib/pchart/data/";
		$this->TitleY = _("Visits");
		
		$this->Months = array(	_("January"), 	_("February"),	_("March"), 
								_("April"),		_("May"), 		_("June"), 
								_("July"), 		_("August"),	_("September"), 
								_("October"),	_("November"), 	_("December"));
		
    }
	
	public function SetType($type, $idt = 1) {
		$this->Type = mysql_real_escape_string($type);
		$this->IDT = intval($idt);
		
		if($this->Type == "all") {
			$this->Where = "";
		}
		elseif($this->Type == "home") {
			$this->Where = " AND Type = 'home'";
		}
		else {
			$this->Where = " AND Type = '" . $this->Type . "' AND IDT = '" . $this->IDT . "'";
		}
	}
	
	public function SetTime($year, $month = NULL, $day = NULL) {
		
		$this->Year  = intval($year);
		$this->Month = intval($month);
		$this->Day   = intval($day);
		
		$prefix = $this->Type . "-" . $this->IDT . "-" . $this->Year . "-" . $this->Month . "-" . $this->Day . "-" . $this->Language . "-" . date("Y-m-d");
		$this->Imagefile = $prefix . ".png";
		$this->Textfile  = $prefix;
		
		$this->ImagefileFull = $this->Folder . $this->Imagefile;
		$this->TextfileFull  = $this->Folder . $this->Textfile . ".map";
		
		if(!$this->IsCached()) {
			if($month == NULL) {
				$this->Title = strval($year);
				$this->AxisTexts = $this->Months;
				for($i=1;$i<=12;$i++) {
					array_push($this->AxisNumbers, $i);	
				}
				$this->TitleX = _("Month");
				$this->QueryYear($year);
			}
			elseif($day == NULL) {
				$this->Title = strval($year) . " / " . $this->Months[intval($month)-1];
				$n = cal_days_in_month(CAL_GREGORIAN, $month, $year);
				for($i=1;$i<=$n;$i++) {
					array_push($this->AxisTexts, strval($i));
					array_push($this->AxisNumbers, $i);	
				}
				$this->TitleX = _("Day");
				$this->QueryMonth($year, $month);
			}
			else {
				$this->Title = strval($year) . " / " . $this->Months[intval($month)-1] . " / " . strval($day);
				for($i=0;$i<=23;$i++) {
					array_push($this->AxisTexts, strval($i) . ":00");
					array_push($this->AxisNumbers, $i);
				}
				$this->TitleX = _("Hour");
				$this->QueryDay($year, $month, $day);
			}
		}
	}
	
	private function QueryDay($year, $month, $day) {
		
		$q = mysql_query("SELECT Year, Month, Day, Hour AS Axis, COUNT(*) AS Number, AVG(Duration) AS Duration FROM " . $this->Table . " WHERE Year = '" . $year . "' AND Month = '" . $month . "' AND Day = '" . $day . "' AND Language = '" . $this->Language . "'" . $this->Where . " GROUP BY Year, Month, Day, Hour");
		$this->FetchData($q);
		
	}
	
	private function QueryMonth($year, $month) {
		
		$q = mysql_query("SELECT Year, Month, Day AS Axis, COUNT(*) AS Number, AVG(Duration) AS Duration FROM " . $this->Table . " WHERE Year = '" . $year . "' AND Month = '" . $month . "' AND Language = '" . $this->Language . "'" . $this->Where . " GROUP BY Year, Month, Day");
		$this->FetchData($q);
		
	}
	
	private function QueryYear($year) {
		
		$q = mysql_query("SELECT Year, Month AS Axis, COUNT(*) AS Number, AVG(Duration) AS Duration FROM " . $this->Table . " WHERE Year = '" . $year . "' AND Language = '" . $this->Language . "'" . $this->Where . " GROUP BY Year, Month");
		$this->FetchData($q);
		
	}
	
	private function FetchData($q) {
		
		$this->Number = array_fill(0, count($this->AxisNumbers), 0);
		$this->Duration = array_fill(0, count($this->AxisNumbers), 0);
		
		if(mysql_num_rows($q) > 0) {
			while($data = mysql_fetch_array($q)) {
				$key = array_search($data['Axis'], $this->AxisNumbers);
				$this->Number[$key] = $data['Number'];
				$this->Duration[$key] = round($data['Duration'],2);
			}
		}
	}
	public function UpdateEntry($diff, $id) {
		
		$diff = intval($diff);
		$max = 60;
		if($diff > $max) { $diff = $max; }
		
		$id = mysql_real_escape_string($id);
		@mysql_query("UPDATE " . $this->Table . " SET Duration = Duration + " . $diff . " WHERE ID = '" . $id . "' LIMIT 1");
		
	}
	
	public function IsCached() {
		return false;
		$image = $this->Folder . $this->Imagefile;
		$text = $this->Folder . $this->Textfile;
		
		if(file_exists($image) && file_exists($text . ".map")) {
			return true;	
		}
		else {
			return false;	
		}
	}
	
	public function Exec() {
		if(!$this->IsCached()) {
			
			$serie = _("Visits");
			
			$MyData = new pData();  
			$MyData->addPoints($this->Number, $serie);
			
			$MyData->Data["Series"][$serie]["Min"] = 0; # Y-axis starts with 0
			
			$MyData->setAxisName(0, $this->TitleY);
			$MyData->addPoints($this->AxisTexts, $this->TitleX);
			$MyData->setSerieDescription($this->TitleX,_("Data"));
			$MyData->setAbscissa($this->TitleX);
			$MyData->setAbscissaName($this->TitleX);
			
			$width = 780;
			$height = 250;
			
			$myPicture = new pImage($width,$height,$MyData);	/* Create the pChart object */
			$myPicture->initialiseImageMap($this->Textfile,IMAGE_MAP_STORAGE_FILE,$this->Textfile, $this->Folder);	/* Set the image map name */
			$myPicture->Antialias = FALSE;																			/* Turn off Antialiasing */
			$Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);	/* Draw the background */
			$myPicture->drawFilledRectangle(0,0,$width,$height,$Settings);
			
			# Draw title
			$myPicture->setFontProperties(array("FontName"=>FONT_PATH."/Forgotte.ttf","FontSize"=>20));
			$myPicture->drawText(60,30,$this->Title,array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMLEFT)); 
			
			/* Overlay with a gradient */
			$Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
			$myPicture->drawGradientArea(0,0,$width,$height,DIRECTION_VERTICAL,$Settings);
			$myPicture->drawRectangle(0,0,$width-1,$height-1,array("R"=>0,"G"=>0,"B"=>0));										/* Add a border to the picture */
			$myPicture->setFontProperties(array("R"=>20,"G"=>20,"B"=>20,"FontName"=>FONT_PATH."/pf_arma_five.ttf","FontSize"=>6));			/* Set the default font */
			$myPicture->setGraphArea(60,40,650,200);																/* Define the chart area */
			
			/* Draw the scale */
			$scaleSettings = array("GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
			$myPicture->drawScale($scaleSettings);
			$myPicture->drawLegend(580,12,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));				/* Write the chart legend */
			$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));						/* Turn on shadow computing */ 
			
			/* Draw the chart */
			$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
			
			
			$Settings = array("RecordImageMap"=>TRUE, "DisplayValues"=>1, "Gradient"=>1);
			$myPicture->drawBarChart($Settings);
			
			/* Draw the data */
			$Sum 		= array_sum($this->Number);
			$Avg		= $MyData->getSerieAverage($serie);
			$Min 		= min($this->Number);
			$Max 		= max($this->Number);
			
			$myPicture->drawThreshold($Avg,array("WriteCaption"=>TRUE,"Caption"=>_("Average")));
			
			$myPicture->drawText(670,50,_("Sum") . " : " . $Sum);
			$myPicture->drawText(670,65,_("Average") . " : " . round($Avg,2));
			$myPicture->drawText(670,80,_("Maximum") . " : " . $Max);
			$myPicture->drawText(670,95,_("Minimum") . " : " . $Min);
			
			# Save to file
			$myPicture->Render($this->Folder . $this->Imagefile);
			
			$this->UpdateImageMap();
		}
		
		return array("Image" => $this->ImagefileFull, "Map" => $this->TextfileFull);
		
	}
	
	private function UpdateImageMap() {
		$contents = file_get_contents($this->TextfileFull);
		$lines = explode("\n", $contents);
		$new_content = "";
		$limit = count($lines)-2;
		for($i = 0; $i<=$limit; $i++) {	
			$values = explode("\x01", $lines[$i]);
			$values[3] = _("Duration (avg.)");
			$values[4] = $this->Duration[$i] . "s";
			$line = implode("\x01",$values);
			$line .= "\n";
			$new_content .= $line;
		}
		file_put_contents($this->TextfileFull, $new_content);
	}
	
}
class Stat {
	
	private $ID;
	
    public $Type;
    public $IDT;
	
    public $Year;
    public $Month;
    public $Day;
    public $Hour;
	
    public $Duration;
	
    public $IP;
	public $Language;
	
    private $Table = "statistics";
	
    function __construct() {
        
		$this->Duration = 0;
		
		$this->Year 	= date("Y");
		$this->Month 	= date("n"); 	# Without leading zero
		$this->Day 		= date("j"); 	# Without leading zero
		$this->Hour 	= date("g"); 	# Without leading zero
		
		$this->IP = $_SERVER['REMOTE_ADDR'];
		$this->Language = @$_SESSION["Language"];
		
    }
	
    public function Save() {
		
		global $website;
		global $user;
		
        if($website->IsLive() && !$user->IsLoggedIn()) {
			
        	if($this->Validate()) {
				
				$cols = array("Type", "IDT", "Year", "Month", "Day", "Hour", "IP", "Language");
				$v = array();
				
				foreach($cols as $column) {
					$val = mysql_real_escape_string($this->$column);
					$val = "'$val'";
					array_push($v, $val);
				}
				
				$c = implode(",", $cols);
				$v = implode(",", $v);
				
				if(mysql_query("INSERT INTO " . $this->Table . " ($c) VALUES ($v)")) {
					$this->ID = mysql_insert_id();
				}
				else {
					if(mysql_errno() === 1062) { // UNIQUE CONSTRAINT
						$this->UpdateID();
					}
				}
			}
        }
		
		if(isset($_SESSION["Statistics"])) {
			$s = new Statistics();
			$s->UpdateEntry(time() - $_SESSION["Statistics"]["Timestamp"], $_SESSION["Statistics"]["ID"]);
			unset($_SESSION["Statistics"]);
		}
		if(!isset($_SESSION["Statistics"])) {
			$_SESSION["Statistics"] = array("Timestamp" => time(), "ID" => $this->ID);
		}
		
    }
    private function Validate() {
		
		$this->IDT = intval($this->IDT);
		
        if(!inRange($this->Year, 2000, 2050)) {
			return false;	
		}
		elseif(!inRange($this->Month, 1, 12)) {
			return false;	
		}
		elseif(!inRange($this->Day, 1, 31)) {
			return false;	
		}
		elseif(!inRange($this->Hour, 0, 23)) {
			return false;	
		}
		elseif(!isIP($this->IP)) {
			return false;	
		}
		else {
			return true;	
		}
    }
	
	private function UpdateID() {
		$q = mysql_query("SELECT ID FROM " . $this->Table . " WHERE Type = '" . $this->Type . "' AND IDT = '" . $this->IDT . "' AND Year = '" . $this->Year . "' AND Month = '" . $this->Month . "' AND Day = '" . $this->Day . "' AND Hour = '" . $this->Hour . "' AND IP = '" . $this->IP . "'");
		if(mysql_num_rows($q) > 0) {
			$data = mysql_fetch_array($q);
			$this->ID = $data['ID'];
		}
	}
}

?>