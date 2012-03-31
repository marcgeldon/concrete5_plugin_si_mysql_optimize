<?php  
/*
*
* Copyright (c) 2009 Marc Geldon <marc.geldon@scalait.de>, SCALA IT (www.scalait.de)
*
* Permission is hereby granted, free of charge, to any person obtaining a 
* copy of this software and associated documentation files (the 
* "Software"), to deal in the Software without restriction, including 
* without limitation the rights to use, copy, modify, merge, publish, 
* distribute, sublicense, and/or sell copies of the Software, and to 
* permit persons to whom the Software is furnished to do so, subject to 
* the following conditions: 
* 
* The above copyright notice and this permission notice shall be included 
* in all copies or substantial portions of the Software. 
* 
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS 
* OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF 
* MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. 
* IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY 
* CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, 
* TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE 
* SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE. 

* @author Marc Geldon <mgeldon@mindnet-systemhaus.com>
* @copyright Copyright (c) 2009-2012 Marc Geldon, MindNet Systemhaus GmbH (http://www.mindnet-systemhaus.com/)
* @license MIT License
*/

defined('C5_EXECUTE') or die(_("Access Denied."));
class SiMysqlOptimize extends Job {
	
	public function getJobName() {
		return t("MindNet Optimize MySQL database");
	}
	
	public function getJobDescription() {
		return t("Optimize all tables in your MySQL database used by concrete5");
	}
	
	public function run() {
		$tables_for_optimization = array();
		$count = 0;

		$db = Loader::db();
		$v = array();
		$q = "SHOW TABLE STATUS";
		$rs = $db->query($q, $v);
	
		foreach ($rs as $table) {
			if ($table["Data_free"] > 0) {
				$tables_for_optimization[] = $table["Name"];
			}
		}
		unset($rs);
		
		foreach ($tables_for_optimization as $table) {
			$db->execute("OPTIMIZE TABLE " . $table);
			$count++;
		}
		
		$return_message = t("The Job was run successfully.");
		
		if ($count > 0) {
			return $return_message . " " . t("Optimized %s tables.", $count);
		} else {
			return $return_message . " " . t("There were no tables to optimize.");
		}
	}
}
?>