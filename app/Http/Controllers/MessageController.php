<?php

namespace App\Http\Controllers;

use App\MessageInvoice;
use Illuminate\Http\Request;
use App\Message;
use App\User;
use App\Kikundi;
use App\VyeoKanisa;
use App\Wanakikundi;
use App\Familia;
use App\Jumuiya;
use App\Mwanafamilia;
use Carbon;
use App\MessageBalance;

class MessageController extends Controller
{
    //
    public function message_index(){
        $data_vyama = Kikundi::where('idadi_ya_waumini','!=',0)->latest()->get();
        $data_viongozi = VyeoKanisa::latest()->get();
        $data_familia = Familia::latest()->get();
        $data_jumuiya = Jumuiya::latest()->get();

        //message statistics
        $today_message = Message::whereDate('created_at',Carbon::now()->format('Y-m-d'))->sum('idadi');

        //total messages
        $total_messages = Message::sum('idadi');

        //this year
        $this_year = Message::whereYear('created_at',Carbon::now()->format('Y'))->sum('idadi');

        //last year
        $last_year = Message::whereYear('created_at',Carbon::now()->subYear(1)->format('Y'))->sum('idadi');

        //this month
        $this_month = Message::whereMonth('created_at',Carbon::now()->format('m'))->whereYear('created_at',Carbon::now()->format('Y'))->sum('idadi');

        //this week
        $this_week = Message::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('idadi');

        //sms balance
        $sms_balance = $this->getSmsBalance();

        return view('backend.message.meseji',compact(['data_vyama','data_viongozi','data_familia','data_jumuiya','today_message','total_messages','this_year','last_year','this_month','this_week','sms_balance']));
    }


