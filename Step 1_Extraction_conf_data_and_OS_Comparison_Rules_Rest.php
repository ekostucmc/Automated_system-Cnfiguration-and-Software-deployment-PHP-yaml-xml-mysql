<?php
/*----- 
Loseva E., 15.09.2017
Description:

The moduls within full code-implementation of the Step 1 contains:
1. class Conversion_Extraction_Compar_rules_step1, 
2. eleven functions

Name of function and description:
1 *createArray*

It works for creation array which is supposed to contain extracted keyword/value pairs from bundles or charms
*------------------------- 
2 *extract_data_from_yaml*

It works for extraction data from Yaml files. ....
*-------------------------
3 *extractFromWindows_german_version*

It works for extraction keyword/value pairs from Windows OS registry german version.
*-------------------------
4 *comparison_forWindowsOS_bundle*

It works for comparison different keyword/value pairs from Winindows OS registry and bundle`s files 
*-------------------------
5 *extractFromLinux_english*

It works for extraction keyword/value pairs from Linux OS document german version
*-------------------------
6 *comparison_forLinuxOS_bundle*  	1

It works for comparison different keyword/value pairs from Linux OS document and bundle`s files
*-------------------------
7 *comaprison_forCharms_Windows*

It works for comparison of keyword/value pairs from charm`s files and Windows OS registry.  
*-------------------------
8 *comaprison_forCharms_Linux* 		1

It works for comparison of keyword/value pairs from charm`s files and Linux OS registry.  
*-------------------------
9 *varificationForSigns*

It works for varification signs "Gb", "Mb" into a provided value, because some values can contain numerical data together with letters. It is about keywords as "root-disk", "mem", etc.  
Example: "root-disk=14G", here keyword is "root-disk", and value is "14G", but this value must be exampted from any letters for further steps impelementation such as comparison with Windows or Linux OS counterparts.  
*-------------------------
10 *bytesToGB*

It works for transformation value from bytes to Mb, or Gb; Mb to Gb; etc.
*-------------------------
11 *for_Extraction*

It works for extracion data from array, that was created after extraction data from bundle files. It is substep between applying function for extraction data and function for comparison of keyword/value pairs from bundles and Windows or Linux counterpart.   
*-------------------------
*/


//*--- Created two functions for two cases: "accept" and "reject". Two functions for insertion and extraction data to/from MySQL.  database.				
//*--- Trigger all functions: Conversion to XML, Extraction data using Xpath, all extracted keyword/value pairs submit into DB MySQL 
 
