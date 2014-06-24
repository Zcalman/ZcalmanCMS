<?PHP
// Plugin funktioner på hemsidan


function zc_add_action($where, $func)
{
	// Standard områden: htmlhead, bodytop*, beforetop*, aftertop*, beforemenu*, aftermenu*, beforecontent, aftercontent, beforefooter, afterfooter*, bodybottom*
	// * = måste läggas till av temaskaparen
	global $actionareas;
	global $pluginmeta;
	
	$action = new Action($pluginmeta['slug'], $where, $func);
	if(array_key_exists($where, $actionareas))
	{
		$actionareas[$where]->add_action($action);
		return true;
	}
	else
	{
		$ac = new ActionControl($where);
		$ac->add_action($action);
		$actionareas[$where] = $ac;
		return true;
	}
	return false;
}

function zc_action_control($where)
{
	global $actionareas;
	
	if(array_key_exists($where, $actionareas))
	{
		$ac = $actionareas[$where];
		foreach($ac->actions as $action)
		{
			if(function_exists($action->func))
			{
				$func = $action->func;
				$func();
			}
		}
	}
}

function zc_add_page($pageslug, $pagefile, $pagetitle)
{
	global $pluginpages;
	global $pluginmeta;
	
	if(!array_key_exists($pageslug, $pluginpages))
	{
		$p = new PluginPage($pluginmeta['slug'], $pageslug, $pagefile, $pagetitle, $pluginmeta['type']);
		$pluginpages[$pageslug] = $p;
		return true;
	}
	return false;
}
?>