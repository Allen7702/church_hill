<?php

namespace App\Http\Controllers;

use App\AhadiMichangoGawaMwanafamilia;
use App\AinaZaMichango;
use App\AwamuMichango;
use App\Message;
use App\MessageBalance;
use App\MessageShukraniUkumbusho;
use App\MichangoBenki;
use App\MichangoTaslimu;
use App\Mwanafamilia;
use App\Zaka;
use App\ZakaKilaMwezi;
use Carbon;
use Illuminate\Http\Request;
use Validator;

class MessageShukraniUkumbushoController extends Controller
{
    //
    public function shukrani_ukumbusho_index()
    {

        //message statistics
        $today_message = Message::whereDate('created_at', Carbon::now()->format('Y-m-d'))->sum('idadi');

        //total messages
        $total_messages = Message::sum('idadi');

        //this year
        $this_year = Message::whereYear('created_at', Carbon::now()->format('Y'))->sum('idadi');

        //last year
        $last_year = Message::whereYear('created_at', Carbon::now()->subYear(1)->format('Y'))->sum('idadi');

        //this month
        $this_month = Message::whereMonth('created_at', Carbon::now()->format('m'))->whereYear('created_at', Carbon::now()->format('Y'))->sum('idadi');

        //this week
        $this_week = Message::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('idadi');

        //sms balance
        $sms_balance = $this->getSmsBalance();

        $data_sample = MessageShukraniUkumbusho::latest()->get();
        return view('backend.message.shukrani_ukumbusho', compact(['data_sample', 'today_message', 'total_messages', 'this_year', 'last_year', 'this_month', 'this_week', 'sms_balance']));
    }

    public function shukrani_ukumbusho_sample()
    {
        $data_ujumbe = MessageShukraniUkumbusho::latest()->get();
        $data_aina = AinaZaMichango::latest()->get();
        return view('backend.message.shukrani_ukumbusho_sample', compact(['data_ujumbe', 'data_aina']));
    }

    //saving the message template
    public function shukrani_ukumbusho_save(Request $request)
    {
        //getting data
        $must = [
            'kichwa' => 'required|unique:message_shukrani_ukumbushos',
            'kundi' => 'required',
            'aina_ya_toleo' => 'required',
            'ujumbe' => 'required',
        ];

        $error = Validator::make($request->all(), $must);

        if ($error->fails()) {
            return response()->json(['errors' => "Hakikisha umejaza sehemu zote zinazohitajika.. kichwa kisijirudie.."]);
        }

        //checking if we are having the shukrani then we must have $jina and $kiasi specified
        $kundi = $request->kundi;

        $ujumbe = $request->ujumbe;

        if ($kundi == "shukrani") {
            //validating the kiasi and jina
            $jina = "\$jina";
            $kiasi = "\$kiasi";
            $mwezi = "\$mwezi";

            //verifying the jina
            if (!(strpos($ujumbe, $jina) !== false)) {
                return response()->json(['errors' => "Hakikisha umehusisha alama ya \$jina kwenye ujumbe wako kwakua ujumbe wa shukrani utahusisha jina la muumini.."]);
            }

            //verifying the kiasi
            if (!(strpos($ujumbe, $kiasi) !== false)) {
                return response()->json(['errors' => "Hakikisha umehusisha alama ya \$kiasi kwenye ujumbe wako kwakua ujumbe wa shukrani utahusisha kiasi kilichotolewa na muumini.."]);
            }

            //verifying the mwezi
            if (!(strpos($ujumbe, $mwezi) !== false)) {
                return response()->json(['errors' => "Hakikisha umehusisha alama ya \$mwezi kwenye ujumbe wako kwakua ujumbe wa shukrani utahusisha mwezi husika.."]);
            }
        } else {
            //since it is a reminder we only need a name only kiasi is optional
            //verifying the jina
            $jina = "\$jina";
            $mwezi = "\$mwezi";

            if (!(strpos($ujumbe, $jina) !== false)) {
                return response()->json(['errors' => "Hakikisha umehusisha alama ya \$jina kwenye ujumbe wako kwakua ujumbe wa ukumbusho utahusisha jina la muumini.."]);
            }

            //verifying the mwezi
            if (!(strpos($ujumbe, $mwezi) !== false)) {
                return response()->json(['errors' => "Hakikisha umehusisha alama ya \$mwezi kwenye ujumbe wako kwakua ujumbe wa ukumbusho utahusisha mwezi husika.."]);
            }
        }

        //saving data
        $data_save = [
            'kichwa' => $request->kichwa,
            'kundi' => $kundi,
            'ujumbe' => $ujumbe,
            'aina_ya_toleo' => $request->aina_ya_toleo,
        ];

        $success = MessageShukraniUkumbusho::create($data_save);

        if ($success) {
            return response()->json(['success' => "Ujumbe umehifadhiwa kikamilifu.."]);
        } else {
            return response()->json(['errors' => "Imeshindikana kuhifadhi ujumbe jaribu tena.."]);
        }
    }

    //function to populate data in the modal
    public function shukrani_ukumbusho_edit($id)
    {
        $data = MessageShukraniUkumbusho::findOrFail($id);
        return response()->json(['result' => $data]);
    }

    //updating the message template
    public function shukrani_ukumbusho_update(Request $request)
    {

        //existing data
        $existing = MessageShukraniUkumbusho::findOrFail($request->hidden_id);

        $must = [
            'kichwa' => 'required|unique:message_shukrani_ukumbushos,kichwa,' . $existing->id,
            'kundi' => 'required',
            'aina_ya_toleo' => 'required',
            'ujumbe' => 'required',
        ];

        $error = Validator::make($request->all(), $must);

        if ($error->fails()) {
            return response()->json(['errors' => "Hakikisha umejaza sehemu zote zinazohitajika.. kichwa kisijirudie.."]);
        }

        //checking if we are having the shukrani then we must have $jina and $kiasi specified
        $kundi = $request->kundi;

        $ujumbe = $request->ujumbe;

        if ($kundi == "shukrani") {

            //validating the kiasi and jina
            $jina = "\$jina";
            $kiasi = "\$kiasi";
            $mwezi = "\$mwezi";

            //verifying the jina
            if (!(strpos($ujumbe, $jina) !== false)) {
                return response()->json(['errors' => "Hakikisha umehusisha alama ya \$jina kwenye ujumbe wako kwakua ujumbe wa shukrani utahusisha jina la muumini.."]);
            }

            //verifying the kiasi
            if (!(strpos($ujumbe, $kiasi) !== false)) {
                return response()->json(['errors' => "Hakikisha umehusisha alama ya \$kiasi kwenye ujumbe wako kwakua ujumbe wa shukrani utahusisha kiasi kilichotolewa na muumini.."]);
            }

            //verifying the mwezi
            if (!(strpos($ujumbe, $mwezi) !== false)) {
                return response()->json(['errors' => "Hakikisha umehusisha alama ya \$mwezi kwenye ujumbe wako kwakua ujumbe wa shukrani utahusisha mwezi husika.."]);
            }
        } else {
            //since it is a reminder we only need a name only kiasi is optional
            //verifying the jina
            $jina = "\$jina";
            $mwezi = "\$mwezi";

            if (!(strpos($ujumbe, $jina) !== false)) {
                return response()->json(['errors' => "Hakikisha umehusisha alama ya \$jina kwenye ujumbe wako kwakua ujumbe wa ukumbusho utahusisha jina la muumini.."]);
            }

            //verifying the mwezi
            if (!(strpos($ujumbe, $mwezi) !== false)) {
                return response()->json(['errors' => "Hakikisha umehusisha alama ya \$mwezi kwenye ujumbe wako kwakua ujumbe wa ukumbusho utahusisha mwezi husika.."]);
            }
        }

        //updating data
        $data_update = [
            'kichwa' => $request->kichwa,
            'kundi' => $kundi,
            'ujumbe' => $ujumbe,
            'aina_ya_toleo' => $request->aina_ya_toleo,
        ];

        $success = MessageShukraniUkumbusho::whereId($request->hidden_id)->update($data_update);

        if ($success) {
            return response()->json(['success' => "Ujumbe umebadilishwa kikamilifu.."]);
        } else {
            return response()->json(['errors' => "Imeshindikana kubadili ujumbe jaribu tena.."]);
        }
    }

