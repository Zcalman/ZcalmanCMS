<?PHP
if(!isset($host))
{
	header('HTTP/1.1 404 Not Found');
}
?>
<div id="menybar">
<?PHP
global $publish_menu;
global $design_menu;
global $settings_menu;
global $plugin_menu;

if(count($publish_menu->links) > 0)
{
	?>
<div id="publish_menu" class="menublock">
    <div class="menuarrow">
    	Publicera
    </div>
    <div class="menucontent">
    	<ul>
        	<?PHP 
			array_walk($publish_menu->links, "print_menu");
			?>
        </ul>
    </div>
</div>
	<?PHP
}

if(count($design_menu->links) > 0)
{
	?>
<div id="design_menu" class="menublock">
    <div class="menuarrow">
    	Utseende
    </div>
    <div class="menucontent">
    	<ul>
        	<?PHP 
			array_walk($design_menu->links, "print_menu");
			?>
        </ul>
    </div>
</div>
	<?PHP
}

if(count($settings_menu->links) > 0)
{
	?>
<div id="settings_menu" class="menublock">
    <div class="menuarrow">
    	Inställningar
    </div>
    <div class="menucontent">
    	<ul>
        	<?PHP 
			array_walk($settings_menu->links, "print_menu");
			?>
        </ul>
    </div>
</div>
	<?PHP
}

if(count($plugin_menu->links) > 0)
{
	?>
<div id="plugin_menu" class="menublock">
    <div class="menuarrow">
    	Tillägg
    </div>
    <div class="menucontent">
    	<ul>
        	<?PHP 
			array_walk($plugin_menu->links, "print_menu");
			?>
        </ul>
    </div>
</div>
	<?PHP
}

foreach($menus as $menu)
{
	?>
<div id="extra_menu" class="menublock">
    <div class="menuarrow">
    	<?PHP echo $menu->title; ?>
    </div>
    <div class="menucontent">
    	<ul>
        	<?PHP 
			array_walk($menu->links, "print_extramenu");
			?>
        </ul>
    </div>
</div>
	<?PHP
}
?>
</div>