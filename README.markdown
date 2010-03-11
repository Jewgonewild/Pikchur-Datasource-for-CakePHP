#Overview

A simple Datasource for the Pikchur standard API. Documentation for the api can be found here: 
http://groups.google.com/group/pikchur-api/web/api-documentation

#Usage

	<?php
		class PikchurController extends AppController {
			function index(){
				$this->Pikchur = ConnectionManager::getDataSource('pikchur');
        		$response = $this->Pikchur->authenticate();
				debug($response);
				}
			}
	?>