    public function shukrani_ukumbusho_destroy($id)
    {
        $data = MessageShukraniUkumbusho::findOrFail($id);

        if ($data->delete()) {
            return response()->json(['success' => "Ujumbe umefutwa kikamilifu.."]);
        } else {
            return response()->json(['errors' => "imeshindikana kufuta ujumbe.."]);
        }
    }

    //function to get the message template
    public function shukrani_ukumbusho_message(Request $request)
    {
        $kichwa_id = $request->kichwa_id;

        //getting the message
        $data = MessageShukraniUkumbusho::findOrFail($kichwa_id);

        if ($data == "") {
            return response()->json(['errors' => "Hakuna taarifa za ujumbe kwa kichwa husika.."]);
        } else {
            return response()->json(['result' => $data]);
        }
    }

    //function to handle the messages
    public function shukrani_ukumbusho_send(Request $request)
    {

        //getting the date
        $wanafamilia = Mwanafamilia::all();
        $mwisho = date('Y-m-d', strtotime($request->mwisho));
        $kuanzia = date('Y-m-d', strtotime($request->kuanzia));

        $mwezi = $request->mwisho;
        $kichwa = $request->kichwa;
        $ujumbe = $request->ujumbe;
        $aina_ya_toleo = $request->aina_ya_toleo;

        $kundi = $request->kundi;
        $mwezi_query = $mwezi; //to be used in getting the details for michango and zakas
        $number_of_message = $request->number_of_message;

        //checking for empty values
        if ($mwezi == "") {
            return response()->json(['errors' => "Hakikisha umejaza mwezi.."]);
        }

        //what if we have empty kichwa
        if ($kichwa == "") {
            return response()->json(['errors' => "Hakikisha umejaza aina ya ujumbe.."]);
        }

        //verifying ujumbe wenyewe
        if ($ujumbe == "") {
            return response()->json(['errors' => "Hakikisha umejaza ujumbe wa kutumwa.."]);
        }

        //validating if we have the kiasi, mwezi and jina in message
        if ($kundi == "shukrani") {

            //validating the kiasi and jina
            $jina_verify = "\$jina";
            $kiasi_verify = "\$kiasi";
            $mwezi_verify = "\$mwezi";

            //verifying the jinal
            if (!(strpos($ujumbe, $jina_verify) !== false)) {
                return response()->json(['errors' => "Hakikisha umehusisha alama ya \$jina kwenye ujumbe wako kwakua ujumbe wa shukrani utahusisha jina la muumini.."]);
            }

            //verifying the kiasi
            if (!(strpos($ujumbe, $kiasi_verify) !== false)) {
                return response()->json(['errors' => "Hakikisha umehusisha alama ya \$kiasi kwenye ujumbe wako kwakua ujumbe wa shukrani utahusisha kiasi kilichotolewa na muumini.."]);
            }

            //verifying the mwezi
            if (!(strpos($ujumbe, $mwezi_verify) !== false)) {
                return response()->json(['errors' => "Hakikisha umehusisha alama ya \$mwezi kwenye ujumbe wako kwakua ujumbe wa shukrani utahusisha mwezi husika.."]);
            }

        } else {

            //since it is a reminder we only need a name only kiasi is optional
            //verifying the jina
            $jina_verify = "\$jina";
            $mwezi_verify = "\$mwezi";

            if (!(strpos($ujumbe, $jina_verify) !== false)) {
                return response()->json(['errors' => "Hakikisha umehusisha alama ya \$jina kwenye ujumbe wako kwakua ujumbe wa ukumbusho utahusisha jina la muumini.."]);
            }

            //verifying the mwezi
            if (!(strpos($ujumbe, $mwezi_verify) !== false)) {
                return response()->json(['errors' => "Hakikisha umehusisha alama ya \$mwezi kwenye ujumbe wako kwakua ujumbe wa ukumbusho utahusisha mwezi husika.."]);
            }

        }

        //converting the mwezi to another format
        $mwezi_u = $mwezi;
        $mwezi = Carbon::parse($mwezi)->format('d-F-Y');
        $mwezi_ukumbusho = Carbon::parse($mwezi_u)->format('F-Y');

        //getting list of people based on aina
        if ($aina_ya_toleo == "zaka") {

            //   dd($kundi);
            $months = getMonthListFromDate($kuanzia, $mwisho);
            // dd($months);
            if ($months) {
                $firstMonth = $months[array_key_first($months)];
                $secondMonth = $months[array_key_last($months)];
            } else {
                $firstMonth = Carbon::parse($kuanzia)->format('m');
                $secondMonth = Carbon::parse($mwisho)->format('m');
                //dd($secondMonth);
            }
            $waliotoa_zaka = collect();
            $wasiotoa_zaka = collect();
            $jumla_wanafamilia_zaka = 0;
            foreach ($wanafamilia as $familia) {
                $jumla_wanafamilia_zaka += 1;
                // $mchango_zaka = ZakaKilaMwezi::where('mwanafamilia_id', $familia->id)
                //     ->where('status', '=', 'ajalipa')
                //     ->whereBetween('mwezi', [$firstMonth, $secondMonth])
                //     ->get();


                      
                $mchango_zaka=Zaka::where('mwanajumuiya',$familia->id)
                ->whereBetween('tarehe',[$kuanzia,$mwisho])->get();

                if ($mchango_zaka->count()<=0) {
                    $wasiotoa_zaka->push($familia);
                } else {
                    $waliotoa_zaka->push($familia);
                }
                // dd(count($mchango_zaka));
                // if (count($mchango_zaka) > 0) {
                //     $wasiotoa_zaka->push($familia);
                // } else {
                //     $waliotoa_zaka->push($familia);

                // }
                //    }
            }

//dd($waliotoa_zaka);

            if ($kundi == "shukrani") {

            } else {
                if ($wasiotoa_zaka->isEmpty()) {
                    return response()->json(['info' => "Hakuna orodha ya waamini wasiolipa zaka kwa mwezi $mwezi_ukumbusho"]);
                }
            }
            //function to clean the message and append amount and name
            //------------------ IMPORTANT CONCEPT ---------------- */
            $mwezi = $mwezi_ukumbusho; //here we assign the month since the message of ukumbusho does not state date

            if ($kundi == "shukrani") {
                //dd($waliotoa_zaka);

                $cleaned_message_numbers = $this->cleanShukraniMessage($waliotoa_zaka, $mwezi, $ujumbe, $kuanzia, $mwisho, 'zaka');
                ///  dd($cleaned_message_numbers);
            } else {

                $cleaned_message_numbers = $this->cleanUkumbushoMessage($wasiotoa_zaka, $mwezi, $ujumbe, $kuanzia, $mwisho, 'zaka');
            }

            if ($cleaned_message_numbers == "CODE2021") {

                if ($kundi == 'shukrani') {
                    return response()->json(['errors' => "Hakuna taarifa za waliotoa zaka mpaka tarehe  $mwezi .."]);
                } else {
                    return response()->json(['errors' => "Hakuna taarifa za wasiotoa zaka kwa mwezi $mwezi_ukumbusho .."]);
                }
            } else {
                //counting total message
                $message = count($cleaned_message_numbers);

                //before sending getting the balance first
                $total_sms =$message;
                $sms_balance = $this->getSmsBalance();

                if ($total_sms > $sms_balance) {
                    return response()->json(['errors' => "Salio la meseji halitoshi kutuma meseji $total_sms tafadhali ongeza salio.."]);
                }

                $statue = $this->sendingMessage($cleaned_message_numbers);

//dd($statue);

                if ($statue == "SUCCESS") {
                    //saving message
                    $data_save = [
                        'mtumaji' => auth()->user()->email,
                        'mpokeaji' => $kundi == "shukrani" ? "wasiotoa zaka" : 'waliotoa zaka',
                        'ujumbe' => $ujumbe,
                        'status' => "CODE",
                        'idadi' => $total_sms,
                        'tarehe' => date('Y-m-d'),
                    ];

                    Message::create($data_save);

                    //deducting the balance
                    MessageBalance::decrement('balance', $total_sms);

                    return response()->json(['success' => "Meseji zote zimetumwa kikamilifu.."]);

                } elseif ($statue == "FAILED_ALL") {
                    return response()->json(['errors' => "Imeshindikana kutuma meseji za shukrani jaribu tena baadae.."]);
                } else {
                    $gap = $total_sms - $statue;
                    //deducting the balance
                    MessageBalance::decrement('balance', $gap);
                    return response()->json(['info' => "Jumla ya meseji $gap zimetumwa kati ya $total_sms"]);
                }
            }

        } elseif ($aina_ya_toleo == "all") {

           //  dd($aina_ya_toleo);

            $wanafamilia_waliolipa_walioahidi = collect();
            $wanafamilia_wasiolipa_waliohidi = collect();
            $waliolipa_wasioahidi = collect();
            $wasiolipa_wasioahidi = collect();

            $waliotoa_taslimu = collect();
            $waliotoa_bank = collect();
            $wasiotoa_kabisa = collect();

            $jumla_wanajumuiya = 0;

            $waliotoa_zaka = collect();
            $wasiotoa_zaka = collect();

            $aina_za_michangos = AinaZaMichango::all();

            //loop through aina za michango zote

            $months = getMonthListFromDate($kuanzia, $mwisho);
//dd($kuanzia);
            if ($months) {
                $firstMonth = $months[array_key_first($months)];
                $secondMonth = $months[array_key_last($months)];
            } else {
                $firstMonth = Carbon::parse($kuanzia)->format('m');
                $secondMonth = Carbon::parse($mwisho)->format('m');
                //dd($secondMonth);
            }

            $jumla_wanafamilia_zaka = 0;
            foreach ($wanafamilia as $familia) {
                $jumla_wanafamilia_zaka += 1;
                // $mchango_zaka = ZakaKilaMwezi::where('mwanafamilia_id', $familia->id)
                //     ->where('status', '=', 'ajalipa')
                //     ->whereBetween('mwezi', [$firstMonth, $secondMonth])
                //     ->get();

                // if (count($mchango_zaka) > 0) {
                //     $wasiotoa_zaka->push($familia);
                // } else {
                //     $waliotoa_zaka->push($familia);

                // }

                $mchango_zaka=Zaka::where('mwanajumuiya',$familia->id)
                ->whereBetween('tarehe',[$kuanzia,$mwisho])->get();

                if ($mchango_zaka->count()<=0) {
                    $wasiotoa_zaka->push($familia);
                } else {
                    $waliotoa_zaka->push($familia);
                }
                
                
            }

            foreach ($aina_za_michangos as $aina_za_mchango) {

                if (!is_null($aina_za_mchango->ahadi_ya_aina_za_mchango)) {
                    //get wanafamilia
                    $wanafamilia = Mwanafamilia::all();
                    //get ahadi ya kila mwanafamilia nipe id,naamount
                    foreach ($wanafamilia as $familia) {

                        $mwanafamilia_ahadi = AhadiMichangoGawaMwanafamilia::where('ahadi_michango_id', $aina_za_mchango->id)
                            ->where('mwanafamilia_id', $familia->id)->first();
                        !is_null($mwanafamilia_ahadi) ? $kiasi_alichoahidi = $mwanafamilia_ahadi->kiasi : $kiasi_alichoahidi = 0;

                        //get kiasi alichotoa kila mwanafamilia get na amount
                        $kiasi_alichotoa = AwamuMichango::where('ahadi_ainayamichango_id', $aina_za_mchango->ahadi_ya_aina_za_mchango->id)
                            ->where('mwanafamilia_id', $familia->id)
                            ->whereBetween('tarehe',[$kuanzia,$mwisho])
                            ->sum('kiasi');
                        //compare the amount alfu nipe wanafamila
                        //a.wasiotoa
                        if ($kiasi_alichoahidi > 0) {
                            //check kama kiasi alicholipa in kikubwa kuliko au sana na alicho ahidi
                            if ($kiasi_alichotoa >= $kiasi_alichoahidi) {
                                $wanafamilia_waliolipa_walioahidi->push($familia);
                            } else {
                                $wanafamilia_wasiolipa_waliohidi->push($familia);
                            }

                        } else {
                            //kalipa ila hakuahidi
                            if ($kiasi_alichotoa > 0) {
                                $waliolipa_wasioahidi->push($familia);
                            } else {
                                $wasiolipa_wasioahidi->push($familia);
                            }
                        }
                    }
                } else {
                    //michango isiyo na ahadi

                    //query by wanafamilia to get list ya waliotoa na wasiotoa
                    $aina_ya_mchango = AinaZaMichango::where('aina_ya_mchango', $aina_za_mchango->aina_ya_mchango)->first();
                    foreach ($wanafamilia as $familia) {
                        $jumla_wanajumuiya += 1;
                        //pata list ya wanafamilia kwenye mchango husika kwenye taslimu
                        $michango_taslimu_jumla_kiasi = MichangoTaslimu::where('aina_ya_mchango', $aina_za_mchango->aina_ya_mchango)
                        ->whereBetween('tarehe',[$kuanzia,$mwisho])
                            ->where('mwanafamilia', $familia->id)->sum('kiasi');
                        $michango_bank_jumla_kiasi = MichangoBenki::where('aina_ya_mchango', $aina_za_mchango->aina_ya_mchango)
                        ->whereBetween('tarehe',[$kuanzia,$mwisho])
                            ->where('mwanafamilia', $familia->id)->sum('kiasi');

                        if ($michango_taslimu_jumla_kiasi > 0) {
                            $waliotoa_taslimu->push($familia);
                        } elseif ($michango_bank_jumla_kiasi > 0) {$waliotoa_bank->push($familia);
                        } else { $wasiotoa_kabisa->push($familia);
                        }

                    }

                }

            }

            $familia_waliolipa_ahadi = $wanafamilia_waliolipa_walioahidi->merge($waliolipa_wasioahidi)->unique('id');
            $familia_zisizolipa_ahadi = $wanafamilia_wasiolipa_waliohidi->merge($wasiolipa_wasioahidi)->unique('id');
    
             
            $familia_waliolipa_mchango = $waliotoa_bank->merge($waliotoa_taslimu)->unique('id');
            $familia_zisizolipa_mchango = $wasiotoa_kabisa;

            $waliolipa = $familia_waliolipa_ahadi->merge($familia_waliolipa_mchango)->merge($waliotoa_zaka)->unique('id');

            $wasiolipa = $familia_zisizolipa_ahadi->merge($familia_zisizolipa_mchango)->merge($wasiotoa_zaka)->unique('id');
            //dd($wasiolipa);
            $kundi == 'shukrani' ? $datas = $waliolipa : $datas = $wasiolipa;
            
             // dd($datas);
            $cleaned_message_numbers = $this->cleanAllMessage($datas, $mwezi, $ujumbe, $kundi, $kuanzia, $mwisho);
              
         //  dd($cleaned_message_numbers);
            if ($cleaned_message_numbers == "CODE2021") {
                return response()->json(['errors' => "Hakuna taarifa  mwezi $mwezi_ukumbusho husika.."]);
            } else {
                //counting total message
                $message = count($cleaned_message_numbers);

                //before sending getting the balance first
                $total_sms = $message;

                //   dd($total_sms);
                $sms_balance = $this->getSmsBalance();

                if ($total_sms > $sms_balance) {
                    return response()->json(['errors' > "Salio la meseji halitoshi kutuma meseji $total_sms tafadhali ongeza salio.."]);
                }

                $statue = $this->sendingMessage($cleaned_message_numbers);

                //save to db wanajumuiya waliotumiwa message.

                if ($statue == "SUCCESS") {
                    //saving message
                    $data_save = [
                        'mtumaji' => auth()->user()->email,
                        'mpokeaji' => $kundi == "shukrani" ? "wasiotoa mchango  $aina_za_mchango->aina_ya_mchango" : "waliotoa mchango  $aina_za_mchango->aina_ya_mchango",
                        'ujumbe' => $ujumbe,
                        'status' => "CODE",
                        'idadi' => $total_sms,
                        'tarehe' => date('Y-m-d'),
                    ];

                    Message::create($data_save);

                    //deducting the balance
                    MessageBalance::decrement('balance', $total_sms);

                    return response()->json(['success' => "Meseji zote zimetumwa kikamilifu.."]);

                } elseif ($statue == "FAILED_ALL") {
                    return response()->json(['errors' => "Imeshindikana kutuma meseji  jaribu tena baadae.."]);
                } else {
                    $gap = $total_sms - $statue;
                    //deducting the balance
                    MessageBalance::decrement('balance', $gap);

                    return response()->json(['info' => "Jumla ya meseji $gap zimetumwa kati ya $total_sms"]);
                }
            }

        } else {
            //ukumbusho michango
            //getting data from mchango specific to those who contribute
            $data = MessageShukraniUkumbusho::findOrFail($kichwa);

            // dd($data);

            if ($data == "") {
                return response()->json(['Taarifa za mchango husika hazipo..']);
            } else {
                $mchango = $data->aina_ya_toleo;
            }

            $wanafamilia_waliolipa_walioahidi = collect();
            $wanafamilia_wasiolipa_waliohidi = collect();
            $waliolipa_wasioahidi = collect();
            $wasiolipa_wasioahidi = collect();
            $jumla_wanafamilia = 0;
            $wanafamilia = Mwanafamilia::all();

            $aina_za_mchango = AinaZaMichango::where('aina_ya_mchango', $mchango)->first();
            if (!is_null($aina_za_mchango->ahadi_ya_aina_za_mchango)) {
                //get ahadi ya kila mwanafamilia nipe id,naamount
                foreach ($wanafamilia as $familia) {
                    $jumla_wanafamilia += 1;
                    $mwanafamilia_ahadi = AhadiMichangoGawaMwanafamilia::where('ahadi_michango_id', $aina_za_mchango->id)
                        ->where('mwanafamilia_id', $familia->id)->first();
                    !is_null($mwanafamilia_ahadi) ? $kiasi_alichoahidi = $mwanafamilia_ahadi->kiasi : $kiasi_alichoahidi = 0;

                    //get kiasi alichotoa kila mwanafamilia get na amount
                    $kiasi_alichotoa = AwamuMichango::where('ahadi_ainayamichango_id', $aina_za_mchango->ahadi_ya_aina_za_mchango->id)
                        ->where('mwanafamilia_id', $familia->id)
                        ->whereBetween('tarehe',[$kuanzia,$mwisho])
                        ->sum('kiasi');
                    //compare the amount alfu nipe wanafamila
                    //a.wasiotoa
                    if ($kiasi_alichoahidi > 0) {
                        //check kama kiasi alicholipa in kikubwa kuliko au sana na alicho ahidi
                        if ($kiasi_alichotoa >= $kiasi_alichoahidi) {
                            $wanafamilia_waliolipa_walioahidi->push($familia);
                        } else {
                            $wanafamilia_wasiolipa_waliohidi->push($familia);
                        }

                    } else {
                        //kalipa ila hakuahidi
                        if ($kiasi_alichotoa > 0) {
                            $waliolipa_wasioahidi->push($familia);
                        } else {
                            $wasiolipa_wasioahidi->push($familia);
                        }
                    }

                }
                $familia_waliolipa = $wanafamilia_waliolipa_walioahidi->merge($waliolipa_wasioahidi)->unique('id');
                $familia_zisizolipa = $wanafamilia_wasiolipa_waliohidi->merge($wasiolipa_wasioahidi)->unique('id');

                //spotting out those who did not pay

                if ($kundi == "shukrani") {

                } else {
                    if ($familia_zisizolipa->isEmpty()) {
                        return response()->json(['info' => "Waamini wote wamelipa mchango wa $mchango"]);
                    }
                }

                //function to clean the message and append amount and name

                //------------------ IMPORTANT CONCEPT ---------------- */
                $mwezi = $mwezi_ukumbusho; //fix here we assign the month since the message of ukumbusho does not state date

                if ($kundi == "shukrani") {
                    $cleaned_message_numbers = $this->cleanShukraniMessage($familia_waliolipa, $mwezi, $ujumbe,$kuanzia,$mwisho,$mchango);
                } else {
                    $cleaned_message_numbers = $this->cleanUkumbushoMessage($familia_zisizolipa, $mwezi, $ujumbe,$kuanzia,$mwisho,$mchango);
                }

                if ($cleaned_message_numbers == "CODE2021") {
                    return response()->json(['errors' => "Hakuna taarifa  mwezi $mwezi_ukumbusho husika.."]);
                } else {
                    //counting total message
                    $message = count($cleaned_message_numbers);

                    //before sending getting the balance first
                    $total_sms = $message;

                    //   dd($total_sms);
                    $sms_balance = $this->getSmsBalance();

                    if ($total_sms > $sms_balance) {
                        return response()->json(['errors' => "Salio la meseji halitoshi kutuma meseji $total_sms tafadhali ongeza salio.."]);
                    }

                    $statue = $this->sendingMessage($cleaned_message_numbers);

                    //save to db wanajumuiya waliotumiwa message.

                    if ($statue == "SUCCESS") {
                        //saving message
                        $data_save = [
                            'mtumaji' => auth()->user()->email,
                            'mpokeaji' => "wasiotoa mchango $mchango",
                            'ujumbe' => $ujumbe,
                            'status' => "CODE",
                            'idadi' => $total_sms,
                            'tarehe' => date('Y-m-d'),
                        ];

                        Message::create($data_save);

                        //deducting the balance
                        MessageBalance::decrement('balance', $total_sms);

                        return response()->json(['success' => "Meseji zote zimetumwa kikamilifu.."]);

                    } elseif ($statue == "FAILED_ALL") {
                        return response()->json(['errors' => "Imeshindikana kutuma meseji  jaribu tena baadae.."]);
                    } else {
                        $gap = $total_sms - $statue;
                        //deducting the balance
                        MessageBalance::decrement('balance', $gap);

                        return response()->json(['info' => "Jumla ya meseji $gap zimetumwa kati ya $total_sms"]);
                    }
                }

            } else {

                //michango isiyo na ahadi

                $waliotoa_taslimu = collect();
                $waliotoa_bank = collect();
                $wasiotoa_kabisa = collect();
                $jumla_wanajumuiya = 0;
                //query by wanafamilia to get list ya waliotoa na wasiotoa
                $aina_ya_mchango = AinaZaMichango::where('aina_ya_mchango', $aina_za_mchango->aina_ya_mchango)->first();
                foreach ($wanafamilia as $familia) {

                    
                    $jumla_wanajumuiya += 1;
                    //pata list ya wanafamilia kwenye mchango husika kwenye taslimu
                    $michango_taslimu_jumla_kiasi = MichangoTaslimu::where('aina_ya_mchango', $aina_za_mchango->aina_ya_mchango)
                    ->whereBetween('tarehe',[$kuanzia,$mwisho])
                        ->where('mwanafamilia', $familia->id)->sum('kiasi');
                    $michango_bank_jumla_kiasi = MichangoBenki::where('aina_ya_mchango', $aina_za_mchango->aina_ya_mchango)
                    ->whereBetween('tarehe',[$kuanzia,$mwisho])
                        ->where('mwanafamilia', $familia->id)->sum('kiasi');

                    if ($michango_taslimu_jumla_kiasi > 0) {
                        $waliotoa_taslimu->push($familia);
                    } elseif ($michango_bank_jumla_kiasi > 0) {$waliotoa_bank->push($familia);
                    } else { $wasiotoa_kabisa->push($familia);}
                }

                $familia_waliolipa = $waliotoa_bank->merge($waliotoa_taslimu)->unique('id');
                $familia_zisizolipa = $wasiotoa_kabisa;

                if ($kundi == "shukrani") {

                } else {
                    if ($familia_zisizolipa->isEmpty()) {
                        return response()->json(['info' => "Waamini wote wamelipa mchango wa $mchango"]);
                    }
                }

                //function to clean the message and append amount and name

                //------------------ IMPORTANT CONCEPT ---------------- */
                $type = 'mchango';
                $mwezi = $mwezi_ukumbusho; //fix here we assign the month since the message of ukumbusho does not state date
                if ($kundi == "shukrani") {
                    $cleaned_message_numbers = $this->cleanShukraniMessage($familia_waliolipa, $mwezi, $ujumbe,$kuanzia,$mwisho,$mchango);
                } else {
                    $cleaned_message_numbers = $this->cleanUkumbushoMessage($familia_zisizolipa, $mwezi, $ujumbe,$kuanzia,$mwisho,$mchango);
                }

                ;if ($cleaned_message_numbers == "CODE2021") {
                    return response()->json(['errors' => "Hakuna taarifa za wasiotoa michango kwa mwezi $mwezi_ukumbusho husika.."]);
                } else {
                    //counting total message
                    $message = count($cleaned_message_numbers);
                    //before sending getting the balance first
                    $total_sms = $message;
                    //   dd($total_sms);
                    $sms_balance = $this->getSmsBalance();

                    if ($total_sms > $sms_balance) {
                        return response()->json(['errors' => "Salio la meseji halitoshi kutuma meseji $total_sms tafadhali ongeza salio.."]);
                    }

                    $statue = $this->sendingMessage($cleaned_message_numbers);
                    //save to db wanajumuiya waliotumiwa message.

                    if ($statue == "SUCCESS") {
                        //saving message
                        $data_save = [
                            'mtumaji' => auth()->user()->email,
                            'mpokeaji' => "wasiotoa mchango $mchango",
                            'ujumbe' => $ujumbe,
                            'status' => "CODE",
                            'idadi' => $total_sms,
                            'tarehe' => date('Y-m-d'),
                        ];

                        Message::create($data_save);
                        //deducting the balance
                        MessageBalance::decrement('balance', $total_sms);

                        return response()->json(['success' => "Meseji zote zimetumwa kikamilifu.."]);

                    } elseif ($statue == "FAILED_ALL") {
                        return response()->json(['errors' => "Imeshindikana kutuma meseji jaribu tena baadae.."]);
                    } else {
                        $gap = $total_sms - $statue;
                        //deducting the balance
                        MessageBalance::decrement('balance', $gap);

                        return response()->json(['info' => "Jumla ya meseji $gap zimetumwa kati ya $total_sms"]);
                    }
                }

            }
        }

    }