    //function to send the message
    public function send_message(Request $request){
        //getting data
        $mpokeaji = $request->mpokeaji;

        //getting message
        $ujumbe = $request->ujumbe;

        $number_of_message = $request->number_of_message;

       // dd($ujumbe);

        //validating ujumbe
        if($ujumbe == ""){
            return response()->json(['errors'=>["Hakikisha umeandika ujumbe wako huwezi kutuma ujumber usio na maandishi.."]]);
        }

        if($mpokeaji === "mawasiliano"){
            //confirming user has choose the number
            $single_number = $request->mawasiliano;

            if($single_number == ""){
                return response()->json(['errors'=>["Hakikisha umejaza namba sahihi kwakua unatuma kwa mtu mmoja"]]);
            }

            $single_number = ltrim($single_number, '0');

            $namba = "255".$single_number;

            //before sending getting the balance first
            $sms_balance = $this->getSmsBalance();

           // dd($sms_balance);

            //counting the characters of message
            $chars = ceil((strlen($ujumbe))/160);

            if($chars > $sms_balance){
                return response()->json(['errors'=>"Salio lako la meseji halitoshi tafadhali ongeza salio.."]);
            }

            $data = $this->sendSingleMessage($ujumbe,$namba);

            if($data == "0000ATLA"){
                return response()->json(['errors'=>"Ujumbe haujatumwa tafadhali jaribu tena.."]);
            }

            else{

                $server_data = json_decode($data);

                //saving message
                $data_save = [
                    'mtumaji' => auth()->user()->email,
                    'mpokeaji' => $namba,
                    'ujumbe' => $ujumbe,
                    'status' => $server_data->code,
                    'idadi' => $chars,
                    'tarehe' => date('Y-m-d'),
                ];

                Message::create($data_save);

                //deducting the balance
                MessageBalance::decrement('balance',$chars);


                return response()->json(['success'=>"Ujumbe umetumwa kikamilifu.."]);
            }
        }

        else{
            //getting the specific group
            $wapokeaji = $request->mpokeaji;

            //================================ CHAMA CHA KITUME =======================//
            if($wapokeaji === "chama_cha_kitume"){

                //getting details in chama
                $chama = $request->chama;

                //getting specific chama
                foreach($chama as $key => $row){

                    //get members from vyama vya kitume
                    if($row != "vyama_vyote"){
                        //for the selected vyama vya kitume only
                        $vyama = "none";
                        $ids_za_wanafamilia[] = Wanakikundi::whereNotNull('mwanafamilia')->where('kikundi',$row)->get('mwanafamilia');
                    }
                    else{
                        //for all vyama vya kitume
                        $vyama = "vyama_vyote";
                        $ids_za_wanachama_wote = Wanakikundi::whereNotNull('mwanafamilia')->get('mwanafamilia');
                    }
                }

                //checking if vyama vyote transform the object since the single vyama use value it formulate the array well but for vyote it is not ok
                if($vyama == "vyama_vyote"){

                    foreach($ids_za_wanachama_wote as $row_data){
                        $numbers[] = Mwanafamilia::whereId($row_data->mwanafamilia)->value('mawasiliano');
                    }
                }

                else{
                    //now getting all the numbers
                    foreach ($ids_za_wanafamilia as $key => $item_data){

                        //we must have a value from familia
                        if($item_data->isNotEmpty()){

                            foreach($item_data as $row){
                                $numbers[] = Mwanafamilia::whereId($row->mwanafamilia)->value('mawasiliano');
                            }
                        }
                    }
                }

                //function to clean the numbers
                $real_cleaned_numbers = $this->getCleanedNumbers($numbers);

                $total_sms = $number_of_message * count($real_cleaned_numbers);

                //before sending getting the balance first
                $sms_balance = $this->getSmsBalance();

                if($total_sms > $sms_balance){
                    return response()->json(['errors'=>"Salio la meseji halitoshi kutuma meseji $total_sms tafadhali ongeza salio.."]);
                }

                //sending $data to group sms
                $data_group = $this->sendGroupMessage($real_cleaned_numbers,$ujumbe);

                if($data_group == "0000ATLA"){
                    return response()->json(['errors'=>"Ujumbe haujatumwa tafadhali jaribu tena.."]);
                }
                else{
                    $server_data = json_decode($data_group);

                    //saving message
                    $data_save = [
                        'mtumaji' => auth()->user()->email,
                        'mpokeaji' => "vyama vya kitume",
                        'ujumbe' => $ujumbe,
                        'status' => $server_data->code,
                        'idadi' => $server_data->valid,
                        'tarehe' => date('Y-m-d'),
                    ];

                    Message::create($data_save);
                    //deducting the balance
                    MessageBalance::decrement('balance',$server_data->valid);
                    return response()->json(['success'=>"Ujumbe umetumwa kikamilifu.."]);
                }
            }
            //===========================================================================//

            //================================ VIONGOZI =================================//
            elseif($wapokeaji === "viongozi"){
                //getting details in viongozi
                $viongozi_data = $request->viongozi;

                //getting specific viongozi
                foreach($viongozi_data as $key => $row_v){

                    //get members from viongozi
                    if($row_v != "viongozi_wote"){
                        //for the selected viongozi only
                        $viongozi = "none";
                        $selected_uncleaned_numbers[] = User::where('ngazi','!=','administrator')->where('cheo',$row_v)->get('mawasiliano');
                    }
                    else{
                        //for all viongozi
                        $viongozi = "viongozi_wote";
                        $uncleaned_numbers = User::whereNotNull('id')->where('ngazi','!=','administrator')->get('mawasiliano');
                    }
                }

                //checking if viongozi wote or selective
                if($viongozi == "viongozi_wote"){

                    foreach($uncleaned_numbers as $row){
                        $numbers[] = $row->mawasiliano;
                    }

                    //function to clean the numbers
                    $real_cleaned_numbers = $this->getCleanedNumbers($numbers);
                }

                else{

                    //the formulated array has got mawasiliano with array of array so merge them to form single array
                    foreach($selected_uncleaned_numbers as $key => $rows){

                        foreach($rows as $row_datas){
                            $numbers[] = $row_datas->mawasiliano;
                        }
                    }

                    //function to clean the numbers
                    $real_cleaned_numbers = $this->getCleanedNumbers($numbers);
                }

                $total_sms = $number_of_message * count($real_cleaned_numbers);

                //before sending getting the balance first
                $sms_balance = $this->getSmsBalance();

                if($total_sms > $sms_balance){
                    return response()->json(['errors'=>"Salio la meseji halitoshi kutuma meseji $total_sms tafadhali ongeza salio.."]);
                }

                //sending $data to group sms
                $data_group = $this->sendGroupMessage($real_cleaned_numbers,$ujumbe);

                //
                if($data_group == "0000ATLA"){
                    return response()->json(['errors'=>"Ujumbe haujatumwa tafadhali jaribu tena.."]);
                }
                else{
                    $server_data = json_decode($data_group);

                    //saving message
                    $data_save = [
                        'mtumaji' => auth()->user()->email,
                        'mpokeaji' => "viongozi",
                        'ujumbe' => $ujumbe,
                        'status' => $server_data->code,
                        'idadi' => $server_data->valid,
                        'tarehe' => date('Y-m-d'),
                    ];

                    Message::create($data_save);
                    MessageBalance::decrement('balance',$server_data->valid);
                    return response()->json(['success'=>"Ujumbe umetumwa kikamilifu.."]);
                }
            }
            //========================================================//

            //====================== FAMILIA =========================//
            elseif($wapokeaji === "familia"){

                //getting details in familia
                $familia_data = $request->familia;

                //getting specific familia
                foreach($familia_data as $key => $row){

                    //get members from familia
                    if($row != "familia_zote"){

                        //for the selected familia only
                        $familia = "none";
                        $namba_za_wanafamilia[] = Familia::leftJoin('mwanafamilias','mwanafamilias.familia','=','familias.id')
                            ->whereNotNull('mwanafamilias.familia')
                            ->where('familias.id',$row)
                            ->get('mwanafamilias.mawasiliano');
                    }
                    else{
                        //for all familias
                        $familia = "familia_zote";
                        $namba_za_wanafamilia_wote = Familia::leftJoin('mwanafamilias','mwanafamilias.familia','=','familias.id')
                            ->whereNotNull('mwanafamilias.id')
                            ->get('mwanafamilias.mawasiliano');
                    }
                }

                //returning data by selecting either familia zote or selective
                if($familia == "familia_zote"){
                    foreach($namba_za_wanafamilia_wote as $row){
                        $numbers[] = $row->mawasiliano;
                    }

                    $real_cleaned_numbers = $this->getCleanedNumbers($numbers);
                }

                else{

                    foreach($namba_za_wanafamilia as $key => $data_row){

                        foreach($data_row as $row_a){
                            $numbers[] = $row_a->mawasiliano;
                        }
                    }

                    $real_cleaned_numbers = $this->getCleanedNumbers($numbers);
                }

                $total_sms = $number_of_message * count($real_cleaned_numbers);

                //before sending getting the balance first
                $sms_balance = $this->getSmsBalance();

                if($total_sms > $sms_balance){
                    return response()->json(['errors'=>"Salio la meseji halitoshi kutuma meseji $total_sms tafadhali ongeza salio.."]);
                }

                //sending $data to group sms
                $data_group = $this->sendGroupMessage($real_cleaned_numbers,$ujumbe);

                if($data_group == "0000ATLA"){
                    return response()->json(['errors'=>"Ujumbe haujatumwa tafadhali jaribu tena.."]);
                }
                else{
                    $server_data = json_decode($data_group);

                    //saving message
                    $data_save = [
                        'mtumaji' => auth()->user()->email,
                        'mpokeaji' => "familia",
                        'ujumbe' => $ujumbe,
                        'status' => $server_data->code,
                        'idadi' => $server_data->valid,
                        'tarehe' => date('Y-m-d'),
                    ];

                    Message::create($data_save);

                    //deducting the messages
                    MessageBalance::decrement('balance',$server_data->valid);
                    return response()->json(['success'=>"Ujumbe umetumwa kikamilifu.."]);
                }
            }
            //=========================================================//

            //====================== JUMUIYA ==========================//
            elseif($wapokeaji === "jumuiya"){
                //getting details in jumuiya
                $jumuiya_data = $request->jumuiya;

                //getting specific jumuiya
                foreach($jumuiya_data as $key => $row){

                    //get members from jumuiya
                    if($row != "jumuiya_zote"){

                        //for the selected jumuiya only
                        $jumuiya = "none";
                        $namba_za_wanajumuiya[] = Jumuiya::leftJoin('familias','familias.jina_la_jumuiya','=','jumuiyas.jina_la_jumuiya')
                            ->leftJoin('mwanafamilias','mwanafamilias.familia','=','familias.id')
                            ->whereNotNull('mwanafamilias.familia')
                            ->where('jumuiyas.id',$row)
                            ->get('mwanafamilias.mawasiliano');
                    }
                    else{
                        //for all jumuiya
                        $jumuiya = "jumuiya_zote";
                        $namba_za_wanajumuiya_wote = Jumuiya::leftJoin('familias','familias.jina_la_jumuiya','=','jumuiyas.jina_la_jumuiya')
                            ->leftJoin('mwanafamilias','mwanafamilias.familia','=','familias.id')
                            ->whereNotNull('mwanafamilias.familia')
                            ->get('mwanafamilias.mawasiliano');
                    }
                }

                //returning data by selecting either jumuiya zote or selective
                if($jumuiya == "jumuiya_zote"){
                    foreach($namba_za_wanajumuiya_wote as $row){
                        $numbers[] = $row->mawasiliano;
                    }

                    //cleaning the numbers
                    $real_cleaned_numbers = $this->getCleanedNumbers($numbers);
                }

                else{

                    foreach($namba_za_wanajumuiya as $key => $data_row){

                        foreach($data_row as $row_a){
                            $numbers[] = $row_a->mawasiliano;
                        }
                    }

                    //cleaning the numbers
                    $real_cleaned_numbers = $this->getCleanedNumbers($numbers);
                }

                //processing the sending of message

                $total_sms = $number_of_message * count($real_cleaned_numbers);

                //before sending getting the balance first
                $sms_balance = $this->getSmsBalance();

                if($total_sms > $sms_balance){
                    return response()->json(['errors'=>"Salio la meseji halitoshi kutuma meseji $total_sms tafadhali ongeza salio.."]);
                }

                //sending $data to group sms
                $data_group = $this->sendGroupMessage($real_cleaned_numbers,$ujumbe);

                if($data_group == "0000ATLA"){
                    return response()->json(['errors'=>"Ujumbe haujatumwa tafadhali jaribu tena.."]);
                }
                else{

                    $server_data = json_decode($data_group);

                    //saving message
                    $data_save = [
                        'mtumaji' => auth()->user()->email,
                        'mpokeaji' => "jumuiya",
                        'ujumbe' => $ujumbe,
                        'status' => $server_data->code,
                        'idadi' => $server_data->valid,
                        'tarehe' => date('Y-m-d'),
                    ];

                    Message::create($data_save);

                    //deducting the messages
                    MessageBalance::decrement('balance',$server_data->valid);
                    return response()->json(['success'=>"Ujumbe umetumwa kikamilifu.."]);
                }
            }
            else{
                return response()->json(['errors'=>["Hakikisha umefanya uchaguzi sahihi kabla ya kutuma ujumbe.."]]);
            }
        }
    }

