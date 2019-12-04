<?php

/*

Loseva E. 25.10.2017
Description:

// Functionality of this part of code "Step 3" of Fixit:
 
//1  Rewrite (update) values using substitute values into yaml file; 
//2  Rewrite (update) values using substitute values into all other format files; 
//2  Conversion file with XML extention to any file formats in order to return file to original format.   


*/


$object = new Conversion_Extraction_Compar_rules_step2();
$object->updateElement_yaml_files();		
$object->conversion_fromXML_to_originalFormat();
$object->XML_to_Yaml();



class Conversion_Extraction_Compar_rules_step2
{
	private  
		$file_newXML = "createdXML.xml", 
		$file_XML = "yaml2XMLfile1.xml"; 

		
// for .yaml files 
public function updateElement_yaml_files()
{
	
$file = "yaml2XMLfile.xml";
$xml = simplexml_load_file($file, null, LIBXML_NOCDATA);
for($i = 0; $i < count($this->arr_bun); $i++)
{
	$rtu = explode(" ", $this->arr_bun[$i]); 									
	for($y = 0; $y < count($rtu); $y++)
	{	

	if(strpos($rtu[$y], "arch") !== false)
	{
		$itr = $y; 
	}
	}
	$type_arch = "i386";
	$rtu[$itr] = "arch=".$type_arch;
	$updString[] = implode(" ", $rtu); 
}
	for($v = 0; $v < count($updString); $v++)
	{
	foreach ($xml->xpath('//constraints') as $desc) 
	{
		$dom = dom_import_simplexml($desc);
		$dom->nodeValue = $updString[$v];
		file_put_contents($file, $xml->asXML());
	}
	}
}



// for other formats 
public function updateElement_all_files()
{

$file = "XMLedFiles.xml";

$xml = simplexml_load_file($file);

foreach ($xml->xpath('//path/to/value_old') as $desc) 							// create Path
{
$dom = dom_import_simplexml($desc);
$updatedValue = "//path/../.."; 
$dom->nodeValue = $updatedValue;											// new value or new phrase 

file_put_contents($file, $xml->asXML());

}
}


public function conversion_fromXML_to_originalFormat()
{

// remove tags:
$file_partXML = file_get_contents("XMLedFiles.xml"); 				// full text of XML file 
$strr = strip_tags($file_partXML); 
$arrs = explode("\n", $strr); 

// remove empty spaces in array 
for($i = 0; $i < count($arrs); $i++)
{
	 $resultXML = array_filter($arrs, create_function('$a','return preg_match("#\S#", $a);'));
}

$str = implode("\n", $resultXML);

file_put_contents("XMLedFiles.xml", $str); 

$this->conversion2originalFormat();		

}


public function conversion2originalFormat()
{
	
// Conversion step: conversion to original file format 
$origformat = ".py";

$file_forXML = "XMLedFiles.xml";
$fileexpl = explode(".", $file_forXML);
$curform = ".".$fileexpl[1];

$newext = str_replace($curform, $origformat, $file_forXML);
rename($file_forXML, $newext);

}


// conversion XML to yaml



public function XML_to_Yaml()
{
	
$xmlFile = 'yaml2XMLfile.xml';
$xslFile='temp.xsl';
$fileyaml = "updatedYml.yaml";


if (file_exists($xmlFile))
{	

# Load XML file 
$XML = new DOMDocument(); 
$XML->load( $xmlFile ); 


# create object  
$xslt = new XSLTProcessor(); 


# load StyleSheet 
$XSL = new DOMDocument(); 
$XSL->load( $xslFile ); 
$xslt->importStylesheet( $XSL ); 

try{
$newYaml = $xslt->transformToXML($XML);
}
catch(Exception $e){
	echo "Error message: {
		$e->getMessage()
		}";
}
}
else
{
	echo "*--  Error message: 'Use some other StyleSheet'  --*";
}



if(file_exists($fileyaml))
{
	
$fout = fopen($fileyaml, 'w');
		fwrite($fout, $newYaml);
		fclose($fout);
}
		
if(!empty($fileyaml))
{
	
$f = file_get_contents($fileyaml);
$art = explode("\n", $f);
unset($art[0]);
unset($art[1]);

$eru = implode("\n", $art);
file_put_contents($fileyaml, $eru);

}


//* Replace tags into XML file	*

$yml = file_get_contents($fileyaml);
$cvb = explode("\n", $yml);

for($i = 0; $i < count($cvb); $i++)
{
	
if (strpos($cvb[$i], "num") !== false)
{
		$index = $i;
		$ind[] = $index;	
}
}


// information about amount of machines 

$bundle = 6; 											// amout of values into array  
$am_mach = $bundle; 
$content = $yml;


$newFile = $fileyaml; 									// File for writing XML data


if (file_exists($newFile))
{
	$fout = fopen($newFile, 'w');
	for($f = 0; $f < $am_mach; $f++)
	{
		$newvalue = $f.":";
		$newvalue_upd[] = $f.":";
		$curvalue[] = "num".$f.":";
	}
	
	$new_text = str_replace($curvalue, $newvalue_upd, $content);
	fwrite($fout, $new_text);
	fclose($fout);
}
else
{
	echo " Check your file ";
}

$strin = file_get_contents($newFile);
$massd = explode("\n", $strin);

for($i = 0; $i < count($massd); $i++)
{
	 $yaml = array_filter($massd, create_function('$a','return preg_match("#\S#", $a);'));
}

if (file_exists($newFile))
{
	$fout = fopen($newFile, 'w');
	foreach($yaml as $yamls)
	{
		fwrite($fout, $yamls."\n");
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