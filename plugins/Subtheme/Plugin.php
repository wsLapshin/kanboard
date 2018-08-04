<?php

namespace Kanboard\Plugin\Subtheme;
use Kanboard\Core\Plugin\Base;

class Plugin extends Base
{
	public function initialize()
	{

		// Add additional CSS-File in Header
		// CSS-File is generate from SASS-File
		$this->template->hook->attach('template:layout:head', 'subtheme:layout/head');

	}
}