    //function that clean shukrani messages
    private function cleanShukraniMessage($data_michango, $mwezi, $ujumbe, $kuanzia, $mwisho, $type)
    {
      //   dd($data_michango);

        $months = getMonthListFromDate($kuanzia, $mwisho);
        if ($months) {
            $firstMonth = $months[array_key_first($months)];
            $secondMonth = $months[array_key_last($months)];
        } else {
            $firstMonth = Carbon::parse($kuanzia)->format('m');
            $secondMonth = Carbon::parse($mwisho)->format('m');
            //dd($secondMonth);
        }

        if ($firstMonth == $secondMonth) {
            $mwezi_new = Carbon::parse($mwisho)->format('F-Y');
        } else {
            $mwezi_wa_kwanza = Carbon::parse($kuanzia)->format('F');
            $mwezi_wa_mwisho = Carbon::parse($mwisho)->format('F-Y');

            $mwezi_new = $mwezi_wa_kwanza . ' Mpaka ' . $mwezi_wa_mwisho;
        }
        //validating first

        if ($data_michango->isNotEmpty()) {

            $clean_this = ["\$jina", "\$kiasi", "\$mwezi"];

            foreach ($data_michango as $data) {

                //  dd($data_michango);
                if ($type == 'zaka') {
                    $message = '';
                    //zaka
                    // $zaka = ZakaKilaMwezi::where('mwanafamilia_id', '=', $data->id)
                    //     ->where('status', '=', 'kalipa')
                    //     ->whereBetween('mwezi', [$firstMonth, $secondMonth])
                    //     ->sum('kiasi');
                    //   dd($zaka);

                    $zaka=Zaka::where('mwanajumuiya',$data->id)
                    ->whereBetween('tarehe',[$kuanzia,$mwisho])
                    ->sum('kiasi');

                    if ($zaka > 0) {
                        $message = 'Zaka ' . number_format($zaka);
                    }
                }else{

                    $taslimu = MichangoTaslimu::where('mwanafamilia', $data->id)
                    ->whereBetween('mwezi', [$kuanzia, $mwisho])
                ->where('aina_ya_mchango',$type)
                    ->get();
                $benki = MichangoBenki::where('mwanafamilia', $data->id)
                ->whereBetween('mwezi', [$kuanzia, $mwisho])
                ->where('aina_ya_mchango',$type)
                    ->get();
                $taslimu_group = $taslimu->mapToGroups(function ($item, $key) {
                    return [$item['aina_ya_mchango'] => $item];
                });

                $benki_group = $benki->mapToGroups(function ($item, $key) {
                    return [$item['aina_ya_mchango'] => $item];
                });

                // dd($taslimu_group);
                $array_kiasi = [];
                $array_jina = [];
                $array_kiasi_benk = [];
                $array_jina_benk = [];

                foreach ($taslimu_group as $index => $group) {

                    array_push($array_kiasi, $group->sum('kiasi'));
                    array_push($array_jina, $index);
                }

                foreach ($benki_group as $index => $group) {

                    array_push($array_kiasi_benk, $group->sum('kiasi'));
                    array_push($array_jina_benk, $index);
                }
                $combined_taslimu = array_combine($array_jina, $array_kiasi);

                $combined_benk = array_combine($array_jina_benk, $array_kiasi_benk);
                $combined_all = array();
                foreach (array_keys($combined_taslimu + $combined_benk) as $key) {
                    $combined_all[$key] = @($combined_taslimu[$key] + $combined_benk[$key]);
                }

                $message = "";
                foreach ($combined_all as $key => $value) {

                    $message .= $key . ':' . number_format($value) . ' ';
                }
                $message=$message;
                }

                $cleaned_text[] = [

                    'namba' => "255" . ltrim($data->mawasiliano, 0),
                    'ujumbe' => str_replace($clean_this, [$data->jina_kamili, $message, $mwezi_new], $ujumbe), //replacing the jina, kiasi and mwezi
                ];
            }

          //dd($cleaned_text);

            return $cleaned_text;
        } else {
            return "CODE2021";
        }
    }

