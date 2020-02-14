<?php

/*
 * Smarty plugin
 * --------------------------------------------------------------------
 * Type:     function
 * Name:     textfield
 * Version:  1.0
 * Date:     Oct 20, 2003
 * Author:	 Leider Vivas <leiderv@hotmail.com>
 * Purpose:
 * Input:
 *           name = name of the textfield (optional)
 *           type = define the type of the textfield (required)
 *           id = id of the textfield (optional)
 *           value = puts text inside the textfield (optional)
 *           size = Long of the textfield (optional)
 *           typeData = define the type of data that you can introduce (optional)
 *           readonly = readonly ('true'|'false') (optional)
 *           disabled = disabled the textfield (optional)
 *           onClick =  introduce code javascript (optional)
 *           maxlength = Maximum of characters of the textfield (optional)
 *
 *
 *
 * Examples : {textfield name="textfield" type="text" size="60" value="LIDIS"}
 *
 *
 *
 * --------------------------------------------------------------------
 */
function smarty_function_scripttab($params, &$smarty)
{
    extract($params);

     $html_result="<script type='text/javascript'>


var tabPane;

function showArticleTab( sName ) {
	
    if (typeof tabPane != 'undefined' ) {
		switch ( sName ) {
                  

			case '21':
				tabPane.setSelectedIndex( 0 );
				break;
			case '20':
				tabPane.setSelectedIndex( 1 );
				break;

		}
	}
}

</script>";

	print $html_result;
}

?>
