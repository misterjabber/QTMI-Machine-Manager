<?php
include_once "../lib/LinkMaker.php"; 
include_once "../lib/Util.php";

class CustomerMachineHours extends QtmiBaseClass {
    // declare properties
    public $id = -1;       
    public $customer_id = -1;
    public $customer_name = "";
    public $customer_machine_id = -1;
    public $customer_machine_type = "Fusion";
    public $created_on = "";
   	public $turbo_on = "";
   	public $water_chiller_run_time = "";
   	public $glow_hydro_rp_on = "";
   	public $dep_rp_on = "";
   	public $dep_motor_run_time = "";
   	public $rotation_motor_o_ring = "";
   	public $glow_hydro_rp_oil_life_meter = "";
   	public $dep_rough_pump_oil_life = "";
   	public $lens_count = "";
   	public $lens_count_setpoint = "";   	
   	public $machine_on_time = "";
   	
    public $customerMachineContacts;
    public $listFiles;
 	public $code_base = "";
 	public $sort = "DESC";
    
 	private $linkMaker = "";
    private $util = "";
    private $csv_dir = "";
    
    	//Creates a row in the database for this customer hour log
    	//Manual addition (?)
    	public function addCustomerMachineHours() {
		$today = date('y-m-j');
		$this->last_hmi_update = $today;
		$this->last_plc_update = $today;
		
		$query = sprintf("INSERT INTO `hmi_plc_mgr`.`customer_machine_hours` (
		`id` ,
		`customer_id` ,
		`customer_name` ,
		`customer_machine_id` ,
		`customer_machine_type` ,
		`created_on` ,
		`turbo_on` ,
		`water_chiller_run_time` ,
		`glow_hydro_rp_on` ,
		`dep_rp_on` ,
		`dep_motor_run_time` ,
		`rotation_motor_o_ring` ,
		`glow_hydro_rp_oil_life_meter` ,
		`dep_rough_pump_oil_life` ,
		`lens_count` ,
		`lens_count_setpoint` ,
		`machine_on_time` ,
		
		)
		VALUES (
		NULL , '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'
		);", 
		mysql_real_escape_string($this->id),  
		mysql_real_escape_string($this->customer_id), 
		mysql_real_escape_string($this->customer_name), 
		mysql_real_escape_string($this->customer_machine_id),
		mysql_real_escape_string($this->customer_machine_type),		
		mysql_real_escape_string($today), 
		mysql_real_escape_string($this->turbo_on), 
		mysql_real_escape_string($this->water_chiller_run_time), 
		mysql_real_escape_string($this->glow_hydro_rp_on),
		mysql_real_escape_string($this->dep_rp_on),
		mysql_real_escape_string($this->dep_motor_run_time), 
		mysql_real_escape_string($this->rotation_motor_o_ring), 
		mysql_real_escape_string($this->glow_hydro_rp_oil_life_meter), 
		mysql_real_escape_string($this->dep_rough_pump_oil_life),
		mysql_real_escape_string($this->lens_count),
		mysql_real_escape_string($this->lens_count_setpoint),
		mysql_real_escape_string($this->machine_on_time));
		//echo $query;
		mysql_query($query);
	}
	/*
	public function showMachineHours() {
		$today = date('y-m-j');
		//$this->linkMaker->machine_type = $this->customer_machine_type;
		//$this->linkMaker->code_base = $this->code_base;
		
		$rowCounter = 0;
		$query = sprintf("SELECT * FROM `hmi_plc_mgr`.`customer_machine_hours` WHERE `customer_machine_hours`.`customer_name` = '%s' \AND `customer_machine_hours`.`customer_machine_type` = '%s' AND `customer_machine_hours`.`created_on` = '%s' AND `customer_machine_hours`.`turbo_on` = '%s' AND `customer_machine_hours`.`water_chiller_run_time` = '%s' AND `customer_machine_hours`.`glow_hydro_rp_on` = '%s' AND `customer_machine_hours`.`dep_rp_on` = '%s' AND `customer_machine_hours`.`dep_motor_run_time` = '%s' AND `customer_machine_hours`.`rotation_motor_o_ring` = '%s' AND `customer_machine_hours`.`glow_hydro_rp_oil_life_meter` = '%s' AND `customer_machine_hours`.`dep_rough_pump_oil_life` = '%s' AND `customer_machine_hours`.`lens_count` = '%s' AND `customer_machine_hours`.`lens_count_setpoint` = '%s' AND `customer_machine_hours`.`machine_on_time` = '%s' ORDER BY `customer_machine_archive`.`id` %s", mysql_real_escape_string($this->customer_name),mysql_real_escape_string($this->customer_machine_type),  mysql_real_escape_string($today),  mysql_real_escape_string($this->turbo_on),  mysql_real_escape_string($this->water_chiller_run_time),  mysql_real_escape_string($this->glow_hydro_rp_on),  mysql_real_escape_string($this->dep_rp_on),  mysql_real_escape_string($this->dep_motor_run_time),  mysql_real_escape_string($this->rotation_motor_o_ring),  mysql_real_escape_string($this->glow_hydro_rp_oil_life_meter),  mysql_real_escape_string($this->dep_rough_pump_oil_life),  mysql_real_escape_string($this->lens_count),  mysql_real_escape_string($this->lens_count_setpoint),  mysql_real_escape_string($this->machine_on_time), mysql_real_escape_string($this->sort));

		//echo $query;
		echo "<table border=1  style='background:#F3F7F7' >";
				echo "<tr>";
					echo "<td style='font-size:18'><b>Customer</b></td>";
					echo "<td style='font-size:18'><b>Machine</b></td>";
					echo "<td style='font-size:18'><b>Date</b></td>";
					echo "<td style='font-size:18'><b>Turbo Hours</b></td>";
					echo "<td style='font-size:18'><b>Chiller Run Time</b></td>";
					echo "<td style='font-size:18'><b>Glow & Hydroo Roughing Pump Run Time</b></td>";
					echo "<td style='font-size:18'><b>Dep Chamber Roughing Pump Run Time</b></td>";
					echo "<td style='font-size:18'><b>Dep Motor Run Time</b></td>";
					echo "<td style='font-size:18'><b>Rotation Motor O-Ring</b></td>";
					echo "<td style='font-size:18'><b>Glow & Hydroo Roughing Pump Oil Life</b></td>";
					echo "<td style='font-size:18'><b>Dep Roughing Pump Oil Life</b></td>";
					echo "<td style='font-size:18'><b>Lens Count</b></td>";
					echo "<td style='font-size:18'><b>Lens Count Setpoint</b></td>";
					echo "<td style='font-size:18'><b>Machine Run Time</b></td>";					
				echo "</tr>";
		$result = mysql_query($query);
			while ($row = mysql_fetch_assoc($result)) {
				//$this->listFiles->setFile($row['file_id']);		
			
				if($rowCounter % 2 == 0) echo "<tr style='background:#F5E0EB'>";
				else echo "<tr>";
					echo "<td valign=top align=left>".$row['customer_name']."</td>";
					echo "<td valign=top align=left>".$row['customer_machine_type']."</td>";
					echo "<td valign=top align=left>".$row['created_on']."</td>";
					echo "<td valign=top align=left>".$row['turbo_on']."</td>";
					echo "<td valign=top align=left>".$row['water_chiller_run_time']."</td>";
					echo "<td valign=top align=left>".$row['glow_hydro_rp_on']."</td>";
					echo "<td valign=top align=left>".$row['dep_rp_on']."</td>";
					echo "<td valign=top align=left>".$row['dep_motor_run_time']."</td>";
					echo "<td valign=top align=left>".$row['rotation_motor_o_ring']."</td>";
					echo "<td valign=top align=left>".$row['glow_hydro_rp_oil_life_meter']."</td>";
					echo "<td valign=top align=left>".$row['dep_rough_pump_oil_life']."</td>";
					echo "<td valign=top align=left>".$row['lens_count']."</td>";
					echo "<td valign=top align=left>".$row['lens_count_setpoint']."</td>";
					echo "<td valign=top align=left>".$row['machine_on_time']."</td>";
				echo "</tr>";
				$rowCounter++;
			}	
		echo "</table>";
	}
	
	*/

	/*
	public function loadHours() {
		exec('mkdir ../../customers/hours_logs');	
		exec('mkdir ../../customers/hours_logs/'.$this->customer->code);	
		exec('mkdir ../../customers/hours_logs/'.$this->customer->code.'/'.$this->machine_type);	
		$this->csv_dir = '../../customers/hours_logs/'.$this->customer->code.'/'.$this->machine_type.'/';
		$csvFiles = scandir($this->csv_dir, 1);
		
		foreach($csvFiles as $csvFile) 
		{ 
			if($csvFile != "." && $csvFile != ".." ){
				if(!$this->hasFileBeenLogged($csvFile)){
					$csv_array = $this->readHours($csvFile);
					$this->insertHours($csv_array);
					$this->insertLoggedFile($csvFile);
				}
			}
		} 



	}	
*/
	// method declaration
	/*		probably not using this method. 
			commenting in the event that it is useful
	public function hasFileBeenLogged($filename) {
		$returnValue = false;
		
		$query = sprintf("SELECT * FROM `hmi_plc_mgr`.`customer_machine_error_file_log` WHERE `customer_machine_error_file_log`.`filename` = '%s' AND `customer_machine_error_file_log`.`customer_id` = '%s' AND `customer_machine_error_file_log`.`machine_type` = '%s' ",  mysql_real_escape_string($filename), mysql_real_escape_string($this->customer->id), mysql_real_escape_string($this->machine_type));
		//echo $query;
		$result = mysql_query($query);
		while ($row = mysql_fetch_assoc($result)) {
			$returnValue = true;
		}
		return $returnValue;
	}
	 


	// method declaration
	public function insertLoggedFile($filename) {
		$query = sprintf("INSERT IGNORE INTO `hmi_plc_mgr`.`customer_machine_error_file_log` (
			`id` ,
			`customer_id` ,
			`machine_type` ,
			`filename` 
			)
			VALUES (
			NULL , '%s', '%s', '%s'
			);", 
			mysql_real_escape_string($this->customer->id), 
			mysql_real_escape_string($this->machine_type), 
			mysql_real_escape_string($filename));
			//echo $query . "\n\n";
		if(mysql_query($query)) echo "Logged CSV File \n";
	
	}
	*/



