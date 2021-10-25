<!--
<?php
$this->disableAutoLayout();

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Not Found - Voler Admin Dashboard</title>
    <link rel="stylesheet" href="../../css/Admin/bootstrap.css">
    <link rel="stylesheet" href="../../css/Admin/main.css">

    <link rel="stylesheet" href="../../vendors/Admin/chartjs/Chart.min.css">

    <link rel="stylesheet" href="../../vendors/Admin/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="../../css/Admin/app.css">
    <link rel="stylesheet" href="../../css/Admin/cake.css">
    <link rel="shortcut icon" href="../../images/Admin/favicon.svg" type="image/x-icon">
</head>
<body>
    <div id="error">
<div class="container text-center pt-32">
    <h1 class='error-title'>500</h1>
    <p>we couldn't find the page you are looking for</p>
</div>

        <div class="footer pt-32">
            <p class="text-center">Copyright &copy; Voler 2020</p>
        </div>
    </div>
</body>
</html> -->

<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Database\StatementInterface $error
 * @var string $message
 * @var string $url
 */

use Cake\Core\Configure;
use Cake\Error\Debugger;

$this->layout = 'error';

if (Configure::read('debug')) :
	$this->layout = 'dev_error';

	$this->assign('title', $message);
	$this->assign('templateName', 'error500.php');

	$this->start('file');
?>
	<?php if (!empty($error->queryString)) : ?>
		<p class="notice">
			<strong>SQL Query: </strong>
			<?= h($error->queryString) ?>
		</p>
	<?php endif; ?>
	<?php if (!empty($error->params)) : ?>
		<strong>SQL Query Params: </strong>
		<?php Debugger::dump($error->params) ?>
	<?php endif; ?>
	<?php if ($error instanceof Error) : ?>
		<strong>Error in: </strong>
		<?= sprintf('%s, line %s', str_replace(ROOT, 'ROOT', $error->getFile()), $error->getLine()) ?>
	<?php endif; ?>
<?php
	echo $this->element('auto_table_warning');

	$this->end();
endif;
?>
<h2><?= __d('cake', 'An Internal Error Has Occurred.') ?></h2>
<p class="error">
	<strong><?= __d('cake', 'Error') ?>: </strong>
	<?= h($message) ?>
</p>