    //function that clean ukumbusho messages

    private function cleanAllMessage($datas, $mwezi, $ujumbe, $kundi, $kuanzia, $mwisho)
    {

        // dd($datas);
        //validating first
        if ($datas->isNotEmpty()) {

            ($kundi == 'shukrani') ? $clean_this = ["\$jina", "\$kiasi", "\$mwezi"] : $clean_this = ["\$jina", "\$mwezi"];

            $months = getMonthListFromDate($kuanzia, $mwisho);
            //dd($kuanzia);
            if ($months) {
                $firstMonth = $months[array_key_first($months)];
                $secondMonth = $months[array_key_last($months)];
            } else {
                $firstMonth = Carbon::parse($kuanzia)->format('m');
                $secondMonth = Carbon::parse($mwisho)->format('m');
                //dd($secondMonth);
            }

            if ($firstMonth == $secondMonth) {
                $mwezi_new = Carbon::parse($mwisho)->format('F-Y');
            } else {
                $mwezi_wa_kwanza = Carbon::parse($kuanzia)->format('F');
                $mwezi_wa_mwisho = Carbon::parse($mwisho)->format('F-Y');

                $mwezi_new = $mwezi_wa_kwanza . ' Mpaka ' . $mwezi_wa_mwisho;
            }
            //    dd($mwezi_new);

            // $mwezi_ukumbusho = Carbon::parse($mwezi_u)->format('F-Y');

            foreach ($datas as $data) {

                if ($kundi == 'shukrani') {

                    $zaka_message = '';
                    //zaka
                    // $zaka = ZakaKilaMwezi::where('mwanafamilia_id', '=',$data->id)
                    //     ->whereBetween('mwezi', [$firstMonth, $secondMonth])
                    //     ->where('status', '=', 'kalipa')
                    //     ->sum('kiasi');

                        $zaka=Zaka::where('mwanajumuiya',$data->id)
                        ->whereBetween('tarehe',[$kuanzia,$mwisho])
                        ->sum('kiasi');

                      //  dd($zaka);
                    if ($zaka > 0) {
                        $zaka_message = 'Zaka: ' . number_format($zaka).' ';
                    }
                    $taslimu = MichangoTaslimu::where('mwanafamilia', $data->id)
                    ->whereBetween('tarehe',[$kuanzia,$mwisho])
                        ->get();
                    $benki = MichangoBenki::where('mwanafamilia', $data->id)
                    ->whereBetween('tarehe',[$kuanzia,$mwisho])
                        ->get();
                    $taslimu_group = $taslimu->mapToGroups(function ($item, $key) {
                        return [$item['aina_ya_mchango'] => $item];
                    });

                    $benki_group = $benki->mapToGroups(function ($item, $key) {
                        return [$item['aina_ya_mchango'] => $item];
                    });

                    // dd($taslimu_group);
                    $array_kiasi = [];
                    $array_jina = [];
                    $array_kiasi_benk = [];
                    $array_jina_benk = [];

                    foreach ($taslimu_group as $index => $group) {

                        array_push($array_kiasi, $group->sum('kiasi'));
                        array_push($array_jina, $index);
                    }

                    foreach ($benki_group as $index => $group) {

                        array_push($array_kiasi_benk, $group->sum('kiasi'));
                        array_push($array_jina_benk, $index);
                    }
                    $combined_taslimu = array_combine($array_jina, $array_kiasi);

                    $combined_benk = array_combine($array_jina_benk, $array_kiasi_benk);
                    $combined_all = array();
                    foreach (array_keys($combined_taslimu + $combined_benk) as $key) {
                        $combined_all[$key] = @($combined_taslimu[$key] + $combined_benk[$key]);
                    }

                    $message = "";
                    foreach ($combined_all as $key => $value) {

                        $message .= $key . ':' . number_format($value) . ' ';
                    }
                    $last_message = $zaka_message . $message;

                    $cleaned_text[] = [
                        'namba' => "255" . ltrim($data->mawasiliano, 0),
                        'ujumbe' => str_replace($clean_this, [$data->jina_kamili, $last_message, $mwezi_new], $ujumbe), //replacing the jina and mwezi
                    ];
                    //  dd($cleaned_text);

                } else {

                    $cleaned_text[] = [
                        'namba' => "255" . ltrim($data->mawasiliano, 0),
                        'ujumbe' => str_replace($clean_this, [$data->jina_kamili, $mwezi], $ujumbe), //replacing the jina and mwezi
                    ];
                }

            }

      //dd($cleaned_text);

      return $cleaned_text;
    
        } else {
            return "CODE2021";
        }
    }

