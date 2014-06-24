<?PHP
$publish_menu = new Menu("publish_menu", "Publicera", false);
$publish_menu->addLink("Nyheter", "news/", true);
$publish_menu->addLink("Sidor", "pages/", true);
$publish_menu->addLink("Formulär", "forms/", true);
$publish_menu->addLink("Dokument", "documents/", true);

$design_menu = new Menu("design_menu", "Utseende", false);
$design_menu->addLink("Meny", "menus/", true);
$design_menu->addLink("Teman", "themes/", true);

$settings_menu = new Menu("settings_menu", "Inställningar", false);
$settings_menu->addLink("Allmänna inställningar", "general-settings/", true);
$settings_menu->addLink("Användare", "users/", true);
$settings_menu->addLink("Mitt konto", "my-settings/", true);

$plugin_menu = new Menu("plugin_menu", "Tillägg", false);
$plugin_menu->addLink("Hantera tillägg", "plugins/", true);

?>

