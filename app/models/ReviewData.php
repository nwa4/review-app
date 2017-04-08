<?php

/*

1. What is your name?

	Free form data input field (Required field);

2. Your birth date:

	Selection fields (Year, month, day); - Date needs to be valid dates, lets say if 29th of February is chosen, you need to make sure the day exists in that year (Please use select type HTML elements, all of them are required)

3. Gender:

One of the given fields must be required (radio button):
*male
*female	

4. Are you interested in development?

	Selection from 2 variants (Radio button)Pasirinkimas iš 2 variantų (radio input tipo laukai, privalomi);

	*Yes (If this option is selected the review continues)
	*No (If this option is selected the questionaries’ stops);

5. Which development languages do you know?

	Use input checkbox type elements, few selections available:

	*PHP
	*CSS
	*HTML
	*JavaScript
	*Java
	* Don’t know any – (If this option is selected all other fields are unselected and selection of any other options are unavailable. After this field selection the review stops);

6. Please attach your photo:

	Option to allow to attach photo, if photo is attached, you need to check maximum weight of the pohoto, and it should not be higher then 500 KB


*/
class ReviewData
{
	public static function GetReview()
	{
		return new Review(new PhalconSessionTempStore(), ReviewData::$settings);
	}
	public static $storeName = "tempItems";
	public static $photoDir = "../public/photos/";
	public static $settings = [
		'question1'=>array(
		'title'=>'What is your name?',
		'dependants'=>[]
		),
		'question2'=>array(
		'title'=>'Your birth date:',
		'dependants'=>[]
		),
		'question3'=>array(
		'title'=>'Gender',
		'dependants'=>[]
		),
		'question4'=>array(
		'title'=>'Are you interested in development?',
		'dependants'=>[]
		),
		'question5'=>array(
		'title'=>'Which development languages do you know?',
		'dependants'=>['question4'=>'No']
		),
		'question6'=>array(
		'title'=>'Please attach your photo:',
		'dependants'=>['question4'=>'No', 'question5'=>'noDev']
		)
	];
	
	private static $error = [
		'name'=>'you must provide your name to continue',
		'dob'=>'Please select your date of birth',
		'dob-invalid'=>'Selected date is invalid',
		'gender'=>'select your gender',
		'dev'=>'your must select yes or no ',
		'devList'=>'select at leat one',
		'photo'=>'upload a valid photo'
	];
	
	public static function getError($key='')
	{
		if(!isset($key) || empty($key))
			return '';
			
		return '<label class="alert alert-danger">'.ReviewData::$error[$key].'</label>';
	}
	
}