<?php

namespace Application\Model;

class LongOutput
{
	public function run($length) 
	{
		$output = '';
		
		for ($i = 0; $i <= $length; $i++) {
			$output .= 123 * $i;

			for ($j = 0; $j <= $i; $j++) {
				$output .= md5($j);
			}
		}

		return $output;
	}
}