<?php
   echo $this->Form->create(NULL,array('url'=>'/users/edit/'.$id));
   echo $this->Form->control('username',['value'=>$username]);
   echo $this->Form->control('password',['value'=>$password]);
   echo $this->Form->button('Submit');
   echo $this->Form->end();
?>