    private function cleanUkumbushoMessage($data_wasiolipa, $mwezi, $ujumbe, $kuanzia, $mwisho, $type)
    {

        //validating first

        $months = getMonthListFromDate($kuanzia, $mwisho);
        if ($months) {
            $firstMonth = $months[array_key_first($months)];
            $secondMonth = $months[array_key_last($months)];
        } else {
            $firstMonth = Carbon::parse($kuanzia)->format('m');
            $secondMonth = Carbon::parse($mwisho)->format('m');
            //dd($secondMonth);
        }

        if ($firstMonth == $secondMonth) {
            $mwezi_new = Carbon::parse($mwisho)->format('F-Y');
        } else {
            $mwezi_wa_kwanza = Carbon::parse($kuanzia)->format('F');
            $mwezi_wa_mwisho = Carbon::parse($mwisho)->format('F-Y');

            $mwezi_new = $mwezi_wa_kwanza . ' Mpaka ' . $mwezi_wa_mwisho;
        }
        if ($data_wasiolipa->isNotEmpty()) {

            $clean_this = ["\$jina", "\$mwezi"];

            foreach ($data_wasiolipa as $data) {

                $cleaned_text[] = [
                    'namba' => "255" . ltrim($data->mawasiliano, 0),
                    'ujumbe' => str_replace($clean_this, [$data->jina_kamili, $mwezi_new], $ujumbe), //replacing the jina and mwezi
                ];
            }

//dd($cleaned_text);

            return $cleaned_text;
        } else {
            return "CODE2021";
        }
    }

