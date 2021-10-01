<?php
   echo $this->Form->create(NULL,array('url'=>'/users/add'));
   echo $this->Form->control('username');
   echo $this->Form->control('password');

   ?>
   <b>Role<b>
   <select name="role_id" id="">
      <?php
      foreach ($rolesTable as $role) {?>
         <option value="<?php echo $role['id'] ?>"><?php echo $role['role_name'] ?></option>
      <?php
      }
      ?>
   </select>
   <?php
   echo $this->Form->button('Submit');
   echo $this->Form->end();
?>