    public function sendSingleMessage($ujumbe,$namba){
        $api_key = env('BONGO_LIVE_KEY');
        $secret_key = env('BONGO_LIVE_SECRET');
        $sender_info = env('BONGO_SENDER_ID');

        $postData = array(
            'source_addr' => $sender_info,
            'encoding'=>0,
            'schedule_time' => '',
            'message' => $ujumbe,
            'recipients' => [array('recipient_id' => '1','dest_addr'=>$namba)]
        );

        $Url ='https://apisms.beem.africa/v1/send';

        $ch = curl_init($Url);
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'Authorization:Basic ' . base64_encode("$api_key:$secret_key"),
                'Content-Type: application/json'
            ),
            CURLOPT_POSTFIELDS => json_encode($postData)
        ));

        $response = curl_exec($ch);

        if($response === FALSE){
            $data = $response;
            die(curl_error($ch));

            $data = "0000ATLA";
            return $data;
        }
        else{
            // var_dump($response);
            return $response;
        }
    }

    //function to send the group message
    private function sendGroupMessage($real_cleaned_numbers,$ujumbe){
        $api_key = env('BONGO_LIVE_KEY');
        $secret_key = env('BONGO_LIVE_SECRET');
        $sender_info = env('BONGO_SENDER_ID');

        $postData = array(
            'source_addr' => $sender_info,
            'encoding'=>0,
            'schedule_time' => '',
            'message' => $ujumbe,
            'recipients' => $real_cleaned_numbers
        );

        $Url ='https://apisms.beem.africa/v1/send';

        $ch = curl_init($Url);
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'Authorization:Basic ' . base64_encode("$api_key:$secret_key"),
                'Content-Type: application/json'
            ),
            CURLOPT_POSTFIELDS => json_encode($postData)
        ));

        $response = curl_exec($ch);

        if($response === FALSE){
            $data = $response;
            die(curl_error($ch));

            $data = "0000ATLA";
            return $data;
        }
        else{
            // var_dump($response);
            return $response;
        }
    }

    //function to clean all the numbers of wanachama
    private function getCleanedNumbers($numbers){
        $id = 0;
        foreach($numbers as $key => $clean){

            $new_number = \ltrim($clean,0);

            $cleaned_numbers[] = ['recipient_id' => $id=$id+1,'dest_addr' => "255".$new_number];
        }

        return $cleaned_numbers;
    }

    //function to handle sms balance
    private function getSmsBalance(){
        $balance = MessageBalance::latest()->value('balance');
        return $balance;
    }

    public function messageInvoice()
    {
        $invoices = MessageInvoice::all();

        return view('backend.message.message_invoice', ['title' => 'Malipo ya Salio la Mesaji','invoices' => $invoices]);
    }
}