    public function sendingMessage($cleaned_message_numbers)
    {
        $api_key = env('BONGO_LIVE_KEY');
        $secret_key = env('BONGO_LIVE_SECRET');
        $sender_info = env('BONGO_SENDER_ID');

        $messages = count($cleaned_message_numbers);
        $errors = 0; //tracking errors
        $success = 0; //tracking success

        foreach ($cleaned_message_numbers as $key => $data) {
            $namba = $data['namba'];
            $ujumbe = $data['ujumbe'];

            $postData = array(
                'source_addr' => $sender_info,
                'encoding' => 0,
                'schedule_time' => '',
                'message' => $ujumbe,
                'recipients' => [array('recipient_id' => '1', 'dest_addr' => $namba)],
            );

            $Url = 'https://apisms.beem.africa/v1/send';

            $ch = curl_init($Url);
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt_array($ch, array(
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => array(
                    'Authorization:Basic ' . base64_encode("$api_key:$secret_key"),
                    'Content-Type: application/json',
                ),
                CURLOPT_POSTFIELDS => json_encode($postData),
            ));

            $response = curl_exec($ch);

            if ($response === false) {
                $failure = $response;
                die(curl_error($ch));
                $errors = $errors + 1;
            } else {
                $success = $success + 1;
            }

        }

        //do something here
        if ($errors == 0) {
            return "SUCCESS";
        } elseif ($errors == $messages) {
            return "FAILED_ALL";
        } else {
            $gap = $messages - $errors;
            // $gap = $message - $errors;
            return $gap;
        }
    }

    //function to handle sms balance
    private function getSmsBalance()
    {
        $balance = MessageBalance::latest()->value('balance');
        return $balance;
    }

