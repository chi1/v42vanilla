<?php if (!defined('APPLICATION')) exit();
$Session = Gdn::Session();
?>
<h1><?php echo T('Manage the Menu'); ?></h1>
<div class="FilterMenu"><?php echo Anchor('Add Menu Item', 'pages/menu/add', 'AddMenuItem Button'); ?></div>
<div class="Info"><?php
   echo T('Here you can arrange the menu order, and add your own custom menu items.  The menu consists of items (the actual links) organized into groups.  Unfortunately due to the nature of Garden builtin groups are not editable, and not all builtin group items may be displayed.  Also, note that this application collects builtin groups as Garden is used, so no builtin groups will be displayed until the main site is visited.');
?></div>
<table id="MenuFilterTable" border="0" cellpadding="0" cellspacing="0" class="AltColumns MenuSortable">
   <thead>
      <tr id="0">
         <th><?php echo T('Menu Group'); ?></th>
         <th class="Alt"><?php echo T('Group Items'); ?></th>
      </tr>
   </thead>
   <tbody>
<?php
foreach ($this->MenuOrder as $Group) {
    $GroupItem = &$this->MenuData[$Group];
    ?>
    <tr id="<?php echo $Group ?>" class="MenuGroup">
       <td class="MenuGroup Info nowrap <?php echo $GroupItem['Enabled'] ? 'Enabled' : 'Disabled' ?>">
          <h4><?php echo $Group; ?></h4>
          <div><?php echo $GroupItem['Builtin'] ? T('Builtin') : T('Custom') ?>
          <span> | </span>
          <?php echo Anchor($GroupItem['Enabled'] ? 'Disable' : 'Enable', '/pages/menu/toggle/'.$Group.'/'.$Session->TransientKey(), 'ToggleMenuGroup'); ?></div>
       </td>
       <td class="MenuItems Alt">
         <table id="MenuItemsTable_<?php echo $Group ?>" border="0" cellpadding="0" cellspacing="0" class="AltRows <?php echo !$GroupItem['Builtin'] ? 'MenuSortable' : 'Builtin'; ?> MenuItemsTable">
            <tbody>
<?php
    $Alt = true;
    foreach ($GroupItem['Order'] as $Index) {
    	$Item = $GroupItem[$Index];
        $Alt = $Alt ? false : true;
        ?>
              <tr id="<?php echo $Index ?>" class="MenuItem <?php echo $Alt ? 'Alt' : '' ?>">
                <td class="Info">
                  <strong><?php echo $Item['Text'] ?></strong> - <?php echo $Item['Url'] ?>
        <?php if (!$GroupItem['Builtin']) { ?>
    			  <div><?php 
            echo Anchor('Edit', '/pages/menu/edit/'.$Group.'/'.$Index, 'EditMenuItem'); ?>
                  <span>|</span><?php
            echo Anchor('Delete', '/pages/menu/delete/'.$Group.'/'.$Index.'/'.$Session->TransientKey(), 'DeleteMenuItem'); ?></div>
        <?php } ?>
                </td>
              </tr>
    <?php } ?>
            </tbody>
         </table>
    <?php if (!$GroupItem['Builtin']) { ?>
         <div><?php echo Anchor('Add', '/pages/menu/add/'.$Group, 'AddMenuItem'); ?></div>
    <?php } ?>
       </td>
    </tr>
<?php } ?>
   </tbody>
</table>
