<?php if (!defined('APPLICATION')) exit();

// Conversations
$Configuration['Conversations']['Version'] = '2.0.1';

// Database
$Configuration['Database']['Name'] = 'v42_vanilla';
$Configuration['Database']['Host'] = 'localhost';
$Configuration['Database']['User'] = 'root';
$Configuration['Database']['Password'] = 'root';

// EnabledApplications
$Configuration['EnabledApplications']['Conversations'] = 'conversations';
$Configuration['EnabledApplications']['Vanilla'] = 'vanilla';
$Configuration['EnabledApplications']['Skeleton'] = 'skeleton';

// EnabledPlugins
$Configuration['EnabledPlugins']['GettingStarted'] = 'GettingStarted';
$Configuration['EnabledPlugins']['Voting'] = 'Voting';
$Configuration['EnabledPlugins']['Tagging'] = 'Tagging';
$Configuration['EnabledPlugins']['Flagging'] = 'Flagging';
$Configuration['EnabledPlugins']['Textile'] = 'Textile';
$Configuration['EnabledPlugins']['HtmLawed'] = 'HtmLawed';
$Configuration['EnabledPlugins']['Emoticons'] = 'Emoticons';

// Garden
$Configuration['Garden']['Title'] = 'Vanilla 2';
$Configuration['Garden']['Cookie']['Salt'] = 'QFV78NOVII';
$Configuration['Garden']['Cookie']['Domain'] = '';
$Configuration['Garden']['Version'] = '2.0.1';
$Configuration['Garden']['RewriteUrls'] = FALSE;
$Configuration['Garden']['CanProcessImages'] = TRUE;
$Configuration['Garden']['Installed'] = TRUE;
$Configuration['Garden']['Errors']['MasterView'] = 'error.master.php';
$Configuration['Garden']['RequiredUpdates'] = 's:279:"a:2:{i:0;a:3:{s:4:"Name";s:8:"Gravatar";s:4:"Type";s:6:"Plugin";s:7:"Version";s:5:"0.1.2";}i:1;a:3:{s:4:"Name";s:7:"Vanilla";s:4:"Type";s:11:"Application";s:7:"Version";s:5:"2.0.1";}}";';
$Configuration['Garden']['UpdateCheckDate'] = 1281553909;
$Configuration['Garden']['EditContentTimeout'] = '-1';
$Configuration['Garden']['InputFormatter'] = 'markdown';

// Plugins
$Configuration['Plugins']['GettingStarted']['Plugins'] = '1';
$Configuration['Plugins']['GettingStarted']['Discussion'] = '1';
$Configuration['Plugins']['GettingStarted']['Categories'] = '1';
$Configuration['Plugins']['GettingStarted']['Profile'] = '1';
$Configuration['Plugins']['Flagging']['Enabled'] = TRUE;
$Configuration['Plugins']['Tagging']['Enabled'] = TRUE;

// Routes
$Configuration['Routes']['DefaultController'] = 'discussions';

// Vanilla
$Configuration['Vanilla']['Version'] = '2.0.1';
$Configuration['Vanilla']['Comments']['AutoOffset'] = FALSE;
$Configuration['Vanilla']['Comments']['AutoRefresh'] = '0';
$Configuration['Vanilla']['Comments']['PerPage'] = '50';
$Configuration['Vanilla']['Discussions']['PerPage'] = '30';
$Configuration['Vanilla']['Archive']['Date'] = '';
$Configuration['Vanilla']['Archive']['Exclude'] = FALSE;
$Configuration['Vanilla']['Categories']['Use'] = TRUE;

// Last edited by admin (127.0.0.1)2010-08-11 20:15:19
