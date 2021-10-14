<?php
/**
 * @var \App\View\AppView $this
 * @var array $params
 * @var string $message
 */
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div id="message">
    <div class="message success" onclick="this.classList.add('hidden')" ><?= $message ?></div>
</div>

<!-- <script>
function myFunction() {
  setTimeout(function(){
    var myobj = document.getElementById("demo");
    myobj.remove();
  }, 3000);
}
</script> -->
