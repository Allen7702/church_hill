<?php

use App\SahihishaKanda;
use Carbon\Carbon;

function getVariables($tareheMwanzo,$tareheMwisho){

    $data_variable=[
        'previous_year' => date('Y',strtotime($tareheMwanzo)),
        'current_year' => date('Y',strtotime($tareheMwisho)),
        'current_month' => date('F',strtotime($tareheMwisho)),
        'start_month' =>date('F', strtotime($tareheMwanzo)),
        'start_date' => date('Y-m-d',strtotime($tareheMwanzo)),
        'end_date'=>date('Y-m-d',strtotime($tareheMwisho))
    ];

    
    return $data_variable;

}

    function search_by_date($tareheMwanzo,$tareheMwisho)
    {
        $data_variable = getVariables($tareheMwanzo,$tareheMwisho);

        return [
            'current_month'=> $data_variable['current_month'],
            'current_year'=> $data_variable['current_year'],
            'start_month'=> $data_variable['start_month'],
            'start_date'=> $data_variable['start_date'],
            'end_date'=>$data_variable['end_date'],
            'previous_year'=>$data_variable['previous_year'],
            'begin_with'=>Carbon::parse($data_variable['start_date'])->format('Y-m-d')
        ];
    }

    function space_to_underscore($str){
      
        return  str_replace(" ","_", $str);
    }

    function underscore_to_space($str)
    {
        return str_replace("_"," ", $str);

    }

    function get_kanda()
    {
        return SahihishaKanda::first();
    }
 function getMonthListFromDate($startDate, $endDate)
{
    $start    = new DateTime($startDate); // Today date
    $end      = new DateTime($endDate); // Create a datetime object from your Carbon object
    $interval = DateInterval::createFromDateString('1 month'); // 1 month interval
    $period   = new DatePeriod($start, $interval, $end); // Get a set of date beetween the 2 period

    $months = array();

    foreach ($period as $dt) {
        $months[] = $dt->format("m");
    }

    return $months;
}