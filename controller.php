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

class SiMysqlOptimizePackage extends Package {

	protected $pkgHandle = 'si_mysql_optimize';
	protected $appVersionRequired = '5.4.2.2';
	protected $pkgVersion = '1.11'; 
	
	public function getPackageName() {
		return t("MindNet Optimize MySQL database"); 
	}	
	
	public function getPackageDescription() {
		return t("This package provides a job script for optimize all tables in your MySQL database used by concrete5.");
	}
	
	public function install() {
		$pkg = parent::install();
		
		Loader::model("job");
		Job::installByPackage('si_mysql_optimize', $pkg);
	}
	
	public function uninstall() {
		$pkg = parent::uninstall();
	}
	
	public function upgrade() {
		parent::upgrade();
		
 		if (is_writable("./jobs")) {
			$job = Job::getByHandle('si_mysql_optimize');
			
			if ($job) {
				$job->uninstall();
			}
			
			@unlink("./jobs/si_mysql_optimize.php");
			
			// We have to uninstall the package on the upgrade because on earlier versions the job was copied in the "/job/" directory.
			// In the new version we leave it in the "job" directory in the package. But this is the only way to upgrade, because
			// otherwise we would redeclare the class (here: "/job/si_mysql_optimize.php" and there "/packages/si_mysql_optimize/jobs/si_mysql_optimize.php")
			$this->uninstall();
		}
	}	
}
?>