<?php

class wpfaqPaginate extends wpFaqPlugin {
	
	/**
	 * DB table name to paginate on
	 *
	 */
	var $table = '';
	
	/**
	 * Fields for SELECT query
	 * Only these fields will be fetched.
	 * Use asterix for all available fields
	 *
	 */
	var $fields = '*';
	
	/**
	 * Current page
	 *
	 */
	var $page = 1;
	
	/**
	 * Records to show per page
	 *
	 */
	var $per_page = 10;
	
	/**
	 * WHERE conditions
	 * This should be an array
	 *
	 */
	var $where = '';
	
	/**
	 * ORDER condition
	 *
	 */
	var $order = '';
	
	var $plugin_url = '';
	var $sub = '';
	var $parent = '';
	
	var $allcount = 0;
	var $allRecords = array();
	
	var $pagination = '';
	
	function wpfaqPaginate($table = '', $fields = '', $sub = '', $parent = '') {	
		$this -> sub = $sub;
		$this -> parentd = $parent;
	
		if (!empty($table)) {
			$this -> table = $table;
		}
		
		if (!empty($fields)) {
			$this -> fields = $fields;
		}
	}
	
	function start_paging($page = '') {
		global $wpdb, $wpfaqHtml;
	
		$page = (empty($page)) ? 1 : $page;
	
		if (!empty($page)) {
			$this -> page = $page;
		}
		
		if (!empty($this -> fields)) {
			if (is_array($this -> fields)) {
				$this -> fields = implode(", ", $this -> fields);
			}
		}
		
		$query = "SELECT " . $this -> fields . " FROM `" . $this -> table . "`";
		$countquery = "SELECT COUNT(*) FROM `" . $this -> table . "`";
		
		//check if some conditions where passed.
		if (!empty($this -> where)) {
			//append the "WHERE" command to the query
			$query .= " WHERE";
			$countquery .= " WHERE";
			$c = 1;
			
			foreach ($this -> where as $key => $val) {
				$inbrackets = false;
				
				if (ereg("LIKE", $val)) {
					$query .= " ";
					$countquery .= " ";
					
					if (preg_match("/\((.*)\)/si", $key, $bracketmatch)) {
						$query .= " (";
						$countquery .= " (";
						$key = $bracketmatch[1];
						$inbrackets = true;
					}
					
					$query .= "LOWER(" . $key . ") " . $val . "";	
					$countquery .= "LOWER(" . $key . ") " . $val . "";
					
					if ($inbrackets) {
						$query .= ")";
						$countquery .= ")";
					}
				} else {
					$query .= " `" . $key . "` = '" . $val . "'";
					$countquery .= " `" . $key . "` = '" . $val . "'";
				}
				
				if ($c < count($this -> where)) {
					$query .= " AND";
					$countquery .= " AND";
				}
				
				$c++;
			}
		}
		
		$r = 1;
		
		if ($this -> page > 1) {
			$begRecord = (($this -> page * $this -> per_page) - ($this -> per_page));
		} else {
			$begRecord = 0;
		}
			
		$endRecord = $begRecord + $this -> per_page;
		//$query .= " ORDER BY `modified` DESC LIMIT " . $begRecord . " , " . $this -> per_page . ";";
		
		$this -> begRecord = $begRecord;
		$this -> endRecord = $endRecord;
		
		$order = (empty($this -> order)) ? array('modified', "DESC") : $this -> order;
		list($okey, $oval) = $order;
		$query .= " ORDER BY `" . $okey . "` " . $oval . "";
		$query .= " LIMIT " . $begRecord . " , " . $this -> per_page . ";";
		
		$records = $wpdb -> get_results($query);
		$records_count = count($records);
		$allRecordsCount = $this -> allcount = $wpdb -> get_var($countquery);
		$totalpagescount = round($records_count / $this -> per_page);
		
		$pageparam = (!empty($this -> sub) && $this -> sub == "N") ? '' : 'page=' . $this -> pre . $this -> sub . '&amp;';
		$pageparam = '';
		$search = (empty($this -> searchterm)) ? '' : '&amp;' . $this -> pre . 'searchterm=' . urlencode($this -> searchterm);
		
		if (count($records) < $allRecordsCount) {			
			$p = 1;
			$k = 1;
			$n = $this -> page;
			
			$add_prev = $pageparam . $this -> pre . 'page=' . ($this -> page - 1) . $search . '';
			$add_next = $pageparam . $this -> pre . 'page=' . ($this -> page + 1) . $search . '';
			
			$this -> pagination .= '<span class="displaying-num">' . __('Displaying', $this -> plugin_name) . ' ' . ($this -> begRecord + 1) . ' - ' . ($this -> begRecord + count($records)) . ' ' . __('of', $this -> plugin_name) . ' ' . $this -> allcount . '</span>';
		
			if ($this -> page > 1) {
				$this -> pagination .= '<a class="prev page-numbers" href="' . $wpfaqHtml -> retainquery($add_prev) . '" title="">&laquo;</a>';
			}
			
			while ($p <= $allRecordsCount) {
				$add_numbers = $pageparam . $this -> pre . 'page=' . ($k) . $search . '';
					
				if ($k >= ($this -> page - 5) && $k <= ($this -> page + 5)) {
					if ($k != $this -> page) {
						$this -> pagination .= '<a class="page-numbers" href="' . $wpfaqHtml -> retainquery($add_numbers) . '" title="">' . $k . '</a>';
					} else {
						$this -> pagination .= '<span class="page-numbers current">' . $k . '</span>';
					}
				}
				
				$p = $p + $this -> per_page;
				$k++;
			}
			
			if ((count($records) + $begRecord) < $allRecordsCount) {
				$this -> pagination .= '<a class="next page-numbers" href="' . $wpfaqHtml -> retainquery($add_next) . '" title="">&raquo;</a>';
			}
		}
		
		return $records;
	}
}

?>