<?php

/**

 * Smarty plugin

 */

/**

 * Smarty {hidden} plugin

 * Type:     function<br>

 * Name:     hidden<br>

 * Purpose: crea un componente label<br>

 * Input:<br>

 *           name = name of the hidden (optional)

 *           id = id of the hidden (optional)

 *           value = value of the hidden (optional)

 * <br>

 * Examples : {usuario}

 *

 * @author   Spyro Solutions 

 * @version  1.0.0

 * @param array

 * @param Smarty

 * @return string

 * @copyright Spyro Solutions 

 */

 

function smarty_function_waitscreen($params, &$smarty)

{

    extract($params);

    $html_result = '
    
<!-- THE WAIT SCREEEN!!! -->
<div ID="waitDiv" style="position:absolute; left:350; top:108; visibility:hidden">
	<center>	
	<table border=0 cellpadding=0 cellspacing=0 width1="250">
		<tr>
			<td bgcolor="#000000">
				<table cellpadding=2 cellspacing=1 border=0 width="100%">
					<tr>
						<td bgcolor1="#313380">
							<center>
							<font color="#ffffff" face="Verdana, Arial, Helvetica, sans-serif" size="3">
								<b>Procesando / Processing</b>
							</font><br> 
							<img src="web/images/await.gif" border="0" width="200" height="20"><br>
							<font size="2" color="#ffffff" face="Verdana, Arial, Helvetica, sans-serif">
								Espere por favor / One Moment Please
							</font>
							</center>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	</center>
</div>



    ';
    print $html_result;

}



?>