$c = new Conversion_Extraction_Extrac_Compar_step1();
$c->extractFromWindows_german_version();  
$c->comparison_forWindowsOS_bundle();  
//$c->conversionXml(); 
$c->extractFromLinux_english();   
$c->comparison_forWindowsOS_bundle();  
$c->comaprison_forCharms_Windows(); 
 
   
class Conversion_Extraction_Extrac_Compar_step1
{
	private 
		$file_Win_eng = "msinfo.xml", 
		$file_Win_ger = "MsInfo_ger.nfo",
		$file_XML = "yaml2XMLfile1.xml",			// 	XMLed file after conversion
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

public function target-relevant files()
{	

require_once('execute_from_toDB.php'); 
$val_execute = new Database();

$var2 = "INSERT INTO `comparison_forcharm_step2` (`Charm_name`, `TR_file_name/format`, `keyword_fromCharm`, `value_fromCharm`, `keyword_fromOS_doc/TM_data`, `value_fromOS_doc/TM_data`, `S1*_set_attributes`, `Rule`, `Result`) VALUES
('Cinder', 'worker-multiplier', '2', 'cores', '4', 'cpu-cores', '<=', 'accept', ''),
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
('cinder', 'test_cinder_context.py', 'settings', '\'backend_name\' \'cinder-ceph\',\r\n\'private-address\' \'10.5.8.191\',\r\n\'stateless\' \'True\',            \'subordinate_configuration\'\'{'cinder', '/etc/cinder/cinder.conf'', '', '', 'string', '', '')"	
	
$val_execute->insert_data($var2);

}


//-----	**	WINDOWS	** German version **	-----
public function extractFromWindows_german_version()
{
	
require_once('execute_from_toDB.php'); 
$val_execute = new Database();


$xml = simplexml_load_file($this->file_Win_ger, null, LIBXML_NOCDATA);

//*---------- 		cpu-cores		----------------*
$f_os = $xml->xpath('//Category/Category[@name="Softwareumgebung"]/Category[@name="Umgebungsvariablen"]/Data');
 
$this->createArray($f_os);     
$str = 0;
$var = "NUMBER_OF_PROCESSORS"; 
for($i = 0; $i < count($this->arr); $i++)
{
	if(strpos($this->arr[$i], $var) !== false)
	{
		$str = $i;		
	}
}

//	print("NUMBER_OF_PROCESSORS = ".$this->arr[$str+1]);
$this->strcpu = $this->arr[$str+1];
//	print_r($this->strcpu."\n");	

// Insert TO DB

$var2 = "INSERT INTO comparison_forcharm/bundle_step1,2 (keyword_fromOS_doc, value_fromOS_doc, juju_keyword, S1_set_attributes) VALUES (".$prach.")"
$val_execute->insert_data($var2);


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
$prarch = 'PROCESSOR_ARCHITECTURE'.','.' '.$this->strpi.','.' '.'arch'.','.' '.'string'; 

/*
$var2 = "INSERT INTO comparison_forcharm/bundle_step1,2 (keyword_fromOS_doc, value_fromOS_doc, juju_keyword, S1_set_attributes) VALUES (".$prach.")"

$val_execute->insert_data($var2);

*/


$f_mem = $xml->xpath('//Category/Data');
//  Element=>Installierter physischer Speicher (RAM)
//  Wert=>...

$this->createArray($f_mem);

for($i = 0; $i < count($this->arr); $i++)
{
	if(strpos($this->arr[$i], "Installierter physischer Speicher (RAM)") !== false)
	{
		$str = $i;		
	}
}
$this->strmem = $this->arr[$str+1];
// print("\n"."Installierter physischer Speicher (RAM) = ".$this->arr[$str+1]."\n");
$this->strmem = $this->arr[$str+1]; 

/*
$var2 = "INSERT INTO comparison_forcharm/bundle_step1,2 (keyword_fromOS_doc, value_fromOS_doc, juju_keyword, S1_set_attributes) VALUES (Installierter physischer Speicher (RAM), $this->strmem, mem, string)" 

$val_execute->insert_data($var2);

*/


//*-----	root disk	------------*
$f_rd = $xml->xpath('//Category/Category[@name="Komponenten"]/Category[@name="Speicher"]/Category[@name="Laufwerke"]/Data');
// Element=> Größe 
// Wert=>...

$this->createArray($f_rd);
for($i = 0; $i < count($this->arr); $i++)
{
	if(strpos($this->arr[$i], "Größe") !== false)
	{
		$str = $i;		
	}
}
/*
print("Größe = ".$this->arr[$str+1]);
*/
$this->strrd = $this->arr[$str+1];	// 210,13 GB
// Insert TO DB 	*
/*
$var2 = "INSERT INTO comparison_forcharm/bundle_step1,2 (keyword_fromOS_doc, value_fromOS_doc, juju_keyword, S1_set_attributes) VALUES ('Größe', ".$this->strrd.", root-disk, string)"

$val_execute->insert_data($var2);
 
*/
}


public function comparison_forWindowsOS_bundle()
{	
//	From bundle.yaml file	
$mem = 'mem';
$cpu_c  = 'cores';
$cpu_p = 'power'; 
$root_d = 'root'; 
//--    
$f = 'G'; 
$g = 'M';

//---
require_once('execute_from_toDB.php'); 
$val_execute = new Database();
//---

$this->extractFromWindows_german_version();	 			
// work with array 
$updated_data = "";


$xml_frombundle = simplexml_load_file("yaml2XMLfile.xml", null, LIBXML_NOCDATA);
$this->arr_bun = $xml_frombundle->xpath('//constraints');
// print_r($this->arr_bun); 

$i=0;
while($i < count($this->arr_bun))
{
$str = $this->arr_bun[$i][0];
$expl = explode(" ",$str);
$this->arr_bundle[] = $expl;
$i++;
}

print_r($this->arr_bundle);  

$this->coun = count($this->arr_bundle);

$i = 0;
while($i < $this->coun)
{

for($t = 0; $t < count($this->arr_bundle[$i]); $t++)
{		
	try
	{			
		if (strpos($this->arr_bundle[$i][$t], $mem) !== false)
		{
			$bun_mem = substr($this->arr_bundle[$i][$t], 4); 						// value from bundle	
			$mem = substr($this->strmem, 0, -6);									// value from Windows registry 
			
			if(strpos($bun_mem, $f) !== false || strpos($bun_mem, $g) !== false)
			{			

				$rest = $this-varificationForSigns($rd); 
			
			}
			else
			{
				$rest = $bun_mem;
			}

			
			$var2 = "INSERT INTO dictionary_forbundle_step1 (Bundle_name, TR_file_name/format, Xpath, keyword_frombundle, value_frombundle, machine_№_underConstraints, S1_set_attributes) VALUES (..., ..., ...,...)"
			
			$val_execute->insert_data($var2);
			
			
			if($this->strmem >= $rest)
			{				
			
			$var2 = "INSERT INTO comparison_forcharm/bundle_step1,2 (Charm/bundle_name, TR_file_name/format, keyword_fromCharm/bundle, value_fromCharm/bundle, Rule, Result_afterComparison) VALUES (0, bundle.yaml, ".$bun_mem.", ".$this->strmem.", >=, accept)" 	 					
			$val_execute->insert_data($var2);
			
			}
			else
			{
			
			$var2 = "INSERT INTO comparison_forcharm/bundle_step1,2 (Charm/bundle_name, TR_file_name/format, keyword_fromCharm/bundle, value_fromCharm/bundle, Rule, Result_afterComparison) VALUES ('', bundle.yaml, $bun_mem, ".$this->strmem.", <, reject)" 	 					
			$val_execute->insert_data($var2);
			
			}			
		}	
		else if (strpos($this->arr_bundle[$i][$t], $cpu_c) !== false )
		{
			
			
			$var2 = "INSERT INTO dictionary_forbundle_step1 (Bundle_name, TR_file_name/format, Xpath, keyword_frombundle, value_frombundle, machine_№_underConstraints, S1_set_attributes) VALUES (..., ..., ...,...)" 
			
			$val_execute->insert_data($var2);
			
			$bun_cpu_c = substr($this->arr_bundle[$i][$t], 10);
			if($this->strcpu >= $bun_cpu_c)
			{
			
			$var2 = "INSERT INTO comparison_forcharm/bundle_step1,2 (Charm/bundle_name, TR_file_name/format, keyword_fromCharm/bundle, value_fromCharm/bundle, Rule, Result_afterComparison) VALUES ('', bundle.yaml, ".$bun_cpu_c.", ".$this->strmem.", >=, accept)"
			
			$val_execute->insert_data($var2);
			
			*/
			}
			else
			{
						
			$var2 = "INSERT INTO comparison_forcharm/bundle_step1,2 (Charm/bundle_name, TR_file_name/format, keyword_fromCharm/bundle, value_fromCharm/bundle, Rule, Result_afterComparison) VALUES (0, bundle.yaml, ".$bun_cpu_c.", ".$this->strmem.", <, accept)"
			
			$val_execute->insert_data($var2);			
			
			}		
		}
		else if (strpos($this->arr_bundle[$i][$t], $cpu_p) !== false)
		{
			
		$var2 = "INSERT INTO dictionary_forbundle_step1 (Bundle_name, TR_file_name/format, Xpath, keyword_frombundle, value_frombundle, machine_№_underConstraints, S1_set_attributes) VALUES (..., ..., ...,...)"
		
		$val_execute->insert_data($var2);
	
		$bun_cpu_p = substr($this->arr_bundle[$i][$t], 10);
		if($bun_cpu_p <= 150)
		{
						
			$var2 = "INSERT INTO comparison_forcharm/bundle_step1,2 (Charm/bundle_name, TR_file_name/format, keyword_fromCharm/bundle, value_fromCharm/bundle, Rule, Result_afterComparison) VALUES (0, bundle.yaml, ".$bun_cpu_p.", 150, <=, accept)"
			
			$val_execute->insert_data($var2);

		}
		else
		{
		$var2 = "INSERT INTO comparison_forcharm/bundle_step1,2 (Charm/bundle_name, TR_file_name/format, keyword_fromCharm/bundle, value_fromCharm/bundle, Rule, Result_afterComparison) VALUES (0, bundle.yaml, ".$bun_cpu_p.", 150, >, reject)"
		
		$val_execute->insert_data($var2);
		}			
		}	
		else if (strpos($this->arr_bundle[$i][$t], $root_d) !== false)
		{
		
		$var2 = "INSERT INTO dictionary_forbundle_step1 (Bundle_name, TR_file_name/format, Xpath, keyword_frombundle, value_frombundle, machine_№_underConstraints, S1_set_attributes) VALUES (..., ..., ...,...)" 
		$val_execute->insert_data($var2);
		
		$bun_root_d = substr($this->arr_bundle[$i][$t], 11);
		if (strpos($bun_root_d, $f) !== false || strpos($bun_root_d, $g) !== false)
		{
			$rest = $this-varificationForSigns($rd);  
		}	
		else
		{
			$rest = $bun_root_d; 
		}	
		if($this->strrd >= $rest)
		{
			
			$var2 = "INSERT INTO comparison_forcharm/bundle_step1,2 (Charm/bundle_name, TR_file_name/format, keyword_fromCharm/bundle, value_fromCharm/bundle, Rule, Result_afterComparison) VALUES (0, bundle.yaml, $rest, ".$this->strrd.", >=, accept)"
			
			$val_execute->insert_data($var2);
	
		}
		else
		{
			$var2 = "INSERT INTO comparison_forcharm/bundle_step1,2 (Charm/bundle_name, TR_file_name/format, keyword_fromCharm/bundle, value_fromCharm/bundle, Rule, Result_afterComparison) VALUES (0, bundle.yaml, $rest, ".$this->strrd.", <, reject)";
				
			$val_execute->insert_data($var2);
			
		}
		}			
		}	
		catch(Exception $e)
		{
		
			echo "\n"."  In constraints there are data about: 'tag', 'species', 'virt-type', 'networks', 'instance-type' "."\n";
		
		}				
	}
	$i++;   
}
}



public function comaprison_forCharms_Windows()
{
	
require_once('execute_from_toDB.php'); 
$val_execute = new Database();
	
//*-----------				CHARMS 					------*
//*-  	 cores	 	-- * 
$this->arr_charm_bundle = array();
$xml = simplexml_load_file("yaml2XMLfile1.xml", null, LIBXML_NOCDATA);
$yaml_path1 = $xml->xpath('//worker-multiplier/default');


if (!empty($yaml_path1))
{
	$this->for_Extraction($yaml_path1);
	$cores = substr($this->arr_charm_bundle[0][0],0, -2);
	
	$var2 = "INSERT INTO `dictionary_forcharm_step1` (`Charm_name`, `TR_file_name/format`, `Xpath`, `keyword_fromCharm`, `description_forkeyword`, `juju_keyword`, `S1_set_attributes`) VALUES 
	('Cinder', 'config.yaml', '//worker-multiplier/default', 'worker-multiplier', 'The CPU multiplier to use when configuring worker processes for the\r\naccount, container and object server processes.', 'cpu-cores', 'float')" 	
	$val_execute->insert_data($var2);
	
	//	print_r($cores);
	
	if($cores <= $this->strcpu)
	{		

	$var2 = "INSERT INTO `comparison_forcharm/bundle_step1,2` (`Charm/bundle_name`, `keyword_fromCharm/bundle`, `value_fromCharm/bundle`, `keyword_fromOS_doc`, `value_fromOS_doc`, `juju_keyword`, `Rule`, `Result_afterComparison`, `S1_set_attributes`) VALUES
	('Cinder', 'worker-multiplier', '2', 'cores', ".$cores.", 'cpu-cores', '<=', 'accept', '')"
	$val_execute->insert_data($var2);
	
	}
	else
	{
	$var2 = "INSERT INTO `comparison_forcharm/bundle_step1,2` (`Charm/bundle_name`, `keyword_fromCharm/bundle`, `value_fromCharm/bundle`, `keyword_fromOS_doc`, `value_fromOS_doc`, `juju_keyword`, `Rule`, `Result_afterComparison`, `S1_set_attributes`) VALUES ('Cinder', 'worker-multiplier', '2', 'cores', ".$cores.", 'cpu-cores', '>', 'reject', '')"; 
	
	$val_execute->insert_data($var2);
	}
}
else
{
	print("\n"."*-	Information about CPU cores is not available	-*");
}

//mem 
$this->arr_charm_bundle = array();
$yaml_path2 = $xml->xpath('//reserved-host-memory/default');

if (!empty($yaml_path2))
{
	$this->for_Extraction($yaml_path2); 
	$drd = $this->arr_charm_bundle[0][0];
	$val_ver = $this->varificationForSigns($drd); 
	 
	
	$var2 = "INSERT INTO dictionary_forcharm_step1 (Charm_name, TR_file_name/format, Xpath, keyword_fromCharm_name, description_forKeywords, juju_keyword, S1_set_attributes) VALUES (..., ...)"
	
	$val_execute->insert_data($var2);
	
	if($this->strmem >= $val_ver)
	{

	$var2 = "INSERT INTO comparison_forcharm/bundle_step1,2 (Charm/bundle_name, TR_file_name/format, keyword_fromCharm/bundle, value_fromCharm/bundle, Rule, Result_afterComparison) VALUES (0, config.yaml, reserved-host-memory, $this->varificationForSigns($drd), >=, accept)";
	
	$val_execute->insert_data($var2);

	}
	else
	{
		$var2 = "INSERT INTO comparison_forcharm/bundle_step1,2 (Charm/bundle_name, TR_file_name/format, keyword_fromCharm/bundle, value_fromCharm/bundle, Rule, Result_afterComparison) VALUES (0, config.yaml, reserved-host-memory, ".$this->varificationForSigns($drd).", <, reject)";
		
		$val_execute->insert_data($var2);
					
	}
}
else
{	
	print(" Information "); 
}


// extraction other keywords concerning ram, disk space, socket cores, memory   
$this->arr_charm_bundle = array();
$xml = simplexml_load_file("yaml2XMLfile1.xml", null, LIBXML_NOCDATA);
$yaml_path3 = $xml->xpath('//ram-allocation-ratio/default');


if (!empty($yaml_path3))
{
	$this->for_Extraction($yaml_path3);
	$ram = $this->arr_charm_bundle;
	// $ramm = substr($ram, 0, -2);  
	
	
	$var2 = "INSERT INTO dictionary_forcharm_step1 (Charm_name, TR_file_name/format, Xpath, keyword_fromCharm_name, description_forKeywords, juju_keyword, S1_set_attributes) VALUES (..., ..., ...,...)" 
	
	$val_execute->insert_data($var2);
	
	
	$val_ver = $this->varificationForSigns($ram); 	
		
	if($this->strmem >= $val_ver)
	{
			
		$var2 = "INSERT INTO comparison_forcharm/bundle_step1,2 (Charm/bundle_name, TR_file_name/format, keyword_fromCharm/bundle, value_fromCharm/bundle, Rule, Result_afterComparison) VALUES ('', config.yaml, ram-allocation-ratio, ".$this->varificationForSigns($ram).", >=, accept)"
		
		$val_execute->insert_data($var2);
		 
	}
	else
	{
		
		$var2 = "INSERT INTO comparison_forcharm/bundle_step1,2 (Charm/bundle_name, TR_file_name/format, keyword_fromCharm/bundle, value_fromCharm/bundle, Rule, Result_afterComparison) VALUES ('', config.yaml, ram-allocation-ratio, ".$this->varificationForSigns($ram).", <, reject)";	
		
		$val_execute->insert_data($var2);
			  					
	}
}
else
{
	print("\n"."*-	Information about the physical ram -> virtual ram ratio is not available	-*");
}



//*- 		cores 		-*
$this->arr_charm_bundle = array();
$xml = simplexml_load_file("yaml2XMLfile1.xml", null, LIBXML_NOCDATA);
$yaml_path4 = $xml->xpath('//dpdk-socket-cores/default');

if (!empty($yaml_path4))
{
	$this->for_Extraction($yaml_path4);
	$score = $this->arr_charm_bundle;
	
	$var2 = "INSERT INTO dictionary_forcharm_step1 (Charm_name, TR_file_name/format, Xpath, keyword_fromCharm_name, description_forKeywords, juju_keyword, S1_set_attributes) VALUES (..., ..., ...,...)"
	
	$val_execute->insert_data($var2);
	
	
	if($this->strmem >= $score)
	{
		
		$var2 = "INSERT INTO comparison_forcharm/bundle_step1,2 (Charm/bundle_name, TR_file_name/format, keyword_fromCharm/bundle, value_fromCharm/bundle, Rule, Result_afterComparison) VALUES ('', config.yaml, dpdk-socket-cores, ".$score.", >=, accept)"
		
		$val_execute->insert_data($var2);
	}
	else
	{
		$var2 = "INSERT INTO comparison_forcharm/bundle_step1,2 (Charm/bundle_name, TR_file_name/format, keyword_fromCharm/bundle, value_fromCharm/bundle, Rule, Result_afterComparison) VALUES ('', config.yaml, dpdk-socket-cores, ".$score.", <, reject)"
		
		$val_execute->insert_data($var2);	  					
	}
}
else
{
	print("\n"."*-	Information about socket-cores is not available	-*");
}


//				 memory 			
$this->arr_charm_bundle = array();
$xml = simplexml_load_file("yaml2XMLfile1.xml", null, LIBXML_NOCDATA);
$yaml_path5 = $xml->xpath('//factor/default');


if (!empty($yaml_path5))
{	
	$this->for_Extraction($yaml_path5);
	$ram = $this->arr_charm_bundle;
	// $ramm = substr($ram, 0, -2);  

	$var2 = "INSERT INTO dictionary_forcharm_step1 (Charm_name, TR_file_name/format, Xpath, keyword_fromCharm_name, description_forKeywords, juju_keyword, S1_set_attributes) VALUES (..., ..., ..., ...)"
	$val_execute->insert_data($var2);
	
	$this->varificationForSigns($ram); 
	
		
	if($this->strmem >= $val_ver)
	{
		
		$var2 = "INSERT INTO comparison_forcharm/bundle_step1,2 (Charm/bundle_name, TR_file_name/format, keyword_fromCharm/bundle, value_fromCharm/bundle, Rule, Result_afterComparison) VALUES (0, config.yaml, factor, ".$this->varificationForSigns($ram).", >=, reject)"
		
		$val_execute->insert_data($var2);
	}
	else
	{
		
		$var2 = "INSERT INTO comparison_forcharm/bundle_step1,2 (Charm/bundle_name, TR_file_name/format, keyword_fromCharm/bundle, value_fromCharm/bundle, Rule, Result_afterComparison) VALUES (0, config.yaml, factor, ".$this->varificationForSigns($ram).", <, reject)" 

		$val_execute->insert_data($var2);		
	}
}
else
{
	print("\n"."*-	Information about mem is not available	-*");
}
$this->arr_charm_bundle = array();
$xml = simplexml_load_file("yaml2XMLfile1.xml", null, LIBXML_NOCDATA);
$yaml_path6 = $xml->xpath('//disk-allocation-ratio/default');
	
if (!empty($yaml_path6))
{
	$this->for_Extraction($yaml_path6);
	$dsr = $this->arr_charm_bundle;
	// $dsr = substr($score, 0, -2);												// ????
	
	$val_ver = $this->varificationForSigns($drd); 
	

	$var2 = "INSERT INTO dictionary_forcharm_step1 (Charm_name, TR_file_name/format, Xpath, keyword_fromCharm_name, description_forKeywords, juju_keyword, S1_set_attributes) VALUES (..., ..., ...,...)"
	
	$val_execute->insert_data($var2);
	
	
	
	if($this->strmem >= $val_ver)
	{		
		$var2 = "INSERT INTO comparison_forcharm/bundle_step1,2 (Charm/bundle_name, TR_file_name/format, keyword_fromCharm/bundle, value_fromCharm/bundle, Rule, Result_afterComparison) VALUES (0, config.yaml, disk-allocation-ratio, ".$this->varificationForSigns($drd).", >=, reject)"
		
		$val_execute->insert_data($var2);
		
	}
	else
	{
		$var2 = "INSERT INTO comparison_forcharm/bundle_step1,2 (Charm/bundle_name, TR_file_name/format, keyword_fromCharm/bundle,
		value_fromCharm/bundle, Rule, Result_afterComparison) VALUES (0, config.yaml, disk-allocation-ratio, ".$this->varificationForSigns($drd).", <, reject)";   
		
		$val_execute->insert_data($var2); 					
	}
	
}
else
{	
	print("\n"."*-	Information about disk space is not available	-*");
}
}