/*
	// method declaration
	public function readHours($csvFile) {
		$csv_array = $this->util->csv_to_array($this->csv_dir . $csvFile);
		return $csv_array;
	}	

	// method declaration
	
	public function insertHours($csv_array) {
		foreach ($csv_array as &$value) {
			if($this->areHoursPresent($value['Date']) == 0){
				$query = sprintf("INSERT IGNORE INTO `hmi_plc_mgr`.`customer_machine_hours` (
				`id` ,
				`customer_id` ,
				`customer_name` ,
				`customer_machine_id` ,
				`created_on` ,
				`turbo_on` ,
				`water_chiller_run_time` ,
				`glow_hydro_rp_on` ,
				`dep_rp_on` ,
				`dep_motor_run_time` ,
				`rotation_motor_o_ring` ,
				`glow_hydro_rp_oil_life_meter` ,
				`dep_rough_pump_oil_life` ,
				`lens_count` ,
				`lens_count_setpoint` ,
				`machine_on_time` ,
		
				)
				VALUES (
				NULL , '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'
				);", 
				mysql_real_escape_string($this->id),  
				mysql_real_escape_string($this->customer_id), 
				mysql_real_escape_string($this->customer_name), 
				mysql_real_escape_string($this->customer_machine_id),
				mysql_real_escape_string($today), 
				mysql_real_escape_string($this->turbo_on), 
				mysql_real_escape_string($this->water_chiller_run_time), 
				mysql_real_escape_string($this->glow_hydro_rp_on),
				mysql_real_escape_string($this->dep_rp_on),
				mysql_real_escape_string($this->dep_motor_run_time), 
				mysql_real_escape_string($this->rotation_motor_o_ring), 
				mysql_real_escape_string($this->glow_hydro_rp_oil_life_meter), 
				mysql_real_escape_string($this->dep_rough_pump_oil_life),
				mysql_real_escape_string($this->lens_count));
				mysql_real_escape_string($this->lens_count_setpoint));
				mysql_real_escape_string($this->machine_on_time));
				//echo $query;
				mysql_query($query);
					//echo $query . "\n\n";
				if(mysql_query($query)) echo "inserted!";
			}
		}	
	}	
	
	// method declaration
	public function areHoursPresent($createdDate) {
		$returnValue = 0;	
		$query = sprintf("SELECT * FROM `hmi_plc_mgr`.`customer_machine_hours` WHERE `customer_machine_hours`.`customer_id` = '%s' AND `customer_machine_error`.`created_on_date` = '%s'", mysql_real_escape_string($this->customer->id), mysql_real_escape_string($createdDate));
		//echo $query . "\n\n";
		if($result = mysql_query($query)){
			echo "Result reached". "\n";
		}else{
			echo "Result not reached". "\n";
		}
		while ($row = mysql_fetch_assoc($result)) {
			$returnValue = 1;
		}
		return $returnValue;
		
	}
	*/
	
