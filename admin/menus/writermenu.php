<?PHP
$publish_menu = new Menu("publish_menu", "Publicera", false);
$publish_menu->addLink("Nyheter", "news/", true);
$publish_menu->addLink("Dokument", "documents/", true);

$design_menu = new Menu("design_menu", "Utseende", false);

$settings_menu = new Menu("settings_menu", "Inställningar", false);
$settings_menu->addLink("Mitt konto", "my-settings/", true);

$plugin_menu = new Menu("plugin_menu", "Tillägg", false);


?>