    public function ajax_get_number_wasiotoa(Request $request)
    {
        $wanafamilia = Mwanafamilia::all();
        $mwisho = date('Y-m-d', strtotime($request->mwisho));
        $kuanzia = date('Y-m-d', strtotime($request->kuanzia));

        $aina_ya_ujumbe_id = $request->kichwa;
        $wanafamilia_waliolipa_walioahidi = 0;
        $wanafamilia_wasiolipa_waliohidi = 0;
        $waliolipa_wasioahidi = 0;
        $wasiolipa_wasioahidi = 0;
        $jumla_wanafamilia = 0;

        $data = MessageShukraniUkumbusho::find($aina_ya_ujumbe_id);

        $html = '';
        if ($data == "") {

            return response()->json(['Info' => 'Taarifa za mchango husika hazipo..']);
        } else {
            $mchango = $data->aina_ya_toleo;
        }

        //  dd($aina_ya_ujumbe->aina_ya_toleo);
        if ($data->aina_ya_toleo == 'zaka') {

            $months = getMonthListFromDate($kuanzia, $mwisho);
            if ($months) {
                $firstMonth = $months[array_key_first($months)];
                $secondMonth = $months[array_key_last($months)];
            } else {
                $firstMonth = Carbon::parse($kuanzia)->format('m');
                $secondMonth = Carbon::parse($mwisho)->format('m');
                //dd($secondMonth);
            }
            $waliotoa_zaka = 0;
            $wasiotoa_zaka = 0;
            $jumla_wanafamilia_zaka = 0;
            foreach ($wanafamilia as $familia) {
                $jumla_wanafamilia_zaka += 1;
                // $mchango_zaka = ZakaKilaMwezi::where('mwanafamilia_id', $familia->id)
                //     ->where('status', '=', 'ajalipa')
                //     ->whereBetween('mwezi', [$firstMonth, $secondMonth])
                //     ->get();

                // dd($mchango_zaka);

                $mchango_zaka=Zaka::where('mwanajumuiya',$familia->id)
                ->whereBetween('tarehe',[$kuanzia,$mwisho])->get();


             //   dd($mchango_zaka->count());

                // if ($mchango_zaka->count()) {
                //     $wasiotoa_zaka += 1;
                // } else {
                //     $waliotoa_zaka += 1;
                // }

                if ($mchango_zaka->count()<=0) {
                    $wasiotoa_zaka += 1;
                } else {
                    $waliotoa_zaka += 1;
                }

            }

            // dd($wasiotoa_zaka);
            $url = "waliotoa_wasiotoa/?mchango=zaka&jumuiya=all&wachangiaji=all";
            
            $html .= '<tr><th>Mchango</th><th>Waliotoa</th><th>Wasiotoa</th><th>Jumla</th></tr>
                    <tr><td><a href="' . $url . '" target="_blank">' . $mchango . '</a></td><td>' . $waliotoa_zaka . '</td><td>' . $wasiotoa_zaka . '</td><td>' . $jumla_wanafamilia_zaka . '</td></tr>';
            echo json_encode([
                'table' => $html,
            ]);

            //zaka
        } elseif ($data->aina_ya_toleo == 'all') {
            //find all michango and zaka loop through
            $aina_za_michangos = AinaZaMichango::all();

            //loop through aina za michango zote
            $html .= '<tr><th>Mchango</th><th>Waliotoa</th><th>Wasiotoa</th><th>Jumla</th></tr>';

            $months = getMonthListFromDate($kuanzia, $mwisho);
            if ($months) {
                $firstMonth = $months[array_key_first($months)];
                $secondMonth = $months[array_key_last($months)];
            } else {
                $firstMonth = Carbon::parse($kuanzia)->format('m');
                $secondMonth = Carbon::parse($mwisho)->format('m');
                //dd($secondMonth);
            }
            $waliotoa_zaka = 0;
            $wasiotoa_zaka = 0;
            $jumla_wanafamilia_zaka = 0;
            foreach ($wanafamilia as $familia) {
                $jumla_wanafamilia_zaka += 1;
                // $mchango_zaka = ZakaKilaMwezi::where('mwanafamilia_id', $familia->id)
                //     ->where('status', '=', 'ajalipa')
                //     ->whereBetween('mwezi', [$firstMonth, $secondMonth])
                //     ->get();

                // if (count($mchango_zaka) > 0) {
                //     $wasiotoa_zaka += 1;
                // } else {
                //     $waliotoa_zaka += 1;
                // }
                
                $mchango_zaka=Zaka::where('mwanajumuiya',$familia->id)
                ->whereBetween('tarehe',[$kuanzia,$mwisho])->get();

                if ($mchango_zaka->count()<=0) {
                    $wasiotoa_zaka += 1;
                } else {
                    $waliotoa_zaka += 1;
                }
            }
            $url = "waliotoa_wasiotoa/?mchango=zaka&jumuiya=all&wachangiaji=all";
            $html .= '<tr><td><a href="' . $url . '" target="_blank">Zaka</a></td><td>' . $waliotoa_zaka . '</td><td>' . $wasiotoa_zaka . '</td><td>' . $jumla_wanafamilia_zaka . '</td></tr>';

            foreach ($aina_za_michangos as $aina_za_mchango) {

                if (!is_null($aina_za_mchango->ahadi_ya_aina_za_mchango)) {
                    $wanafamilia = Mwanafamilia::all();
                    //get ahadi ya kila mwanafamilia nipe id,naamount
                    foreach ($wanafamilia as $familia) {
                        $jumla_wanafamilia += 1;
                        $mwanafamilia_ahadi = AhadiMichangoGawaMwanafamilia::where('ahadi_michango_id', $aina_za_mchango->id)
                            ->where('mwanafamilia_id', $familia->id)->first();
                        !is_null($mwanafamilia_ahadi) ? $kiasi_alichoahidi = $mwanafamilia_ahadi->kiasi : $kiasi_alichoahidi = 0;

                        //get kiasi alichotoa kila mwanafamilia get na amount
                        $kiasi_alichotoa = AwamuMichango::where('ahadi_ainayamichango_id', $aina_za_mchango->ahadi_ya_aina_za_mchango->id)
                            ->where('mwanafamilia_id', $familia->id)
                            ->whereBetween('tarehe',[$kuanzia,$mwisho])
                            ->sum('kiasi');
                        //compare the amount alfu nipe wanafamila
                        //a.wasiotoa
                        if ($kiasi_alichoahidi > 0) {
                            //check kama kiasi alicholipa in kikubwa kuliko au sana na alicho ahidi
                            if ($kiasi_alichotoa >= $kiasi_alichoahidi) {
                                $wanafamilia_waliolipa_walioahidi += 1;
                            } else {
                                $wanafamilia_wasiolipa_waliohidi += 1;
                            }

                        } else {
                            //kalipa ila hakuahidi
                            if ($kiasi_alichotoa > 0) {
                                $waliolipa_wasioahidi += 1;
                            } else {
                                $wasiolipa_wasioahidi += 1;
                            }
                        }

                    }
                    $url = "waliotoa_wasiotoa/?mchango=$aina_za_mchango->id&jumuiya=all&wachangiaji=all";
                    $html .= ' <tr><td><a href="' . $url . '" target="_blank">' . $aina_za_mchango->aina_ya_mchango . '</a></td><td>' . ($wanafamilia_waliolipa_walioahidi + $waliolipa_wasioahidi) . '</td><td>' . ($wasiolipa_wasioahidi + $wanafamilia_wasiolipa_waliohidi) . '</td><td>' . $jumla_wanafamilia . '</td></tr>';
                } else {
                    //michango isiyo na ahadi
                    $waliotoa_taslimu = 0;
                    $waliotoa_bank = 0;
                    $wasiotoa_kabisa = 0;
                    $jumla_wanajumuiya = 0;
                    //query by wanafamilia to get list ya waliotoa na wasiotoa
                    $aina_ya_mchango = AinaZaMichango::where('aina_ya_mchango', $aina_za_mchango->aina_ya_mchango)->first();
                    foreach ($wanafamilia as $familia) {
                        $jumla_wanajumuiya += 1;
                        //pata list ya wanafamilia kwenye mchango husika kwenye taslimu
                        $michango_taslimu_jumla_kiasi = MichangoTaslimu::where('aina_ya_mchango', $aina_za_mchango->aina_ya_mchango)
                        ->whereBetween('tarehe',[$kuanzia,$mwisho])
                            ->where('mwanafamilia', $familia->id)->sum('kiasi');
                        $michango_bank_jumla_kiasi = MichangoBenki::where('aina_ya_mchango', $aina_za_mchango->aina_ya_mchango)
                        ->whereBetween('tarehe',[$kuanzia,$mwisho])
                            ->where('mwanafamilia', $familia->id)->sum('kiasi');

                        if ($michango_taslimu_jumla_kiasi > 0) {
                            $waliotoa_taslimu += 1;
                        } elseif ($michango_bank_jumla_kiasi > 0) {$waliotoa_bank += 1;
                        } else { $wasiotoa_kabisa += 1;}

                    }
                    $url = "waliotoa_wasiotoa/?mchango=$aina_ya_mchango->id&jumuiya=all&wachangiaji=all";

                    $html .= '<tr><td><a href="' . $url . '" target="_blank">' . $aina_za_mchango->aina_ya_mchango . '</a></td><td>' . ($waliotoa_taslimu + $waliotoa_bank) . '</td><td>' . ($wasiotoa_kabisa) . '</td><td>' . $jumla_wanajumuiya . '</td></tr>';

                }

            }

            echo json_encode([
                'table' => $html,
            ]);

        } else {

            $aina_za_mchango = AinaZaMichango::where('aina_ya_mchango', $mchango)->first();
            if (!is_null($aina_za_mchango->ahadi_ya_aina_za_mchango)) {
                //get ahadi ya kila mwanafamilia nipe id,naamount
                foreach ($wanafamilia as $familia) {
                    $jumla_wanafamilia += 1;
                    $mwanafamilia_ahadi = AhadiMichangoGawaMwanafamilia::where('ahadi_michango_id', $aina_za_mchango->id)
                        ->where('mwanafamilia_id', $familia->id)->first();
                    !is_null($mwanafamilia_ahadi) ? $kiasi_alichoahidi = $mwanafamilia_ahadi->kiasi : $kiasi_alichoahidi = 0;

                    //get kiasi alichotoa kila mwanafamilia get na amount
                    $kiasi_alichotoa = AwamuMichango::where('ahadi_ainayamichango_id', $aina_za_mchango->ahadi_ya_aina_za_mchango->id)
                        ->where('mwanafamilia_id', $familia->id)
                        ->whereBetween('tarehe',[$kuanzia,$mwisho])
                        ->sum('kiasi');
                    //compare the amount alfu nipe wanafamila
                    //a.wasiotoa
                    if ($kiasi_alichoahidi > 0) {
                        //check kama kiasi alicholipa in kikubwa kuliko au sana na alicho ahidi
                        if ($kiasi_alichotoa >= $kiasi_alichoahidi) {
                            $wanafamilia_waliolipa_walioahidi += 1;
                        } else {
                            $wanafamilia_wasiolipa_waliohidi += 1;
                        }

                    } else {
                        //kalipa ila hakuahidi
                        if ($kiasi_alichotoa > 0) {
                            $waliolipa_wasioahidi += 1;
                        } else {
                            $wasiolipa_wasioahidi += 1;
                        }
                    }

                }
                $url = "waliotoa_wasiotoa/?mchango=$aina_za_mchango->id&jumuiya=all&wachangiaji=all";
                $html .= '<tr><th>Mchango</th><th>Waliotoa</th><th>Wasiotoa</th><th>Jumla</th></tr>
              <tr><td><a href="' . $url . '">' . $mchango . '</a></td><td>' . ($wanafamilia_waliolipa_walioahidi + $waliolipa_wasioahidi) . '</td><td>' . ($wasiolipa_wasioahidi + $wanafamilia_wasiolipa_waliohidi) . '</td><td>' . $jumla_wanafamilia . '</td></tr>';

                echo json_encode([
                    'table' => $html,
                ]);

            } else {
                $waliotoa_taslimu = 0;
                $waliotoa_bank = 0;
                $wasiotoa_kabisa = 0;
                $jumla_wanajumuiya = 0;
                //query by wanafamilia to get list ya waliotoa na wasiotoa
                $aina_ya_mchango = AinaZaMichango::where('aina_ya_mchango', $mchango)->first();
                foreach ($wanafamilia as $familia) {
                    $jumla_wanajumuiya += 1;
                    //pata list ya wanafamilia kwenye mchango husika kwenye taslimu
                    $michango_taslimu_jumla_kiasi = MichangoTaslimu::where('aina_ya_mchango', $mchango)
                    ->whereBetween('tarehe',[$kuanzia,$mwisho])
                        ->where('mwanafamilia', $familia->id)->sum('kiasi');
                    $michango_bank_jumla_kiasi = MichangoBenki::where('aina_ya_mchango', $mchango)
                    ->whereBetween('tarehe',[$kuanzia,$mwisho])
                        ->where('mwanafamilia', $familia->id)->sum('kiasi');

                    if ($michango_taslimu_jumla_kiasi > 0) {
                        $waliotoa_taslimu += 1;
                    } elseif ($michango_bank_jumla_kiasi > 0) {$waliotoa_bank += 1;
                    } else { $wasiotoa_kabisa += 1;}

                }
                $url = "waliotoa_wasiotoa/?mchango=$aina_ya_mchango->id&jumuiya=all&wachangiaji=all";
                $html .= '<tr><th>Mchango</th><th>Waliotoa</th><th>Wasiotoa</th><th>Jumla</th></tr>
                    <tr><td><a href="' . $url . '" target="_blank">' . $mchango . '</a></td><td>' . ($waliotoa_taslimu + $waliotoa_bank) . '</td><td>' . ($wasiotoa_kabisa) . '</td><td>' . $jumla_wanajumuiya . '</td></tr>';
                echo json_encode([
                    'table' => $html,
                ]);

            }

        }

    }

