<?
	$getuses = $_GET['do'];
	require_once('connect.php');
?>
<?
	echo '<!doctype html>';
	echo '<html>';
	echo '<head>';
	echo "<link rel='stylesheet' href='css/style.css'>";
	echo "<script  src='js/jquery.min.js'></script>";
	echo '<link rel="stylesheet" type="text/css" href="css/default.css" />
		<link rel="stylesheet" type="text/css" href="css/component.css" />
		<script src="js/modernizr.custom.js"></script>';
	echo '<title>Сайт пятерочки</title>';
	echo '</head><body>';
?>
<?
	echo "<div class='container'>";
	echo "<header class='header'><h2 class='logo'><a href='/'><img src='images/logo.svg'></a><div class='header_cool'>
                <div class='header_contacts'>
                        <div class='header-contacts_info'>
                            <a href='tel:8-800-555-55-05' class='header_telephone title'>8-800-555-55-05</a>
                        </div>
                        <div class='header_smalltext'>Горячая линия</div>
                </div>
            </div></h2><div class='header_cool'><div class='header_info2'><a href='?do=login'><img src='images/login.svg' width='30px'></a></div></div>
			<div id='sb-search' class='sb-search'>
						<form>
							<input class='sb-search-input' placeholder='Что будет искать?' type='text' value='' name='search' id='search'>
							<input class='sb-search-submit' type='submit' value=''>
							<span class='sb-icon-search'></span>
						</form>
					</div>
			</header><br>";
	echo "<script src='js/classie.js'></script>
		<script src='js/uisearch.js'></script>
		<script>
			new UISearch( document.getElementById( 'sb-search' ) );
		</script>";
	if($getuses == '')
	{
		echo "<section class='intro'><div class='column'>
      <h3>Автор пятерочки</h3>
      <img src='images/profile.jpeg' alt='' class='profile'> </div>
    <div class='column'>
	  <p>В 1998 году Андрей Рогачев вместе с партнерами зарегистрировал ООО «Агроторг». В феврале 1999 года компания открыла первый магазин в Петербурге, а к концу года их было уже 16. В 2001 «Пятерочка» вышла в Москву. В период между 2001 и 2004 годами миноритарным акционером «Агроторга» стал Европейского банка реконструкции и развития кредитовавший компанию.</p>
      <p>По завершению своей инвестропрограммы банк продал свой пакет акций другим собственникам компании. Сейчас сеть насчитывает 442 точек (из которых 207 открыто по франчайзингу).</p>
    </div></section>";
		echo "<h1 class='color_text' align='center'>Специальные предложения</h1><hr>";
		$query = "SELECT * FROM `news`";
		$result = mysqli_query($base, $query);
		echo "<div class='gallery'>";
		while($row = mysqli_fetch_array($result))
		{
				echo "<div class='thumbnail'><a href='".$row['url']."' target='_blank'><img src='".$row['image']."' alt='' class='cards' width='2000'></a>
      			<h4>".$row['title']."</h4>
      			<p class='color_text'>".$row['message']."";
				echo "</div>";
		}
	}
?>
<?
	if($getuses == 'reg')
	{
		echo "<center><h1 class='color_text'>Форма регистрации</h1></center>";
		echo "<center>";
		echo "<form method='POST' class='color_text'>";
		echo "Логин <input name='login' type='text' required><br>";
		echo "Пароль <input name='password' type='password' required><br>";
		echo "<input name='submit' type='submit' value='Зарегистрироваться'>";
		echo "</form>";
		echo "</center>";
		if(isset($_POST['submit']))
		{
			$err = [];
  			if(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['login']))
    		{
        		$err[] = "Логин может состоять только из букв английского алфавита и цифр";
			}
    		if(strlen($_POST['login']) < 3 or strlen($_POST['login']) > 30)
    		{
        		$err[] = "Логин должен быть не меньше 3-х символов и не больше 30";
    		}
    		$query = mysqli_query($base, "SELECT user_id FROM users WHERE user_login='".mysqli_real_escape_string($base, $_POST['login'])."'");
    		if(mysqli_num_rows($query) > 0)
    		{
        		$err[] = "Пользователь с таким логином уже существует в базе данных";
    		}
    		if(count($err) == 0)
    		{
        		$login = $_POST['login'];
        		$password = md5(md5(trim($_POST['password'])));
        		mysqli_query($base,"INSERT INTO users SET user_login='".$login."', user_password='".$password."'");
        		header("Location: ?do=login"); exit();
    		}
    		else
    		{
        		print "<b>При регистрации произошли следующие ошибки:</b><br>";
        		foreach($err AS $error)
        		{
            		print $error."<br>";
        		}
    		}
		}
	}
	if($getuses == 'login')
	{
		echo "<center><h1 class='color_text'>Форма авторизации</h1></center>";
		echo "<center><form method='POST' class='color_text'>";
		echo "Логин <input name='login' type='text' required><br>";
		echo "Пароль <input name='password' type='password' required><br>";
		echo "Не прикреплять к IP(не безопасно) <input type='checkbox' name='not_attach_ip'><br>";
		echo "<input name='submit' type='submit' value='Войти'>";
		echo "</form>";
		if(isset($_POST['submit']))
		{
			$query = mysqli_query($base,"SELECT user_id, user_password FROM users WHERE user_login='".mysqli_real_escape_string($base,$_POST['login'])."' LIMIT 1");
    		$data = mysqli_fetch_assoc($query);
    		if($data['user_password'] === md5(md5($_POST['password'])))
    		{
        		if(!empty($_POST['not_attach_ip']))
        		{
            		$insip = ", user_ip=INET_ATON('".$_SERVER['REMOTE_ADDR']."')";
        		}
        		mysqli_query($link, "UPDATE users SET user_hash='".$hash."' ".$insip." WHERE user_id='".$data['user_id']."'");
        		setcookie("id", $data['user_id'], time()+60*60*24*30);
        		header("Location: ?do=check"); exit();
    		}
    		else
    		{
        		print "<div class='color_text'>Вы ввели неправильный логин/пароль</div>";
    		}
		}
	}
	if($getuses == 'check')
	{
		if (isset($_COOKIE['id']))
		{
			$query = mysqli_query($base, "SELECT *,INET_NTOA(user_ip) AS user_ip FROM users WHERE user_id = '".intval($_COOKIE['id'])."' LIMIT 1");
    		$userdata = mysqli_fetch_assoc($query);
			if(($userdata['user_id'] !== $_COOKIE['id']))
			{
				setcookie("id", "", time() - 3600*24*30*12, "/");
        		print "<div class='color_text'>Хм, что-то не получилось</div>";
    		}
    		else
    		{
        		echo "<center><div class='color_text'>Привет, ".$userdata['user_login'].". Всё работает!</center></div>";
				header("Location: /"); exit();
    		}
		}		
		else
		{
    		print "<center><div class='color_text'>Изменение кук запрещенно!</div></center>";
		}
	}
?>