<?php 
	class HtmlBuilder {
		
		public function to_select_array($items_array, $selected_item) {
			$string = '';
			foreach ($items_array as $item) {
				if ($item == $selected_item) {
					$string .= '<option selected value="'.$item.'">'.$item.'</option>';
				} else {
					$string .= '<option value="'.$item.'">'.$item.'</option>';
				}
			}
			return $string;
		}
		public function to_multi_select_array($items_array, $selected_items) {
			$string = '';
			foreach ($items_array as $item) {
				$selected = false;
				if (is_array($selected_items)) {
					foreach ($selected_items as $sel) {
						if ($item == $sel) {
							$selected = true;
						}
					}
				}
				if (is_string($selected_items)) {
					if ($item == $selected_items) {
						$selected = true;
					}
				}
				if ($selected) {
					$string .= '<option selected value="'.$item.'">'.$item.'</option>';
				} else {
					$string .= '<option value="'.$item.'">'.$item.'</option>';
				}
			}
			return $string;
		}
		
	}
?>