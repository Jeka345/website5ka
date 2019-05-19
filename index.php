<?php
$getuses = $_GET['do'];
$target = $_GET['target'];
$id_news = $_GET['id'];
require_once('connect.php');
echo '<!doctype html>';
echo '<html>';
echo '<head>';
echo "<link rel='stylesheet' href='css/style.css'>";
echo "<script  src='js/jquery.min.js'></script>";
echo '<link rel="stylesheet" type="text/css" href="css/default.css" /><link rel="stylesheet" type="text/css" href="css/component.css" /><script src="js/modernizr.custom.js"></script>';
echo '<title>Сайт пятерочки</title>';
echo '</head><body>';
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
						<input class='sb-search-input' placeholder='Что будем искать?' type='input' value='' name='search' id='search'>
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
if($_SERVER['REQUEST_URI'] == '/')
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
			echo "<div class='thumbnail'><a href='".$row['url']."' target='_blank'><img src='".$row['image']."' alt='' class='cards'></a>
			<h4><font color='black'>Заголовок новости:</font> ".$row['title']."</h4>
			<p><font color='black'>Полный текст новости:</font> ".$row['message']."</p>";
			if(isset($_COOKIE['adminright']))
			{
				echo "<hr>";
				echo "<div class='color_text'>Работа с новостями</div><br>";
				echo "<a href='?do=admin&target=editnews&id=".$row['id_news']."' target='_blank' class='color_text'><img src='images/edit_icon.png' width='64px' alt='Редактировать новость'></a>";
				echo "<a href='?do=admin&target=deletenews&id=".$row['id_news']."' target='_blank' class='color_text'><img src='images/delete_icon.png' alt='Удалить новость'></a>";
			}
			echo "</div>";
	}
}
if($_GET['search'])
{
	echo "<h1 class='color_text' align='center'>Информационный запрос</h1><hr>";
	$query = "SELECT message, url, image FROM `news` WHERE title='".$_GET['search']."'";
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
if($getuses == 'logout')
{
	echo "<div class='color_text'><center>Вы успешно вышли с сайта, до скорой встречи!</center></div>";
	echo "<div class='color_text'><center>Вы будете перенаправлены на главную страницу через 5 секунд</center></div>";
	setcookie ("id",$data['user_id'],time()-60*60*24*30,"/");
	header("refresh:5; url=/");
}
if($getuses == 'admin')
{
	if(isset($_COOKIE['adminright']))
	{
		echo "<center><h1 class='color_text'>Панель управления сайта</center><hr><br>";
		echo "<div id='nav' class='color_text'>
		<ul>
			<li><a href='?do=admin'>Главная страница</a></li>
			<li><a href='#'>Работа с новостями</a>
			<ul>
   				<li><a href='?do=admin&target=addnews'>Добавить новость</a></li>
		   		<li><a href='#'>Редактировать новость</a></li>
				<li><a href='#'>Удалить новость</a></li>
   			</ul>
			</li>
			<li><a href='#'>Работа с сайтом</a>
   			<ul>
			   	<li><a href='?do=admin&target=addadmin'>Добавление администратора</a></li>
			   	<li><a href='?do=admin&target=edtadmin'>Редактирования администратора</a></li>
			   	<li><a href='?do=admin&target=deladmin'>Удаления администратора</a></li>
   			</ul>
			</li>
				<li><a href='#'>О нас</a></li>
			</ul>   
			</div><hr><br>";
		if($getuses == 'admin' && $target == 'addnews')
		{
			echo "<center><h2 class='color_text'>Добавление новой новости</h2></center><hr><br>";
			echo "<center><form method='POST' class='color_text'>";
			echo "Заголовок новости: <input name='title' type='text' required><p>";
			echo "Полный текст новости: <input name='message' type='text' required><p>";
			echo "Ссылка на новость: <input name='url' type='url' required><p>";
			echo "Картинка новости (не обязательно): <input name='image' type='text'><p>";
			echo "<input name='submit' type='submit' value='Добавить новость'><br></form><br></div>";
			if(isset($_POST['submit']))
			{
				$query = mysqli_query($base, "SELECT news_id FROM news WHERE title='".mysqli_real_escape_string($base, $_POST['title'])."'");
				if(mysqli_num_rows($query) > 0)
				{
					$err[] = "<div class='color_text'><center>Данная новость уже существует, пожалуйста введите другой заголовок для новости</center></div>";
				}
				if(count($err) == 0)
				{
					$title = $_POST['title'];
					$message = $_POST['message'];
					$url = $_POST['url'];
					$image = $_POST['image'];
					mysqli_query($base,"INSERT INTO news SET title='".$title."', message='".$message."', url='".$url."', image='".$image."'");
					header("Location: ?do=admin"); exit();
				}
			}
		}
		elseif($getuses == 'admin' && $target == 'editnews')
		{
			$query = mysqli_query($base, "SELECT * FROM `news` WHERE id_news='".mysqli_real_escape_string($base, $_GET['id'])."' LIMIT 1");
			$result = mysqli_fetch_assoc($query);
			echo "<center><h2 class='color_text'>Редактирования новости</h2></center><hr><br>";
			echo "<center><form method='POST' class='color_text'>";
			echo "Заголовок новости: <input name='title' type='text' value='".$result['title']."' required><p>";
			echo "Полный текст новости: <input name='message' type='text' value='".$result['message']."' required><p>";
			echo "Ссылка на новость: <input name='url' type='url' value='".$result['url']."' required><p>";
			echo "Картинка новости (не обязательно): <input name='image' value='".$result['image']."' type='text'><p>";
			echo "<input name='submit' type='submit' value='Редактировать новость'><br></form><br></div>";
			if(isset($_POST['submit']))
			{
				$title = $_POST['title'];
				$message = $_POST['message'];
				$url = $_POST['url'];
				$image = $_POST['image'];
				$query = mysqli_query($base, "UPDATE news SET title='".$title."', message='".$message."', url='".$url."', image='".$image."' WHERE id_news='".mysqli_real_escape_string($base,$_GET['id'])."' LIMIT 1");
				header("Location: ?do=admin"); exit();
			}
		}
		elseif($getuses == 'admin' && $target == 'deletenews')
		{
			$query = mysqli_query($base, "SELECT * FROM `news` WHERE id_news='".mysqli_real_escape_string($base, $_GET['id'])."' LIMIT 1");
			$result = mysqli_fetch_assoc($query);
			echo "<center><h2 class='color_text'>Удаления новости</h2></center><hr><br>";
			echo "<center><form method='POST' class='color_text'>";
			echo "Заголовок новости: <input name='title' type='text' value='".$result['title']."' required><p>";
			echo "Полный текст новости: <input name='message' type='text' value='".$result['message']."' required><p>";
			echo "Ссылка на новость: <input name='url' type='url' value='".$result['url']."' required><p>";
			echo "Картинка новости (не обязательно): <input name='image' value='".$result['image']."' type='text'><p>";
			echo "<input name='submit' type='submit' value='Удалить новость'><br></form><br></div>";
			if(isset($_POST['submit']))
			{
				$query = mysqli_query($base, "DELETE FROM news WHERE id_news='".mysqli_real_escape_string($base,$_GET['id'])."' LIMIT 1");
				header("Location: ?do=admin"); exit();
			}
		}
		if($getuses == 'admin' && $target == 'addadmin')
		{
			echo "<center><h2 class='color_text'>Добавление нового администратора</h2></center><hr><br>";
			echo "<center><form method='POST' class='color_text'>";
			echo "Логин: <input name='login' type='text' required><p>";
			echo "Пароль: <input name='password' type='password' required><p>";
			echo "<input name='submit' type='submit' value='Добавить нового администратора'><p></form><br></div>";
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
				if(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['password']))
				{
					$err[] = "Пароль может состоять только из букв английского алфавита, и цифр";
				}
				if(strlen($_POST['password']) < 6 or strlen($_POST['password']) > 32)
				{
					$err[] = "Пароль должен быть не меньше 6-х символов и не больше 32";
				}
				$query = mysqli_query($base, "SELECT admin_id FROM admins WHERE username='".mysqli_real_escape_string($base, $_POST['login'])."'");
				if(mysqli_num_rows($query) > 0)
				{
					$err[] = "Такой администратор уже существует в базе данных";
				}
				if(count($err) == 0)
				{
					$login = $_POST['login'];
					$password = md5(md5(trim($_POST['password'])));
					mysqli_query($base,"INSERT INTO admins SET username='".$login."', password='".$password."'");
					header("Location: ?do=admin"); exit();
				}
				else
				{
					print "<b>При добавлении администратора произошли следующие ошибки:</b><br>";
					foreach($err AS $error)
					{
						print $error."<br>";
					}
				}
			}
		}
		elseif($getuses == 'admin' && $target == 'edtadmin')
		{
			echo "<center><h2 class='color_text'>Редактировать администратора</h2></center><hr><br>";
			echo "<center><form method='POST' class='color_text'>";
			echo "Логин: <input name='login' type='text' required><p>";
			echo "Пароль: <input name='password' type='password' required><p>";
			echo "<input name='submit' type='submit' value='Редактировать администратора'><p></form><br></div>";
			if(isset($_POST['submit']))
			{
				$login = $_POST['login'];
				$password = md5(md5(trim($_POST['password'])));
				$query = mysqli_query($base, "UPDATE admins SET username='".$login."', password='".$password."' WHERE username='".mysqli_real_escape_string($base,$_POST['login'])."' LIMIT 1");
				header("Location: ?do=admin"); exit();
			}
		}
		elseif($getuses == 'admin' && $target == 'deladmin')
		{
			echo "<center><h2 class='color_text'>Удаление администратора</h2></center><hr><br>";
			echo "<center><form method='POST' class='color_text'>";
			echo "Логин: <input name='login' type='text' required><p>";
			echo "<input name='submit' type='submit' value='Удалить администратора'><p></form><br></div>";
			if(isset($_POST['submit']))
			{
				$query = mysqli_query($base, "DELETE FROM admins WHERE username='".mysqli_real_escape_string($base,$_POST['login'])."' LIMIT 1");
				header("Location: ?do=admin"); exit();
			}
		}
	}
	elseif(is_null($_COOKIE['adminright']))
	{
		echo "<center><h1 class='color_text'>Авторизация в панель администратора</center><hr><br>";
		echo "<center><form method='POST' class='color_text'>";
		echo "Логин: <input name='adminlogin' type='text' required><p>";
		echo "Пароль: <input name='password' type='password' required><p>";
		echo "Запомнить меня: <input type='checkbox' name='memorize'><p>";
		echo "<input name='submit' type='submit' value='Авторизироваться'><p></form><p></div>";
		if(isset($_POST['submit']))
		{
			$query = mysqli_query($base,"SELECT admin_id, password FROM admins WHERE username='".mysqli_real_escape_string($base,$_POST['adminlogin'])."' LIMIT 1");
			$data = mysqli_fetch_assoc($query);
			if($data['password'] === md5(md5($_POST['password'])))
			{
				if(!empty($_POST['memorize']))
				{
					setcookie("adminright", $data['admin_id'], 0);
				}
				setcookie("adminright", $data['admin_id'], time()+60*60*24*30);
				header("refresh:5; url=?do=admin"); exit();
			}
			else
			{
				print "<div class='color_text'>Вы ввели неправильный логин/пароль либо у вас отсутствуют административные права</div>";
			}
		}
	}
}
if($getuses == 'login')
{
	if(isset($_COOKIE['id']))
	{
		echo "<div class='color_text'><center>Вы уже авторизованы на сайте</center></div><br>";
		echo "<div class='color_text'><center><a href='?do=logout' style='color: black; -moz-appearance: button; -webkit-appearance: button; padding: .2em .75em; text-decoration: none'>Выйти с сайта</div></center></a><br><hr><br>";
		echo "<div class='color_text'><center><a href='?do=admin' target='_blank' style='color: black; -moz-appearance: button; -webkit-appearance: button; padding: .2em .75em; text-decoration: none'>Вход для администратора</div></center></a><br>";
	}
	elseif(is_null($_COOKIE['id']))
	{
		echo "<center><h1 class='color_text'>Форма авторизации</h1></center>";
		echo "<center><form method='POST' class='color_text'>";
		echo "Логин <input name='login' type='text' required><p>";
		echo "Пароль <input name='password' type='password' required><p>";
		echo "Не прикреплять к IP(не безопасно): <input type='checkbox' name='not_attach_ip'><p>";
		echo "<input name='submit' type='submit' value='Войти'><p>";
		echo "</form><hr><br>";
		echo "<div class='color_text'><center><a href='?do=admin' target='_blank' style='color: black; -moz-appearance: button; -webkit-appearance: button; padding: .2em .75em; text-decoration: none'>Вход для администратора</div></center></a><br>";
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
				mysqli_query($base, "UPDATE users SET user_hash='".$hash."' ".$insip." WHERE user_id='".$data['user_id']."'");
				setcookie("id", $data['user_id'], time()+60*60*24*30);
				header("Location: ?do=check"); exit();
			}
			else
			{
				print "<div class='color_text'>Вы ввели неправильный логин/пароль</div>";
			}
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
echo "</body></html>"
?>