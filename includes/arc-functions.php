<?php

if ( !function_exists( 'pzdebug' ) )
{

	//---------------------------------------------------------------------------------------------------
	// Debug
	//---------------------------------------------------------------------------------------------------
	/**
	 * [pzdebug description]
	 * @param  string $value='' [description]
	 * @return [type]           [description]
	 */
	function pzdebug( $value = '' )
	{
		$btr	 = debug_backtrace();
		$line	 = $btr[ 0 ][ 'line' ];
		$file	 = basename( $btr[ 0 ][ 'file' ] );
		print"<pre>$file:$line</pre>\n";
		if ( is_array( $value ) )
		{
			print"<pre>";
			print_r( $value );
			print"</pre>\n";
		}
		elseif ( is_object( $value ) )
		{
			var_dump( $value );
		}
		else
		{
			print("<p>&gt;${value}&lt;</p>" );
		}
	}

}

function pz_squish($array) {
	$return_array = array();
	foreach($array as $key => $value) {
		$return_array[$key] = $array[$key][0];
	}
	return $return_array;
}