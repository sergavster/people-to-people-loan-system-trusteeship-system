<?php
/*  
 * Implementation of the SAX xml reader  
 * created by simonyi peng 2010-04-28
 */  

	/*
	 * create xml parser of SAX
	 */
	$parser = xml_parser_create();
	  
	/*
	 * set parser's begin-end perform function handler 
	 * set parset's content data perform function handler 
	 */
	xml_set_element_handler( $parser, "startElement", "endElement" );
	xml_set_character_data_handler( $parser, "textData" );

	/*
	 * parameters will be returned to RSA
	 * @parameter publickey 
	 * @modulus modulus
	 */
	$publickey = null;
	$modulus = null;

	$g_publickeys = array();
	$g_elem = null;
	
	/*
	 * initial variable when saxparser checked the xml document's start position
	 */ 
	function startElement( $parser, $name, $attrs ) 
	{
	  global $g_publickeys, $g_elem;
	  if ( $name == 'PUBLICKEY' ) $g_publickeys []= array();
	  $g_elem = $name;
	}
	  
	/*
	 * gc
	 */
	function endElement( $parser, $name ) 
	{
	  global $g_elem;
	  $g_elem = null;
	}
	  
	/*
	 * get content from XMLElement and set value to parameter
	 */
	function textData( $parser, $text )
	{
	  global $g_publickeys, $g_elem;
	  if ( $g_elem == 'EXPONENT' ||  $g_elem == 'MODULUS' )
	  {
	    $g_publickeys[ count( $g_publickeys ) - 1 ][ $g_elem ] = $text;
	  }
	}
	  
	//-------------------------------------------------------------------------------
	function initPublicKey($path)
	{
	  global $publickey,$modulus,$parser,$g_publickeys;
	  //file operate
	  $f = fopen( $path, 'r' );
		  
	  while( $data = fread( $f, 4096 ) )
	  {
	    xml_parse( $parser, $data );
	  }
	  
	  xml_parser_free( $parser );
	 
	  /*
	   * circle evaluate
	   */
	  foreach( $g_publickeys as $publickey )
	  {
	    $modulus = $publickey['MODULUS'];
	    $publickey = $publickey['EXPONENT'];
	  }
	}

?>