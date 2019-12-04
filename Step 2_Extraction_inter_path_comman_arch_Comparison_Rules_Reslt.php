<?php

/*
Loseva E., 15.10.2017
Description:

//	 Functionality of this part of code "Step 2" of Fixit: 

//1  Extract information about "arch" from Windows/Linux documents 
//2  Compaire with value from bundle or charm, if there values are different need to rewrite into the XMLed file created by conversion yaml to XML ("Step 1" of Fixit) 
//3  Extract paths, internet parameters, commands from XMLed for comparison 				    
//4	 Comparaire them with existing values      					 
//5  If any parameters (values) are not equal need to rewrite (change) them into XMLed file as well

*/


//---


$obj = new convert2XML_file(); 									


class convert2XML_file
{
	private 
		$file_Win_eng = "msinfo.xml", 
		$file_Win_ger = "MsInfo_ger.nfo",
		$file_newXML = "createdXML.xml", 
		$file_XML = "yaml2XMLfile1.xml",		
		$arr = array(),
		$arr_bundle = array(),
		$arr_bun = array(),	
		$xml_doc = null,
		$var_result = "",		
// For comparison rule	
// Windows variables
		$strrd = '',
		$strmem = '',
		$strar = '',
		$strcpu = '',
		$strpi = '', 
		$updated_data = '',
		$coun = '';
	
	
public function createArray($f_var)
{
//general function 
$i = 0;
while($i < count($f_var))
{
	foreach($f_var[$i] as $key=>$value)
	{
	$this->arr[]= "".$value;	
	}
	$i++;
}
return $this->arr;
}


//-----		WINDOWS	** German version **---
public function extractFromWindows_german_version()
{
	
require_once('execute_from_toDB.php'); 
$val_execute = new Database();

	
$xml = simplexml_load_file($this->file_Win_ger, null, LIBXML_NOCDATA);

// PROCESSOR_ARCHITECTURE	 
$this->createArray($f_os);
for($i = 0; $i < count($this->arr); $i++)
{
	if(strpos($this->arr[$i], "PROCESSOR_ARCHITECTURE") !== false)
	{
		$str = $i;		
	}
}

// print("PROCESSOR_ARCHITECTURE = ".$this->arr[$str+1]);
$this->strpi = $this->arr[$str+1];


$prarch = "'PROCESSOR_ARCHITECTURE, '.$this->strpi.', '".$this->strpi."', '.'' ''.'arch, '.'' ''.'string'"

$var2 = "INSERT INTO comparison_forcharm/bundle_step1,2 (keyword_fromOS_doc, value_fromOS_doc, juju_keyword, S1_set_attributes) VALUES "."(".$prach.")" 
$val_execute->insert_data($var2);

$xml = simplexml_load_file("yaml2XMLfile1.xml", null, LIBXML_NOCDATA);
$yaml_path1 = $xml->xpath('//path/to/arch/parameter');

if (!empty($yaml_path1))
{
	//	print_r($cores);
	if($yaml_path1 == $this->strar)
	{		
	
		$adb = "' '.','.' '.'config.yaml'.',.' '.'arch'.','.' '".$yaml_path1."', '.'=='.','.' '.'accept'"
		
		$var2 = "INSERT INTO comparison_forcharm/bundle_step1,2 (Charm/bundle_name, TR_file_name/format, keyword_fromCharm/bundle, value_fromCharm/bundle, Rule, Result_afterComparison) VALUES (".$adb.")"					
		
		$val_execute->insert_data($var2);
	
		
	}
	else
	{
	
	$adb = "''.','.' '.'config.yaml'.',.' '.'arch'.','.' '".$yaml_path1."','.' '.'!='.','.' '.'reject'"
	$var2 = "INSERT INTO comparison_forcharm/bundle_step1,2 (Charm/bundle_name, TR_file_name/format, keyword_fromCharm/bundle, value_fromCharm/bundle, Rule, Result_afterComparison) VALUES (".$adb.")"
	
	$val_execute->insert_data($var2);
	
	}
}
else
{
	print("\n"."*-	Information about CPU cores is not available	-*");
}

}

// Update elements into XML file 
public function path_comprison()
{
	
require_once('execute_from_toDB.php'); 
$val_execute = new Database();


$file = "XMLedFiles.xml";
$path_value_subst = "/str/vtp/logs/log.py";

$xml = simplexml_load_file($file, null, LIBXML_NOCDATA);
$path = $xml->xpath('//path/to/value_old');

$var2 = "INSERT INTO `dictionary_forcharm_step2` (`Charm_name`, `TR_file_name/format`, `Xpath`, `keyword_fromCharm`) VALUES
('Keystone', 'config.yaml', '//path/to/value_old', '')" 
$val_execute->insert_data($var2);

if($path == $path_value_subst)
{
	 			
$var2 = "INSERT INTO comparison_forcharm/bundle_hardware_options (Charm/bundle_name, TR_file_name/format, path_value_charm/bundle, path_value_substit, Result) VALUES (..., ..., ..., '/str/vtp/logs/log.py', ...)"

$val_execute->insert_data($var2);

}
else
{

$this->updateElement_path;  

}



//  substitude for INTERNET PARAMETERS read from file

$internet_keyword_subst = "";
$internet_value_subst = "";
$inter = dom_import_simplexml($xml->xpath('//path/to/value_old'));

$var2 = "INSERT INTO `target_relevant_files` (`Charm/bundle_name`, `TR_folder/file`, `Content`) VALUES 
('cinder', 'config.yaml', 'api-listening-port', '8776', '', '', 'int', '', ''),
('cinder', 'config.yaml', 'vip_cidr', '24', '', '', 'int', '', ''),
('cinder', 'config.yaml', 'ha-mcastport', '5454', '', '', 'int', '', ''),
('cinder', 'config.yaml', 'os-admin-network /os-internal-network/ os-public-network', '192.168.0.0/24', '', '', 'string', '', ''),
('cinder', 'config.yaml', 'worker-multiplier', '2.0', '', '', 'Float', '', ''),
('cinder', 'config.yaml', 'prefer-ipv6', 'False', '', '', 'boolean', '', ''),
('cinder', 'test_cluster_hooks.py', 'admin-address', '\'192.168.20.2\'', '', '', 'string', '', ''),
('cinder', 'test_cluster_hooks.py', 'internal-address', '\'10.20.3.2\'', '', '', 'string', '', ''),
('cinder', 'test_cluster_hooks.py', 'public-address', '\'146.162.23.45\'', '', '', 'string', '', ''),
('cinder', 'test_cluster_hooks.py', 'conf', '\'ha-bindiface\' \'eth100\',\r\n \'ha-mcastport\' \'37373\',\r\n\'vip\' \'192.168.25.163\' \'vip_iface\' \'eth101\',            \'vip_cidr\' \'19\'\r\n', '', '', 'string', '', ''),
('cinder', 'test_cluster_hooks.py', 'corosync_mcastport', '\'37373\'', '', '', 'string', '', ''),
('cinder', 'test_cinder_context.py', 'settings', '\'backend_name\' \'cinder-ceph\',\r\n\'private-address\' \'10.5.8.191\',\r\n\'stateless\' \'True\',            \'subordinate_configuration\'\'{"cinder", "/etc/cinder/cinder.conf"', '', '', 'string', '', '')" 
$val_execute->insert_data($var2);


if($inter == $internet_value_subst)
{
$var2 = "INSERT INTO comparison_forcharm/bundle_hardware_options (Charm/bundle_name, TR_file_name/format, internet_keyword_charm/bundle, internet_value_charm/bundle, internet_keyword_substit, internet_value_substit, Result) VALUES (...)"
$val_execute->insert_data($var2);

}
else
{
this->updateElement_internet_prameters(); 	
	
}


//  substitude for COMMANDS read from DB MySQL 

$command_fromCharm = "";
$command_subst = ""; 												 

$commands = dom_import_simplexml($xml->xpath('//path/to/value_old'));


$val_execute->insert_data($var2);

if($commands == $command_value_subst)
{

$var2 = INSERT INTO comparison_forcommand (Charm/bundle_name, TR_file_name/format, CL_fromCharm/bundle, CL_version_fromOS, Rule, Result) VALUES (..., ..., ..., ..., ..., ...);

$val_execute->insert_data($var2);

}
else
{
$this->updateElement_commands(); 
}

}


// for other formats 
public function updateElement_path()
{
require_once('execute_from_toDB.php'); 
$val_execute = new Database();

$file = "XMLedFiles.xml";
$keyword_substitude = "//folder/path/to"; 									// maybe value from MySQL 

$xml = simplexml_load_file($file);

foreach ($xml->xpath('//path/to/value_old') as $desc) 							// create Path
{
$dom = dom_import_simplexml($desc);
$updatedValue = "//path/to/value_new"; 
$dom->nodeValue = $updatedValue;											// new value or new phrase 

//*******
	$path_value_subst = "/str/vtp/logs/log.py";

	// variant 1:
	$dom->nodeValue = path_keyword_subst.$path_value_subst;
	
	// variant 2: 
	// $dom->nodeValue = path_keyword_subst."=".$path_value_subst;
}

$var2 = "INSERT INTO comparison_forcharm/bundle_hardware_options (Charm/bundle_name, TR_file_name/format, path_value_charm/bundle, path_value_substit, Result VALUES (...)"

$val_execute->insert_data($var2);
}

public function updateElement_internet_prameters()
{

$file = "XMLedFiles.xml";
$keyword_substitude = "//folder/path/to"; 									// maybe value from MySQL 

$xml = simplexml_load_file($file);

foreach ($xml->xpath('//path/to/value_old') as $desc) 						// create Path
{
$dom = dom_import_simplexml($desc);
$updatedValue = "8080"; 

//*******  
	$interim1 = "="; 
	$interim2 = ":"
	$interim3 = " "; 	
	
	$dom->nodeValue = $internet_keyword_subst.$interim1.$internet_value_subst;
	
	// $dom->nodeValue = $internet_keyword_subst.$interim2.$internet_value_subst;
	// $dom->nodeValue = $internet_keyword_subst.$interim3.$internet_value_subst;
												// new value or new phrase 
file_put_contents($file, $xml->asXML());
}
$var2 = "INSERT INTO comparison_forcharm/bundle_hardware_options (Charm/bundle_name, TR_file_name/format, internet_keyword_charm/bundle, internet_value_charm/bundle, internet_keyword_substit, internet_value_substit, Result) VALUES (...)"
$val_execute->insert_data($var2);

}


public function updateElement_commands()
{
$file = "XMLedFiles.xml";
$keyword_substitude = "//folder/path/to"; 								// maybe value from MySQL 

$xml = simplexml_load_file($file);

foreach ($xml->xpath('//path/to/value_old') as $desc) 					// create Path
{
$dom = dom_import_simplexml($desc);
$updatedValue = "open port 90"; 										// new value or new phrase	

	$interim1 = "="; 
	$interim2 = ":"
	$interim3 = " ";
//*******
$dom->nodeValue = $command_keyword_subst.$interim1.command_value_subst;            
// $dom->nodeValue = $command_keyword_subst.$interim2.command_value_subst;            
// $dom->nodeValue = $command_keyword_subst.$interim3.command_value_subst;  

}

$cl = ''.','.''.','.$command_fromCharm.','.''.','.'rewrite'.','.$command_subst; 
$var2 = "INSERT INTO comparison_forcommand (Charm/bundle_name, TR_file_name/format, CL_fromCharm/bundle, CL_version_fromOS, Rule, Result) VALUES (".$cl.")"	
$val_execute->insert_data($var2);

}

}


?>