//******************	  ----- VARIFICATION, TRANSFORMATION -----		*************************
public function varificationForSigns($vark)
{
	if(strpos($vark, "MB") !== false) 
	{	
		$trans = substr($vark, 0, -2);
		$this->bytesToGB($trans);   	
		$prt = $this->bytesToGB($vark); 		
		
	}
	else if (strpos($vark, "M") !== false)
	{
		$trans = substr($vark, 0, -1);
		$this->bytesToGB($trans);
		$prt = $this->bytesToGB($vark);
		
	}
	else if(strpos($vark, "GB") !== false || strpos($vark, "Gb") !== false)
	{
		$trans = substr($vark, 0, -2);
		$this->bytesToGB($trans);
		$prt = $this->bytesToGB($vark);
		
	}
	else if (strpos($vark, "G") !== false || strpos($vark, "g") !== false)
	{
		$trans = substr($vark, 0, -1);   
		$this->bytesToGB($trans);
		$prt = $this->bytesToGB($vark);  
		
	}
	else
	{
		// if no data about MB, GB, Mb, Gb, G, M then just convert to Gb
		$prt = $this->bytesToGB($vark);
		
	}
	
	return $prt; 
}

public function bytesToGB($os)
{

// process transmit
$ost1 = $os/1000; 						

print($ost1);

if ($ost1 < 0.5)
{
	$this->var_result = $ost1;
}
else
{
$ost2 = $ost1/1000;  

if ($ost2 > 0.1)
{
	echo '$ost2'."\n";
	$ost3 = $ost2/1000;
	
	if ($ost3 > 0.1)
	{
		print($ost3."\n");
		$ost4 = $ost3/1000;
		
		if ($ost4 > 0.5)
		{
			$ost5 = $ost4/1000;
			
			if ($ost5 < 0.5)
			{	
				$ost5=$ost4;
				$this->var_result = $ost5;
			}
			else
			{ 
  				$ost6 = $ost5/1000; 
				if ($ost6 < 0.5)
				{
					$ost6 = $ost5;
					$this->var_result = $ost5; 
				}
				else
				{
					$this->var_result = $ost6;
				}
			}
		}	
		else
		{
			$ost4 = $ost3;	
			$this->var_result = $ost4;
		}
	}
	else
	{
		$ost3 = $ost2;
		$this->var_result = $ost3;
	}
}
else
{
	$ost2 = $ost1; 
	$this->var_result = $ost2;
}
}
}



public function for_Extraction($yaml_path1)
{
	
$i=0;
while($i < count($yaml_path1))
{
$str = $yaml_path1[$i][0];
$expl = explode(" ",$str);
$this->arr_charm_bundle[] = $expl;
$i++;


}
}



}





?>