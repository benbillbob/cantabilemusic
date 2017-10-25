<?php

class DateHelper{

	public static function addMonths($date, $months)
	{
		$oldDay = $date->format("d");
		$date->add(new DateInterval("P".$months."M"));
		$newDay = $date->format("d");

		if($oldDay != $newDay) {
			// Check if the day is changed, if so we skipped to the next month.
			// Substract days to go back to the last day of previous month.
			$date->sub(new DateInterval("P" . $newDay . "D"));
		}

		return $date;
	}
}