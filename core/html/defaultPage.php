<?php
	
	define('WTA_AD_LOGIN', 'AD Вход');
	define('WTA_WTA_LOGIN', 'WTA Вход');
	define('WTA_FATAL_ERROR', 'Что-то сломалось. Позвоните: 29-07-77 или 27-77');
	
	class DefaultPage extends HtmlPage {
		private $userpanel;
		private $menu;
		private $notice;
		private $main;
		
		public function __construct(string $title = 'WBaseAnalyser') {
			$style = '/core/html/style.css';
			$charset = 'utf-8';
			Parent::__construct($title, $style, $charset);
			
			$bodydiv = new HtmlDiv('body');
			Parent::add_Element($bodydiv);
			
			$this->userpanel = new HtmlDiv('userpanel');
			$bodydiv->add_Element($this->userpanel);
			
			$logodiv = new HtmlDiv('logo');
			$bodydiv->add_Element($logodiv);
			
			#$logo = new HtmlAImg('/', '/core/html/logo.png', 'HOME');
			$logo = new HtmlA('/', 'Главная');
			$logodiv->add_Element($logo);
			
			$columndiv = new HtmlDiv('column');
			$bodydiv->add_Element($columndiv);
			
			$this->menu = new HtmlDPC('menu', 'menuitem');
			$columndiv->add_Element($this->menu);
			
			$panediv = new HtmlDiv('pane');
			$columndiv->add_Element($panediv);
			
			$this->notice = new HtmlDPC('notice', 'noticeitem');
			$panediv->add_Element($this->notice);
			
			$this->main = new HtmlDiv('main');
			$panediv->add_Element($this->main);
			
			$bottomdiv = new HtmlDiv('bottom');
			$bodydiv->add_Element($bottomdiv);
			$bottomdiv->add_Text('&copy;&nbsp;Wratixor');
			
			$this->add_Menu('/configurator.php', 'Конфигурация подключений');
			$this->add_Menu('/constructor.php', 'Конструктор запросов');
			$this->add_Menu('/sql_manager.php', 'Менеджер запросов');
			$this->add_Menu('/phpinfo.php', 'phpinfo()');
			$this->add_Menu('/configs/main.ini', 'Доступ запрещён');
			
		}
		
		public function add_Element(HtmlElement $element) {
			$this->main->add_Element($element);
		}
		public function add_Text(string $str) {
			$this->main->add_Text($str);
		}
		public function add_Menu(string $url, string $name) {
			$this->menu->add_A($url, $name);
		}
		public function add_Notice(string $str) {
			$this->notice->add_Text($str);
		}
		public function add_Notices(array $str) {
			foreach ($str as $s) {
				$this->notice->add_Text($s);
			}
		}
		public function build_User(array $user) {
			$this->userpanel->add_Element(new HtmlText($user['name'].'&nbsp;&nbsp;&nbsp;'));
			if ($user['uac'] >= 666) {
				$this->add_Menu('/tech.php', 'тк');
			}
			if ($user['uac'] >= 777) {
				$this->add_Menu('/admin.php', 'админка');
			}
		}
		
	}
	
	class DefaultLoginPage extends HtmlPage {
		private $notice;
		public function __construct(string $title = 'Login') {
			$style = '/core/html/style.css';
			$charset = 'utf-8';
			Parent::__construct($title, $style, $charset);
			
			$bodydiv = new HtmlDiv('body');
			Parent::add_Element($bodydiv);
			
			$logindiv = new HtmlDiv('login');
			$bodydiv->add_Element($logindiv);
			
			$this->notice = new HtmlDPC('notice', 'noticeitem');
			$logindiv->add_Element($this->notice);
			
			$form = new HtmlForm('login', '/index.php');
			$logindiv->add_Element($form);
			$grid = new HtmlGrid(1);
			$form->add_Element($grid);
			
			$grid->add_Text('Логин: ');
			$grid->add_Element(new HtmlInput('login', 'text', 'core[login]', ''));
			$grid->add_Text('Пароль: ');
			$grid->add_Element(new HtmlInput('login', 'password', 'core[password]', ''));
			$grid->add_Element(new HtmlInput('login', 'submit', 'core[logbtn]', WTA_AD_LOGIN));
			$grid->add_Element(new HtmlInput('login', 'submit', 'core[logbtn]', WTA_WTA_LOGIN));
			
		}
		
		public function add_Notice(string $str) {
			$this->notice->add_Text($str);
		}
		public function add_Notices(array $str) {
			foreach ($str as $s) {
				$this->notice->add_Text($s);
			}
		}
	}
	
	
	#TODO
	class DefaultErrorPage extends HtmlPage {
		private $notice;
		public function __construct(string $title = 'Login') {
			$style = '/core/html/style.css';
			$charset = 'utf-8';
			Parent::__construct($title, $style, $charset);
			
			$bodydiv = new HtmlDiv('body');
			Parent::add_Element($bodydiv);
			
			$logindiv = new HtmlDiv('login');
			$bodydiv->add_Element($logindiv);
			
			$this->notice = new HtmlDPC('notice', 'noticeitem');
			$logindiv->add_Element($this->notice);
			
			$form = new HtmlForm('login', '/index.php');
			$logindiv->add_Element($form);
			$grid = new HtmlGrid(1);
			$form->add_Element($grid);
			
			$grid->add_Text('Логин: ');
			$grid->add_Element(new HtmlInput('login', 'text', 'core[login]', ''));
			$grid->add_Text('Пароль: ');
			$grid->add_Element(new HtmlInput('login', 'password', 'core[password]', ''));
			$grid->add_Element(new HtmlInput('login', 'submit', 'core[logbtn]', WTA_AD_LOGIN));
			$grid->add_Element(new HtmlInput('login', 'submit', 'core[logbtn]', WTA_WTA_LOGIN));
			
		}
		
		public function add_Notice(string $str) {
			$this->notice->add_Text($str);
		}
		public function add_Notices(array $str) {
			foreach ($str as $s) {
				$this->notice->add_Text($s);
			}
		}
	}
	
	
?>