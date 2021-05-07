<?php
	class HtmlElement {
		private $open;
		private $text;
		private $elements;
		private $close;
		
		public function __construct(string $s = '', string $e = '') {
			$this->open = $s;
			$this->close = $e;
		}
		
		public function add_Element(HtmlElement $element) {
			$this->elements[] = $element;
		}
		
		public function add_Text(string $str) {
			$this->text .= $str;
		}
		
		public function show() {
			echo $this->open;
			if (isset($this->text)) {
				echo $this->text;
			}
			if (isset($this->elements)) {
				if (is_array($this->elements)) {
					foreach ($this->elements as $element) {
						$element->show();
					}
				} else {
					$this->elements->show();
				}
			}
			echo $this->close;
		}
	}
	
	class HtmlText extends HtmlElement {
		public function __construct(string $str) {
			Parent::add_Text($str);
		}
		public function add_Element(HtmlElement $element) {
			#do nothing
		}
		
		public function add_Text(string $str) {
			#do nothing
		}
	}
	
	class HtmlDiv extends HtmlElement {
		public function __construct(string $cclass = 'default') {
			$start = '<div class="'.$cclass.'">';
			$end = '</div>';
			Parent::__construct($start, $end);
		}
	}
	
	class HtmlP extends HtmlElement {
		public function __construct(string $cclass = 'default') {
			$start = '<p class="'.$cclass.'">';
			$end = '</p>';
			Parent::__construct($start, $end);
		}
	}
	
	class HtmlPage extends HtmlElement {
		public function __construct(	string $title = 'NewPage',
										string $style = '/core/html/style.css',
										string $charset = 'utf-8') {
			$start = '<!DOCTYPE html><HTML><head>'
				.'<meta charset="'.$charset.'">'
				.'<title>'.$title.'</title>'
				.'<link rel="stylesheet" href="'.$style.'">'
				.'</head><body>';
			$end = '</body></HTML>';
			Parent::__construct($start, $end);
		}
	}
	
	class HtmlImg extends HtmlElement {
		public function __construct(string $url, string $alt = '') {
			$start = '<img src="'.$url.'" alt="'.$alt.'">';
			$end = '';
			Parent::__construct($start, $end);
		}
		public function add_Element(HtmlElement $element) {
			#do nothing
		}
		
		public function add_Text(string $str) {
			#do nothing
		}
	}
	
	class HtmlA extends HtmlElement {
		public function __construct(string $url, string $name) {
			$start = '<a href="'.$url.'">';
			$end = '</a>';
			Parent::__construct($start, $end);
			Parent::add_Text($name);
		}
		public function add_Element(HtmlElement $element) {
			#do nothing
		}
		
		public function add_Text(string $str) {
			#do nothing
		}
	}
	
	class HtmlAImg extends HtmlElement {
		public function __construct(string $url, string $imgurl, string $name = '') {
			$start = '<a href="'.$url.'">';
			$end = '</a>';
			$img = new HtmlImg($imgurl, $name);
			Parent::__construct($start, $end);
			Parent::add_Element($img);
		}
		public function add_Element(HtmlElement $element) {
			#do nothing
		}
		
		public function add_Text(string $str) {
			#do nothing
		}
	}
	
	class HtmlDPC extends HtmlElement {
		private $pclass;
		public function __construct(string $dclass = 'DPCD', string $pclass = 'DPCP') {
			$this->pclass = $pclass;
			$start = '<div class="'.$dclass.'">';
			$end = '</div>';
			Parent::__construct($start, $end);
		}
		public function add_Element(HtmlElement $element) {
			$p = new HtmlP($this->pclass);
			$p->add_Element($element);
			Parent::add_Element($p);
		}
		public function add_Text(string $str) {
			$p = new HtmlP($this->pclass);
			$p->add_Text($str);
			Parent::add_Element($p);
		}
		public function add_A(string $url, string $name) {
			$p = new HtmlP($this->pclass);
			$a = new HtmlA($url, $name);
			$p->add_Element($a);
			Parent::add_Element($p);
		}
	}
	
	
	class HtmlTable extends HtmlElement {
		public function __construct(string $cclass = 'default') {
			$start = '<table class="'.$cclass.'">';
			$end = '</table>';
			Parent::__construct($start, $end);
		}
		public function add_HRowE(array $arrElement) {
			$r = new HtmlElement('<tr>','</tr>');
			foreach ($arrElement as $element) {
				$c = new HtmlElement('<th>', '</th>');
				$c->add_Element($element);
				$r->add_Element($c);
			}
			Parent::add_Element($r);
		}
		public function add_RowE(array $arrElement) {
			$r = new HtmlElement('<tr>','</tr>');
			foreach ($arrElement as $element) {
				$c = new HtmlElement('<td>', '</td>');
				$c->add_Element($element);
				$r->add_Element($c);
			}
			Parent::add_Element($r);
		}
		public function add_HRowT(array $arrStr) {
			$r = new HtmlElement('<tr>','</tr>');
			foreach ($arrStr as $str) {
				$c = new HtmlElement('<th>', '</th>');
				$c->add_Text($str);
				$r->add_Element($c);
			}
			Parent::add_Element($r);
		}
		public function add_RowT(array $arrStr) {
			$r = new HtmlElement('<tr>','</tr>');
			foreach ($arrStr as $str) {
				$c = new HtmlElement('<td>', '</td>');
				$c->add_Text($str);
				$r->add_Element($c);
			}
			Parent::add_Element($r);
		}
		public function add_Element(HtmlElement $element) {
			$this->add_RowE(array($element));
		}
		public function add_Text(string $str) {
			$this->add_RowT(array($str));
		}
		
		
	}
	
	class HtmlGrid extends HtmlElement {
		private $gridelement;
		private $x;
		private $y;
		private $iterator;
		private $open;
		private $close;
		public function __construct(int $x = 0, int $y = 0, string $cclass = 'grid') {
			$this->open = '<table class="'.$cclass.'">';
			$this->close = '</table>';
			$this->x = $x;
			$this->y = $y;
			$this->iterator['x'] = 0;
			$this->iterator['y'] = 0;
			$this->gridelement[0][0] = null;
		}
		public function add_Element(HtmlElement $element) {
			$this->add_ElementXY($element, $this->iterator['x'], $this->iterator['y']);
			if ($this->iterator['x'] < $this->x) {
				$this->iterator['x']++;
			} else {
				$this->iterator['x'] = 0;
				$this->iterator['y']++;
			}
		}
		public function add_Text(string $str) {
			$element = new HtmlText($str);
			$this->add_Element($element);
		}
		public function add_ElementXY(HtmlElement $element, int $x, int $y) {
			$this->gridelement[$y][$x] = $element;
			$this->iterator['x'] = $x;
			$this->iterator['y'] = $y;
			if ($x > $this->x) { $this->x = $x; }
			if ($y > $this->y) { $this->y = $y; }
		}
		public function add_TextXY(string $str, int $x, int $y) {
			$element = new HtmlText($str);
			$this->add_ElementXY($element, $x, $y);
		}
		public function show() {
			$width = 100 / ($this->x + 1);
			echo $this->open;
			for ($i = 0; $i <= $this->y; $i++) {
				echo '<tr>';
				for ($ii = 0; $ii <= $this->x; $ii++) {
					echo '<td style="width: '.$width.'%">';
					if (isset($this->gridelement[$i][$ii])) {
						$this->gridelement[$i][$ii]->show();
					}
					echo '</td>';
				}
				echo '</tr>';
			}
			echo $this->close;
		}
	}
	
	
	class HtmlForm extends HtmlElement {
		public function __construct(string $form, string $action, string $method = 'post') {
			$start = '<form id="'.$form.'" action="'.$action.'" method="'.$method.'">';
			$end = '</form>';
			Parent::__construct($start, $end);
		}
	}
	
	class HtmlInput extends HtmlElement {
		public function __construct(string $form, string $type = 'text', string $name = '[noname][]', string $value='') {
			$start = '<input form="'.$form.'" type="'.$type.'" name="'.$name.'" value="';
			$end = '">';
			Parent::__construct($start, $end);
			Parent::add_Text($value);
		}
	}
	
	
	
	
?>