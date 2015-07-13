<?php
require_once ('./header.php');
//logged_in_only ();

$delete = set_post_string_var ('delete');
$create = set_post_string_var ('create');
$new_username = set_post_string_var ('new_username');
$new_password = set_post_string_var('new_password');
$new_admin = set_post_bool_var ('new_admin', false);
$existing_user = set_post_string_var ('existing_user');
$noconfirm = set_get_noconfirm ();

$message1 = '';
$message2 = '';
?>

<h1 id="caption">Admin Page</h1>

<div style="max-width: 27px;margin: 15% auto;min-width: 179px;">
	<div class="text-center">
	
	<?php
	
	if ($create == 'Create') {
		if ($new_username == '' || $new_password == '') {
			$message1 = 'Username and Password fields must not be empty.';
		}
		else if (check_username ($new_username)) {
			$message1 = 'User already exists.';
		}
		else {
			$query = sprintf ("INSERT INTO user (username, password, admin) VALUES ('%s', md5('%s'), '%d')", 
					$mysql->escape ($new_username),
					$mysql->escape ($new_password),
					$mysql->escape ($new_admin));
	
			if ($mysql->query ($query)) {
				$message1 = "User $new_username created.";
			}
			else {
				message ($mysql->error);
			}
			unset ($new_password, $_POST['new_password']);
		}
	}
	?>
	
				<div>
					<h2 class="caption">Register</h2>
	
					<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="POST">
						<table>
							<tr>
								
								<td>
									<input type="text" name="new_username" placeholder="Username">
								</td>
							</tr>
	
							<tr>
								
								<td>
									<input type="password" name="new_password" placeholder="Password">
								</td>
							</tr>
	
							<tr>
								
								<td>
									<input type="submit" name="create" value="Create"> <?php echo $message1; ?>
								</td>
								
							</tr>
							<td>Old User? <a href="./index.php">Login!</a></td>
						</table>
					</form>
	
				</div>

		</div>

<?php
print_footer ();
require_once (ABSOLUTE_PATH . 'footer.php');
?>