    public function schedule(){
        $now= Carbon::now();
        $month=$now->month;
        $wanafamilia=Mwanafamilia::all();
        foreach($wanafamilia as $familia)
        {
            //check if mwanafilia exists in an existing month
            $zaka_kila_mwezi=ZakaKilaMwezi::where('mwanafamilia_id',$familia->id)
            ->where('mwezi',$month)->first();
           
            if(is_null($zaka_kila_mwezi)){
                ZakaKilaMwezi::create([
                   'mwanafamilia_id'=>$familia->id,
                    'mwezi'=>$month,
                ]);
            }
            
        }

        echo 'done';

    }


    public function schedule_with_zaka(){

        $months=['01','02','03','04','05','06','07','08','09','10','11','12'];
        $now= Carbon::now();
        $current_month=$now->month;
        $wanafamilia=Mwanafamilia::all();
        foreach($wanafamilia as $familia){
         //create a schedule for every month of each mwanafamilia.

         foreach($months as $month){

            $zaka_za_mwezi_huo=Zaka::where('mwanajumuiya',$familia->id)
            ->whereMonth('tarehe',$month)
            ->sum('kiasi');
             
              if($zaka_za_mwezi_huo>0){
                ZakaKilaMwezi::create([
                    'mwanafamilia_id'=>$familia->id,
                    'kiasi'=>($zaka_za_mwezi_huo),
                    'status'=>'kalipa',
                    'mwezi'=>$month
                 ]);
              }else{
                ZakaKilaMwezi::create([
                    'mwanafamilia_id'=>$familia->id,
                    'status'=>'ajalipa',
                    'mwezi'=>$month
                 ]);
              }

         }
        
        }
  
          echo 'done';

    }
}
