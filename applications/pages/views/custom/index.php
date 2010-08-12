<?php if (!defined('APPLICATION')) exit();
$Session = Gdn::Session();
?>
<h1><?php echo T('Custom Pages'); ?></h1>
<div class="FilterMenu"><?php echo Anchor('Add Custom Page', 'pages/custom/add', 'AddPage Button'); ?></div>
<div class="Info"><?php
   echo T('Custom Pages gives you the opportunity to create your own pages integrated into the Garden framework.  Note that this is not (yet) integrated into the Menu manager!');
?></div>
<table id="CustomPagesTable" border="0" cellpadding="0" cellspacing="0" class="AltColumns">
   <thead>
      <tr id="0">
         <th><?php echo T('Page'); ?></th>
         <th class="Alt"><?php echo T('Title'); ?></th>
         <th><?php echo T('Options'); ?></th>
      </tr>
   </thead>
   <tbody>
<?php
foreach ($this->PageOverview as $Page) {
    ?>
    <tr>
       <td class="Info nowrap">
          <strong><?php echo Anchor($Page['Url'], '/page/'.$Page['Url'], 'PageUrl'); ?></strong>
       </td>
       <td class="Alt">
       	  <?php echo @$Page['Settings']['Title']; ?>
       </td>
       <td>
       	  <?php echo Anchor('Edit', '/pages/custom/edit/'.$Page['Url'], 'EditPage'); ?>
          <span>|</span>
          <?php echo Anchor('Delete', '/pages/custom/delete/'.$Session->TransientKey().'/'.$Page['Url'], 'DeletePage'); ?>
       </td>
    </tr>
<?php } ?>
   </tbody>
</table>