<?php


// Conversion any format files to XML and 

$obj = new XmlDomCon();
$obj->conversion_yaml_to_Xml();
$obj->createXml_any_formats(); 
 


class XmlDomCon //extends DOMDocument 
{
private
	$mass_r = array();
	
	
public function createXML_yaml($arr_y = array()) 
{

$masst = array();
$mp = array();

$prin[] = '<?xml version="1.0" encoding="utf-8" standalone="yes"?>';
foreach($arr_y as $key=>$value) 
{

	
if(is_array($value))
{
	foreach($value as $key1=>$value1)
	{	
	
		if(is_int($key1) && is_string($value1))
		{
			$mass_r[] = $value1;
		}
	}
}
}
	
foreach($arr_y as $key=>$value) 
{
if (is_array($value)) 
{  
     
$prin[] = "<".$key.">"."\n";

if(is_array($value))
{

	foreach($value as $key1=>$value1)
	{	
		
		
		if(is_string($key1))
		{
			
		$prin[] = "<".$key1.">";
			
			
			if(empty($value1))
			{				
				foreach($mass_r as $masses)
				{
					$prin[] = $masses."\n";
				}
			}
			
			if(is_string($key1) && is_string($value1))
			{
				$prin[] = $value1; 
			}
				
		if(is_array($value1))
		{

		foreach($value1 as $key2=>$value2)
		{
					
	//--------------------------
	
			if(is_int($key2) && is_array($value2))
			{
			
				$prin[] = "<num".$key2.">"."\n";
				
				foreach($value2 as $key3=>$value3)
				{
					
					if(!is_int($key3))
					{
						$prin[] = "<".$key3.">"; 
					$prin[] = $value3;
						$prin[] = "</".$key3.">";
					}
				}						
			}
					
		if(is_string($key2) && is_array($value2))
		{
			
			$prin[] = "<".$key2.">";			
				
		foreach($value2 as $key3=>$value3)
		{						
			if(is_int($key3))
			{					
				$i=0;
				
				while(is_int($key3))
				{
					
					$masst[] = $value3;
					$i++;
					break;				
				}				
			}			
		}				
		foreach($value2 as $key3=>$value3)
		{		
			if(!is_int($key3))
			{
				
				if($value3 == "")
				{
					$prin[] = "<".$key3.">";
					
					foreach($masst as $masses)
					{		
						$prin[] = ""."-".$masses;
					}
					
					$masst = null;
				}					
				else
				{						
					$prin[] = "<".$key3.">";					
				}					
				if(is_array($value3))
				{
					foreach($value3 as $key4=>$value4)
					{								
						$prin[] = "<".$key4.">";
						$prin[] = $value4;
						$prin[] = "</".$key4.">";
					}
				}
				else
				{		
			
					$prin[] = $value3;
				}					
				$prin[] = "</".$key3.">"; 								
			}			
		}
		}
		if(is_int($key2))
		{
		 $prin[] = "</num".$key2.">"."\n";
		}
		else
		{
		 $prin[] = "</".$key2.">"."\n";
		}
			
	//--------------------------
		}	
	}
	$prin[] = "</".$key1.">"."\n";
  }	
 }
}
$prin[] = "</".$key.">"."\n"; 
}
}

for($i = 0; $i < count($prin); $i++)
{
	 $resultXML = array_filter($prin, create_function('$a','return preg_match("#\S#", $a);'));
}

print("*-------------*"."="); 
//print_r($resultXML);


//------ write to file ----------
$newFile = 'yaml2XMLfile.xml'; // File for writing XML data

if (file_exists($newFile))
{
	$fout = fopen($newFile, 'w');
	foreach($resultXML as $resultXMLs)
	{
		fwrite($fout, $resultXMLs);
	}
	
	fclose($fout);
}
else
{
	echo " Check your file ";
}

}


public function conversion_yaml_to_Xml() 
{
	
$yaml = file_get_contents('bundle.yaml'); 
require_once('treatYaml.php');		
$parser = new YamlToArray();
$data = $parser->convToArray($yaml); 
$arr = array("root"=>$data); 

$conv = new XmlDomCon(); 
$conv->createXML_yaml($arr);
}


public function createXml_any_formats()
{
$tk = file_get_contents("test_keystone_hooks+.py");
$atp = explode("\n", $tk);

for($i = 0; $i < count($atp); $i++)
{

if(substr($atp[$i], 0, 1) == "#")
{
	$index = $i;
	$inx[] = $index;
}	
else
{
	$ndex = $i;
	$ndx[] = $ndex;
}	
}

$prin[] = '<?xml version="1.0" encoding="utf-8" standalone="yes"?>'."\n";
$prin[] = "<"."root".">"."\n";


for($i = 0; $i < count($atp); $i++)
{	
	if(strpos($atp[$i], "#") !== false || strpos($atp[$i], "//") !== false)
	{
		$prin[] = "<"."description_"."num".$i.">"."\n";
		$commemts = $atp[$i];
		$prin[] = $commemts;
		$prin[] = "</"."description_"."num".$i.">"."\n";	
	}
	else 
	{
		$tex = $atp[$i];
		$prin[] = "<"."text_"."num".$i.">"."\n";
		
		if(strpos($atp[$i], "@") !== false)
		{
			$prin[] = "<"."Element".">"."\n";
			$com = $atp[$i]; 
			$prin[] = $com."\n"; 
			$prin[] = "</"."Element".">"."\n";
		}
		else if(strpos($atp[$i], "def") !== false)
		{
			$prin[] = "<"."Function".">"."\n";
			$com = $atp[$i]; 
			$prin[] = $com."\n"; 
			$prin[] = "</"."Function".">"."\n";
		}
		else if(strpos($atp[$i], "<") !== false)
		{
			$prin[] = "<"."Uppertag".">"."\n";
			$com = $atp[$i]; 
			$prin[] = $com."\n"; 
			$prin[] = "</"."Uppertag".">"."\n";
		}
		else
		{
			$prin[] = $tex."\n";
		}
		
		$prin[] = "</"."text_"."num".$i.">"."\n";
	}
}
$prin[] = "</"."root".">"; 

for($i = 0; $i < count($prin); $i++)
{
	 $resultXML = array_filter($prin, create_function('$a','return preg_match("#\S#", $a);'));
}

if (file_exists("XMLedFiles.xml"))
{
	$fout = fopen("XMLedFiles.xml", 'w');
	foreach($resultXML as $resultXMLs)
	{
		fwrite($fout, $resultXMLs);
	}
	
	fclose($fout);
}
else
{
	echo " Check your file ";
}
}
}
}
?>