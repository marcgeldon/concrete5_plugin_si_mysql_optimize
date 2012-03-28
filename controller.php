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

* @author Marc Geldon <marc.geldon@scalait.de
* @copyright Copyright (c) 2009 Marc Geldon, SCALA IT (http://www.scalait.de)
* @license MIT License
*/

defined('C5_EXECUTE') or die(_("Access Denied."));

class SiMysqlOptimizePackage extends Package {

	protected $pkgHandle = 'si_mysql_optimize';
	protected $appVersionRequired = '5.3.1';
	protected $pkgVersion = '1.0'; 
	
	public function getPackageName() {
		return t("SCALA IT Optimize MySQL database"); 
	}	
	
	public function getPackageDescription() {
		return t("This package provides a job script for optimize all tables in your MySQL database used by concrete5.");
	}
	
	public function install() {
		$pkg = parent::install();
		
		Loader::model("job");
		
		if (is_writable("./jobs")) {
			copy("./packages/si_mysql_optimize/jobs/si_mysql_optimize.php", "./jobs/si_mysql_optimize.php");
			Job::installByHandle("si_mysql_optimize");
		} else {
			throw new Exception(t("Unable to write to 'jobs' directory."));
		}	
	}
	
	public function uninstall() {
		$pkg = parent::uninstall();
		
		Loader::model("job");
		
		if (is_writable("./jobs")) {
			$job = Job::getByHandle('si_mysql_optimize');
			if ($job) {
				$job->uninstall();
			}
			unlink("./jobs/si_mysql_optimize.php");
		} else {
			throw new Exception(t("Unable to delete from 'jobs' directory."));
		}
	}
}
?>