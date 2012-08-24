<?php
session_start();
require_once('../includes/config.php');

/* Getting data from session */
if(isset($_SESSION['errors']))
	$errors = $_SESSION['errors'];
else $errors = array();

if(isset($_SESSION['old']))
	$old = $_SESSION['old'];

/* Form params */
$neededFields = array('name', 'email', 'message', 'subject');
$messages = array(
	'name'    => 'Name is missing',
	'email'   => 'Email is missing',
	'subject' => 'Subject is missing',
	'message' => 'Message is missing');

/* Page called with post method => form answered or someone is fooling around... */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$errors = array();
	$old = array();

	/* Check if all needed fields are filled */
	$errors = validateForm($neededFields, $messages, $old);

	/* Is email a valid email address */
	if(!isset($errors['email']) && !filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)){
		$errors['email'] = 'You need to set a valid email address.';
		/* Old value wasn't correct, don't display it*/
		unset($old['email']);
	}

	/* Set errors and old for session */
	$_SESSION['errors'] = $errors;
	$_SESSION['old'] = $old;

	/* No error => send mail*/
	if(sizeof($errors) == 0){

		/* Sanitize inputs */
		$name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
		$message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);
		$subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
		$mail = $_POST['mail'];

		/* Creating alerts array */
		$_SESSION['alerts'] = array();
		
		/* Setting mail header */
		$headers = 'From: ' . $name . ' <' . $mail . '>'."\r\n";

		if(mail($config['email'], $subject, wordwrap($message, 70), $headers)){
			$_SESSION['alerts'][] = array('type' => 'info', 'msg' => 'You\'re email has been sent.');

			/* Unset data we putted on session */
			unset($_SESSION['errors']);
			unset($_SESSION['old']);
		}
		else {
			$_SESSION['alerts'][] = array('type' => 'error', 'msg' => 'An error happend while trying to send the mail.');
		}

		/* Redirect */
		header('Location: ' . $_SERVER['REQUEST_URI']);
	}
	else {
		header('Location: #contact');
	}

	exit;
}
?>
<?php include('../includes/header.php');?>

		<?php if(isset($_SESSION['alerts']) && sizeof($_SESSION['alerts'])): ?>
		<div class="mod alert-container" >
		<?php foreach($_SESSION['alerts'] as $alert): ?>
			<p><?php echo $alert['msg']; ?></p>
		<?php endforeach; ?>
		</div>
		<?php unset($_SESSION['alerts']); endif; ?>

		<div class="mod">
		<p>Hi ! I'm Charles, a 23 years old french computer engineer, and <strong>I'm looking for a job</strong>.</p>
		</div>

		<div class="line gut">
			<section class="mod left w300p">
				<h2>You can find me on ...</h2>
				<ul class="elsewhere-links">
					<li><a href="https://twitter.com/Selrahcd">Twitter</a></li>
					<li><a href="https://github.com/SelrahcD">Github</a></li>
					<li><a href="http://www.lastfm.fr/user/SelrahcD">Last.fm</a></li>
				</ul>
			</section>
			<section class="mod item">
				<h2>Choripam ?</h2>
				<p>It's a shame .an doesn't exist anymore. In a perfect world this page should have been named chorip.an after the Argentinian sandwich.</p>
				<div class="txtcenter">
				<img src="img/choripan.jpg" class="w300p">
				</div>
			</section>
		</div>

		<section class="mod" id="contact">
			<h2>Contact</h2>
			<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
				<fieldset>
				<p class="input-container">
					<label for="name">Name</label>
					<?php echo (isset($errors['name']))? '<span class="error">'.$errors['name'].'</span>':'';?>
					<input id="name" type="text" name="name"<?php echo (isset($old['name']))? ' value="'.$old['name'].'"':'';?> />
				</p>
				<p class="input-container">
					<label for="email">Email</label>
					<?php echo (isset($errors['email']))? '<span class="error">'.$errors['email'].'</span>':'';?>
					<input type="email" id="email" name="email"<?php echo (isset($old['email']))? ' value="'.$old['email'].'"':'';?> />
				</p>
				<p class="input-container">
					<label for="subject">Subject</label>
					<?php echo (isset($errors['subject']))? '<span class="error">'.$errors['subject'].'</span>':'';?>
					<input type="text" id="subject" name="subject"<?php echo (isset($old['subject']))? ' value="'.$old['subject'].'"':'';?> />
				</p>
				<p class="input-container">
					<label for="message">Message</label>
					<?php echo (isset($errors['message']))? '<span class="error">'.$errors['message'].'</span>':'';?>
					<textarea id="message" name="message"><?php echo (isset($old['message']))? $old['message']:'';?></textarea>
				</p>
				<p class="input-container">
					<input type="submit" value="Send" />
				</p>
				</fieldset>
			</form>
		</section>
<?php include('../includes/footer.php');?>
<?php
function validateForm($fields = array(), $errors = array(), &$old = null ){
	$res = array();

	foreach($fields as $field){

		/* If field is missing */
		if(!filter_has_var(INPUT_POST, $field) || empty($_POST[$field])){
			
			/* Add custom message if provided*/
			if(array_key_exists($field, $errors)){
				$res[$field] = $errors[$field];
			}
			/* or add default message */
			else $res[$field] = $field . ' is missing.';
		}
		elseif(isset($old)){
			$old[$field] = $_POST[$field];
		}
	}

	return $res;
}

?>

