<?php
require_once "lib/page/SpotPage_Abs.php";
require_once "SpotCategories.php";

class SpotPage_index extends SpotPage_Abs {
	private $_params;

	function __construct($db, $settings, $prefs, $params) {
		parent::__construct($db, $settings, $prefs);
		
		$this->_params = $params;
	} # ctor

	function render() {
		$spotsOverview = new SpotsOverview($this->_db);
		$filter = $spotsOverview->filterToQuery($this->_params['search']);

		# Haal de offset uit de URL en zet deze als startid voor de volgende zoektocht
		# Als de offset niet in de url staat, zet de waarde als 0, het is de eerste keer
		# dat de index pagina wordt aangeroepen
		$pageNr = $this->_params['page'];
		$nextPage = $pageNr + 1;
		if ($nextPage == 1) {
			$prevPage = -1;
		} else {
			$prevPage = max($pageNr - 1, 0);
		} # else
		
		# laad de spots
		$spotsTmp = $spotsOverview->loadSpots($pageNr, $this->_prefs['perpage'], $filter);

		# als er geen volgende pagina is, ook niet tonen
		if (!$spotsTmp['hasmore']) {
			$nextPage = -1;
		} # if
		
		# zet de page title
		$this->_pageTitle = "overzicht";
		
		#- display stuff -#
		$this->template('header');
		$this->template('filters', array('search' => $this->_params['search'],
								  'filters' => $this->_settings['filters']));
		$this->template('spots', array('spots' => $spotsTmp['list'],
		                        'nextPage' => $nextPage,
								'prevPage' => $prevPage,
								'activefilter' => $this->_params['search']));
		$this->template('footer');
	} # render()
	
} # class SpotPage_index