/*
	public function insertArchiveFile() {
		$lastUpdate = "";
		$fileId = -1;
		$fileName = "";
		$fileVersion = "";
		$fileIP = "";
		if($this->code_base == "HMI"){
			$lastUpdate = $this->last_hmi_update;
			$fileId = $this->current_hmi_file_id;
			$fileName = $this->current_hmi;
			$fileVersion = $this->current_hmi_version;
			$fileIP = $this->current_hmi_ip;
		}
		if($this->code_base == "PLC"){
			$lastUpdate = $this->last_plc_update;
			$fileId = $this->current_plc_file_id;
			$fileName = $this->current_plc;
			$fileVersion = $this->current_plc_version;
			$fileIP = $this->current_hmi_ip;
		}	
	
				$query = sprintf("INSERT IGNORE INTO `hmi_plc_mgr`.`customer_machine_archive` (
					`id`,
					`machine_type`,
					`code_base`,
					`customer_machine_id`,
					`customer_id`,
					`customer_name`,
					`file_update`,
					`file_id`,
					`file_name`,
					`file_version`,
					`file_ip`
					)
					VALUES (
					NULL , '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'
					);", 
					mysql_real_escape_string($this->machine_type), 
					mysql_real_escape_string($this->code_base), 
					mysql_real_escape_string($this->id), 
					mysql_real_escape_string($this->customer->id), 
					mysql_real_escape_string($this->customer->name), 
					mysql_real_escape_string($lastUpdate), 
					mysql_real_escape_string($fileId), 
					mysql_real_escape_string($fileName), 
					mysql_real_escape_string($fileVersion), 
					mysql_real_escape_string($fileIP));
					//echo $query . "\n\n";
				mysql_query($query);
	}


	public function showArchiveFiles() {
		$this->linkMaker->machine_type = $this->machine_type;
		$this->linkMaker->code_base = $this->code_base;
		
		$rowCounter = 0;
		$query = sprintf("SELECT * FROM `hmi_plc_mgr`.`customer_machine_archive` WHERE `customer_machine_archive`.`customer_machine_id` = '%s' AND `customer_machine_archive`.`machine_type` = '%s' AND `customer_machine_archive`.`code_base` = '%s' ORDER BY `customer_machine_archive`.`id` %s", mysql_real_escape_string($this->id), mysql_real_escape_string($this->machine_type), mysql_real_escape_string($this->code_base), mysql_real_escape_string($this->sort));

		//echo $query;
		echo "<table border=1  style='background:#F3F7F7' >";
				echo "<tr>";
					echo "<td style='font-size:18'><b>Customer</b></td>";
					echo "<td style='font-size:18'><b>Date</b></td>";
					echo "<td style='font-size:18'><b>File</b></td>";
					echo "<td style='font-size:18'><b>Description</b></td>";
					echo "<td style='font-size:18'><b>Version</b></td>";
					echo "<td style='font-size:18'><b>IP</b></td>";
				echo "</tr>";
		$result = mysql_query($query);
			while ($row = mysql_fetch_assoc($result)) {
				$this->listFiles->setFile($row['file_id']);		
			
				if($rowCounter % 2 == 0) echo "<tr style='background:#F5E0EB'>";
				else echo "<tr>";
				echo "<td valign=top align=left>".$row['customer_name']."</td>";
				echo "<td valign=top align=left>".$row['file_update']."</td>";
				echo "<td valign=top align=left>".$this->linkMaker->getFileLink($row['file_name'])."</td>";
				echo "<td valign=top align=left>".nl2br($this->listFiles->description_of_changes)."</td>";
				echo "<td valign=top align=left>".$row['file_version']."</td>";
				echo "<td valign=top align=left>".$row['file_ip']."</td>";
				echo "</tr>";
				$rowCounter++;
			}	
		echo "</table>";
		*/
	}

    ?>