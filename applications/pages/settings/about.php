<?php
/**
 * An associative array of information about this application.
 */
$ApplicationInfo['Pages'] = array(
   'Description' => "Manage the menu and make your own integrated pages.",
   'Version' => '0.5',
   'RegisterPermissions' => array('Garden.Menu.Manage', 'Garden.Pages.Manage'), // Permissions that should be added to the application when it is installed.
   'SetupController' => 'setup',
   'AllowEnable' => true, // You can remove this when you create your own application (leaving it will make it so the application can't be enabled by Garden)
   'Author' => "Nick Edelen",
   'AuthorEmail' => 'sirnot@gmail.com',
   'AuthorUrl' => '',
   'License' => 'None!'
);