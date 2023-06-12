<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/schedule','MessageShukraniUkumbushoController@schedule');
Route::get('/schedule_with_zaka','MessageShukraniUkumbushoController@schedule_with_zaka');

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/','WavutiController@homepage');
Route::get('kanda_zetu','WavutiController@kanda_zetu');
Route::get('jumuiya_zetu','WavutiController@jumuiya_zetu');
Route::get('mashirika_ya_kitume','WavutiController@mashirika_ya_kitume');
Route::get('matukio_guest','WavutiController@matukio');
Route::get('misa','WavutiController@misa');
Route::get('matangazo_yetu','WavutiController@matangazo_yetu');
Route::get('historia_leo','WavutiController@historia_leo');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth']], function(){

    //the controller for handling centre details
    Route::resource('centre_details','CentreDetailController');
    Route::post('centre_details/edit','CentreDetailController@store')->name('centre_details.store');
    Route::post('centre_details/update','CentreDetailController@update')->name('centre_details.update');
    Route::get('nyaraka','CentreDetailController@documents');
    Route::post('pakua_jumuiya','CentreDetailController@pakua_jumuiya')->name('pukua_jumuiya.excel');
    Route::post('pakua_jumuiya_mwanafamilia','CentreDetailController@pakua_jumuiya_mwanafamilia')->name('pukua_jumuiya_mwanafamilia.excel');
    Route::get('/tafuta','CentreDetailController@tafuta');
    Route::get('/sahihisha_mfumo','SahihishaController@index')->name('sahihisha.index');
    Route::post('/update_sahihisha','SahihishaController@update_sahihisha')->name('update_sahihisha');
    Route::get('/activity_logs','CentreDetailController@activityLogs')->name('activity.index');
    Route::get('/activity_logs/zaka','CentreDetailController@activityLogsZaka')->name('activity.zaka');
    Route::get('/activity_logs/zaka/details/{id}/{date}','CentreDetailController@activityLogsZakaDetails')->name('activity.details');
    Route::get('/activity_logs/zaka/details_wanajumuiya/{date}/{id}/data','CentreDetailController@activityLogsZakaDetailsJumuiya')->name('activity.details_wanajumuiya');
    // the controller to manage the usaidizi
    Route::get('usaidizi','UsaidiziController@usaidizi_index');
    Route::get('orodha_majina','UsaidiziController@orodha_majina_index');
    Route::post('orodha_majina/upload','UsaidiziController@orodha_majina_import')->name('orodha_majina.orodha_majina_import');
    Route::get('orodha_majina/destroy/{id}','UsaidiziController@orodha_majina_destroy');
    Route::get('orodha_majina/truncate/','UsaidiziController@orodha_majina_truncate');
    Route::get('orodha_majina/edit/{id}','UsaidiziController@orodha_majina_edit');
    Route::post('orodha_majina/update','UsaidiziController@orodha_majina_update')->name('orodha_majina.orodha_majina_update');
    Route::get('orodha_familia','UsaidiziController@orodha_familia_index');
    Route::post('orodha_familia/store','UsaidiziController@orodha_familia_store')->name('orodha_familia.orodha_familia_store');
    Route::get('orodha_familia/edit/{id}','UsaidiziController@orodha_familia_edit');
    Route::post('orodha_familia/update','UsaidiziController@orodha_familia_update')->name('orodha_familia.orodha_familia_update');
    Route::get('orodha_familia/destroy/{id}','UsaidiziController@orodha_familia_destroy');
    Route::get('orodha_familia/truncate/','UsaidiziController@orodha_familia_truncate');
    Route::get('orodha_familia/tengeneza/','UsaidiziController@orodha_familia_tengeneza');
    Route::get('orodha_wanafamilia','UsaidiziController@orodha_wanafamilia_index');
    Route::post('orodha_wanafamilia/store','UsaidiziController@orodha_wanafamilia_store')->name('orodha_wanafamilia.orodha_wanafamilia_store');
    Route::get('orodha_wanafamilia/edit/{id}','UsaidiziController@orodha_wanafamilia_edit');
    Route::post('orodha_wanafamilia/update','UsaidiziController@orodha_wanafamilia_update')->name('orodha_wanafamilia.orodha_wanafamilia_update');
    Route::get('orodha_wanafamilia/destroy/{id}','UsaidiziController@orodha_wanafamilia_destroy');
    Route::get('orodha_wanafamilia/truncate/','UsaidiziController@orodha_wanafamilia_truncate');
    Route::get('orodha_wanafamilia/tengeneza/','UsaidiziController@orodha_wanafamilia_tengeneza');

    //the controller for handling kanda
    Route::resource('kanda','KandaController');
    Route::post('kanda/edit','KandaController@update')->name('kanda.update');
    Route::get('kanda/destroy/{id}','KandaController@destroy');
    Route::post('kanda/import','KandaController@kanda_import')->name('kanda.kanda_import');
    Route::get('kanda/{kanda}','KandaController@jumuiya_kanda_husika');

    //the controller for handling jumuiya
    Route::resource('jumuiya','JumuiyaController');
    Route::post('jumuiya/edit','JumuiyaController@update')->name('jumuiya.update');
    Route::post('jumuiya/import','JumuiyaController@jumuiya_import')->name('jumuiya.jumuiya_import');
    Route::get('jumuiya/destroy/{id}','JumuiyaController@destroy');
    Route::get('jumuiya/{jumuiya}','JumuiyaController@wanajumuiya_husika');

    //the controller for handling familia
    Route::resource('familia','FamiliaController');
    Route::post('familia/edit','FamiliaController@update')->name('familia.update');
    Route::get('familia/destroy/{id}','FamiliaController@destroy');
    Route::get('familia/wanafamilia/{id}','FamiliaController@wanafamilia_husika');
    Route::get('familia_zote','FamiliaController@familia_zote');
    Route::post('wanafamilia','FamiliaController@wanafamilia_store')->name('wanafamilia.store');
    Route::post('wanafamilia/edit','FamiliaController@wanafamilia_update')->name('wanafamilia.update');
    Route::get('wanafamilia_edit/{id}','FamiliaController@wanafamilia_edit');
    Route::get('wanafamilia','FamiliaController@wanafamilia');
    Route::get('mwanafamilia/{id}','FamiliaController@mwanafamilia_husika');
    Route::get('wanafamilia/destroy/{id}','FamiliaController@wanafamilia_destroy');
    Route::post('familia/import','FamiliaController@familia_import')->name('familia.familia_import');
    Route::post('wanafamilia/import','FamiliaController@wanafamilia_import')->name('wanafamilia.wanafamilia_import');
    Route::post('wanafamilia/marekebisho/import','FamiliaController@ImportFamilyMemberCorrections')->name('wanafamilia.marekebisho_import');
    Route::get('familia/jumuiya_husika/{jumuiya}','FamiliaController@familia_jumuiya_husika');
    Route::post('taarifa_mkupuo/kanda','FamiliaController@badilishaTaarifaMkupuo')->name('badilisha_taarifa_mkupuo.data');
    Route::get('taarifa_mkupuo','FamiliaController@badilishaTaarifaMkupuoIndex')->name('badilisha_taarifa_mkupuo');

    //the controller for handling kikundi
    Route::get('kikundi','KikundiController@kikundi_index');
    Route::post('kikundi','KikundiController@kikundi_store')->name('kikundi.kikundi_store');
    Route::get('kikundi_edit/{id}','KikundiController@kikundi_edit');
    Route::post('kikundi_update','KikundiController@kikundi_update')->name('kikundi_update.kikundi_update');
    Route::post('wanakikundi','KikundiController@wanakikundi_store')->name('wanakikundi.store');
    Route::get('kikundi_husika/{id}','KikundiController@kikundi_husika');
    Route::get('mwanakikundi_husika/destroy/{id}','KikundiController@mwanakikundi_destroy');
    Route::get('kikundi_destroy/destroy/{id}','KikundiController@kikundi_destroy');
    Route::get('kikundi_husika/edit/{id}','KikundiController@mwanakikundi_edit');
    Route::post('mwanakikundi_update','KikundiController@mwanakikundi_update')->name('mwanakikundi_update.update');

    //the controller for handling vyeo kanisa
    Route::resource('vyeo_kanisa','VyeoKanisaController');
    Route::post('vyeo_kanisa/edit','VyeoKanisaController@update')->name('vyeo_kanisa.update');
    Route::get('vyeo_kanisa/destroy/{id}','VyeoKanisaController@destroy');

    //the controller for handling aina za misa
    Route::resource('aina_za_misa','AinaZaMisaController');
    Route::post('aina_za_misa/edit','AinaZaMisaController@update')->name('aina_za_misa.update');
    Route::get('aina_za_misa/destroy/{id}','AinaZaMisaController@destroy');

    //handling the masakramenti part
    Route::get('masakramenti_jumla','MasakramentiController@masakramenti_kijumla');
    Route::get('masakramenti_kanda','MasakramentiController@masakramenti_kikanda');
    Route::get('masakramenti_kanda_husika/{id}','MasakramentiController@masakramenti_kanda_husika');
    Route::get('masakramenti_jumuiya','MasakramentiController@masakramenti_jumuiya');
    Route::get('masakramenti_jumuiya_husika/{id}','MasakramentiController@masakramenti_jumuiya_husika');

    //handling sadaka kuu controller
    Route::get('sadaka_kuu_takwimu','SadakaKuuController@sadaka_kuu_takwimu');
    Route::get('sadaka_kuu','SadakaKuuController@sadaka_kuu_index');
    Route::post('sadaka_kuu/store','SadakaKuuController@sadaka_kuu_store')->name('sadaka_kuu.store');
    Route::post('sadaka_kuu/update','SadakaKuuController@sadaka_kuu_update')->name('sadaka_kuu.update');
    Route::get('sadaka_kuu/edit/{id}','SadakaKuuController@sadaka_kuu_edit');
    Route::get('sadaka_kuu/delete/{id}','SadakaKuuController@sadaka_kuu_delete');
    Route::get('sadaka_kuu_mwezi/{mwezi}','SadakaKuuController@sadaka_kuu_mwezi');
    Route::get('sadaka_kuu_zaidi','SadakaKuuController@sadaka_kuu_zaidi');
    Route::get('sadaka_kuu_misa_husika/{misa}','SadakaKuuController@sadaka_kuu_misa_husika');

    //handling the zaka data
    Route::get('zaka_takwimu','ZakaController@zaka_takwimu');
    Route::get('zaka','ZakaController@zaka_index');
    Route::get('zaka_zote','ZakaController@zaka_jumuiya_zote');
    Route::get('zaka_jumuiya_husika/{id}','ZakaController@zaka_jumuiya_husika');
    Route::get('zaka_jumuiya_husika_mwezi/{jumuiya}/{mwezi}','ZakaController@zaka_jumuiya_husika_mwezi');
    Route::post('zaka','ZakaController@get_mwanajumuiya')->name('zaka.get_mwanajumuiya');
    Route::post('zaka/store','ZakaController@zaka_store')->name('zaka.store');
    Route::post('zaka/import','ZakaController@zaka_import')->name('zaka.zaka_import');
    Route::post('zaka/update','ZakaController@zaka_update')->name('zaka.update');
    Route::get('zaka/edit/{id}','ZakaController@zaka_edit');
    Route::get('zaka_mkupuo','ZakaController@zaka_mkupuo_index');
    Route::post('zaka_mkupuo/kanda','ZakaController@zaka_mkupuo_vuta_kanda')->name('zaka_mkupuo.vuta_kanda');
    Route::post('zaka_mkupuo/store','ZakaController@zaka_mkupuo_store')->name('zaka_mkupuo.store');
    Route::get('zaka_mwezi/{mwezi}','ZakaController@zaka_mwezi');
    Route::get('zaka/delete/{id}','ZakaController@zaka_delete');
    Route::get('zaka_risiti/{id}','ZakaController@zaka_risiti');
    Route::get('zaka_kwa_miezi_jumuiya/{jumuiya}/{mwaka}','ZakaController@zakaKwaMieziJumuiya')
        ->name('zaka_kwa_miezi_jumuiya');
    Route::get('zaka_kwa_miezi_wanajumuiya/{jumuiya}/{mwaka}','ZakaController@zakaKwaMieziWanajumuiya')
    ->name('zaka_kwa_miezi_wanajumuiya');

    //the controller for handling vyeo kanisa
    Route::resource('users','UserController');
    Route::resource('ratiba-ya-misa','RatibaYaMisaController');
    Route::resource('wahudumu','MuhudumuController');
    Route::get('viongozi_zaidi','UserController@viongozi_zaidi');
    Route::get('viongozi_husika/{id}','UserController@viongozi_husika');
    Route::get('viongozi_kamati/{aina_ya_kamati}','UserController@viongozi_kamati');
    Route::get('viongozi_kamati_parokia','UserController@viongozi_kamati_parokia');
    Route::get('viongozi_kamati_jumuiya','UserController@viongozi_kamati_jumuiya');
    Route::post('users/edit','UserController@update')->name('users.update');
    Route::get('users_profile','UserController@user_profile');
    Route::get('viongozi/destroy/{id}','UserController@destroy');
    Route::post('users_data/getData','UserController@getMwanajumuiyaData')->name('users.getMwanajumuiyaData');
    Route::post('user_profile/update','UserController@profile_update')->name("users.profile_update");
    Route::post('user_profile/password','UserController@password_update')->name("users.password_update");

    //the controller for handling aina za michango
    Route::resource('aina_za_michango','AinaZaMichangoController');
    Route::post('aina_za_michango/edit','AinaZaMichangoController@update')->name('aina_za_michango.update');
    Route::get('aina_za_michango/destroy/{id}','AinaZaMichangoController@destroy');
    Route::get('waliotoa_wasiotoa/','MichangoController@waliotoa_wasiotoa')->name('waliotoa_wasiotoa');

    //the controller for handling aina za huduma
    Route::resource('aina_za_huduma','HudumaAinaController');
    Route::post('aina_za_huduma/edit','HudumaAinaController@update')->name('aina_za_huduma.update');
    Route::get('aina_za_huduma/destroy/{id}','HudumaAinaController@destroy');

    //the controller for handling watoa huduma
    Route::resource('watoa_huduma','WatoaHudumaController');
    Route::post('watoa_huduma/edit','WatoaHudumaController@update')->name('watoa_huduma.update');
    Route::get('watoa_huduma/destroy/{id}','WatoaHudumaController@destroy');

    //the controller for handling aina za matumizi
    Route::resource('aina_za_matumizi','AinaMatumiziController');
    Route::post('aina_za_matumizi/edit','AinaMatumiziController@update')->name('aina_za_matumizi.update');
    Route::get('aina_za_matumizi/destroy/{id}','AinaMatumiziController@destroy');

    //the controller for handling ankra za madeni
    Route::resource('aina_za_sadaka','AinaZaSadakaController');
    Route::post('aina_za_sadaka/edit','AinaZaSadakaController@update')->name('aina_za_sadaka.update');
    Route::get('aina_za_sadaka/destroy/{id}','AinaZaSadakaController@destroy');
    Route::resource('ankra_za_madeni','AnkraMadeniController');
    Route::post('ankra_za_madeni/edit','AnkraMadeniController@update')->name('ankra_za_madeni.update');
    Route::get('ankra_za_madeni/destroy/{id}','AnkraMadeniController@destroy');

    //the controller to handle the michango
    Route::get('ahadi_michango/{id}','AhadiAinayamichangoController@aina_mchango')->name('ahadi_aina_za_mchango');
    Route::get('weka_ahadi_mwanajumuiya/{mchango_id}','AhadiAinayamichangoController@weka_ahadi_mwanajumuiya')->name('weka_ahadi_mwanajumuiya');
    Route::post('weka_ahadi_mwanajumuiya/wanajumuiya','AhadiAinayamichangoController@ahadi_mkupuo_vuta_wanajumuiya')->name('ahadi_mkupuo_vuta_wanajumuiya');
    Route::get('ahadi_michango_jumuiya','AhadiAinayamichangoController@ahadi_michango_gawia_jumuiya')->name('ahadi_michango_gawia_jumuiya');
    Route::get('ahadi_michango_wanafamilia','AhadiAinayamichangoController@ahadi_michango_gawia_wanafamilia')->name('ahadi_michango_gawia_wanafamilia');
    Route::get('ahadi_michango_jumuiya/jumla/{mchango_id}','AhadiAinayamichangoController@ahadi_michango_jumuiya_jumla')->name('ahadi_michango_jumuiya_jumla');
    Route::get('ahadi_michango_jumuiya/mwanajumiya/{mchango_id}/{jumuiya_id}','AhadiAinayamichangoController@ahadi_michango_jumuiya_mwanajumuiya')->name('ahadi_michango_jumuiya_mwanajumuiya');
    Route::get('michango_takwimu','MichangoController@michango_takwimu');
    Route::get('michango_taslimu_kijumuiya','MichangoController@michango_taslimu_kijumuiya');
    Route::get('ajax_check_michango_ahadi','MichangoController@ajax_check_michango_ahadi')->name('ajax_check_michango_ahadi');
    Route::get('michango_taslimu_jumuiya_husika/{jumuiya}','MichangoController@michango_taslimu_jumuiya_husika');
    Route::get('michango_taslimu_jumuiya_mchango_husika/{mchango}/{jumuiya}','MichangoController@michango_taslimu_jumuiya_mchango_husika');
    Route::get('michango_taslimu','MichangoController@michango_taslimu');
    Route::post('michango_taslimu/store','MichangoController@michango_taslimu_store')->name('michango_taslimu.store');
    Route::post('michango_taslimu/update','MichangoController@michango_taslimu_update')->name('michango_taslimu.update');
    Route::get('michango_taslimu/edit/{id}','MichangoController@michango_taslimu_edit');
    Route::get('michango_taslimu/destroy/{id}','MichangoController@michango_taslimu_destroy');
    Route::get('michango_taslimu_miezi','MichangoController@michango_taslimu_miezi');
    Route::get('michango_taslimu_mwezi/{id}','MichangoController@michango_taslimu_mwezi_husika');
    Route::get('michango_taslimu_jumuiya_mwezi_husika/{mwezi}/{jumuiya}','MichangoController@michango_taslimu_jumuiya_mwezi_husika');
    Route::get('michango_taslimu_jumuiya_mwezi_mchango_husika/{mwezi}/{jumuiya}/{mchango}','MichangoController@michango_taslimu_jumuiya_mwezi_mchango_husika');
    Route::get('michango_taslimu_mkupuo','MichangoController@michango_taslimu_mkupuo_index');
    Route::post('michango_taslimu_mkupuo/jumuiya','MichangoController@michango_taslimu_mkupuo_vuta_jumuiya')->name('michango_taslimu_mkupuo.vuta_jumuiya');
    Route::post('michango_taslimu_mkupuo/store','MichangoController@michango_taslimu_mkupuo_store')->name('michango_taslimu_mkupuo.store');
    Route::get('michango_benki','MichangoController@michango_benki');
    Route::get('michango_benki_kijumuiya','MichangoController@michango_benki_kijumuiya');
    Route::get('michango_benki_jumuiya_husika/{jumuiya}','MichangoController@michango_benki_jumuiya_husika');
    Route::post('michango_benki/store','MichangoController@michango_benki_store')->name('michango_benki.store');
    Route::post('michango_benki/update','MichangoController@michango_benki_update')->name('michango_benki.update');
    Route::get('michango_benki/edit/{id}','MichangoController@michango_benki_edit');
    Route::get('michango_benki/destroy/{id}','MichangoController@michango_benki_destroy');
    Route::get('michango_benki_miezi','MichangoController@michango_benki_miezi');
    Route::get('michango_benki_mwezi/{id}','MichangoController@michango_benki_mwezi_husika');
    Route::get('michango_benki_jumuiya_mchango_husika/{mchango}/{jumuiya}','MichangoController@michango_benki_jumuiya_mchango_husika');
    Route::get('michango_benki_jumuiya_mwezi_husika/{mwezi}/{jumuiya}','MichangoController@michango_benki_jumuiya_mwezi_husika');
    Route::get('michango_benki_jumuiya_mwezi_mchango_husika/{mwezi}/{jumuiya}/{mchango}','MichangoController@michango_benki_jumuiya_mwezi_mchango_husika');
    Route::get('michango_taslimu_risiti/{id}','MichangoController@michango_taslimu_risiti');
    Route::get('michango_benki_risiti/{id}','MichangoController@michango_benki_risiti');

    //the controller for handling aina za michango
    Route::resource('akaunti_za_benki','AkauntiZaBenkiController');
    Route::post('akaunti_za_benki/edit','AkauntiZaBenkiController@update')->name('akaunti_za_benki.update');
    Route::get('akaunti_za_benki/destroy/{id}','AkauntiZaBenkiController@destroy');

    //the controller to handle the matumizi taslimus
    Route::get('matumizi_taslimu','MatumiziTaslimuController@matumizi_taslimu_index');
    Route::post('matumizi_taslimu/store','MatumiziTaslimuController@matumizi_taslimu_store')->name('matumizi_taslimu.store');
    Route::post('matumizi_taslimu/edit','MatumiziTaslimuController@matumizi_taslimu_update')->name('matumizi_taslimu.update');
    Route::get('matumizi_taslimu/destroy/{id}','MatumiziTaslimuController@matumizi_taslimu_destroy');
    Route::get('matumizi_taslimu/edit/{id}','MatumiziTaslimuController@edit');
    Route::get('matumizi_taslimu_zaidi','MatumiziTaslimuController@matumizi_taslimu_zaidi');
    Route::get('matumizi_taslimu_mwezi/{id}','MatumiziTaslimuController@matumizi_taslimu_mwezi');


    //the controller to handle the matumizi benkis
    Route::get('matumizi_benki','MatumiziBenkiController@matumizi_benki_index');
    Route::post('matumizi_benki/store','MatumiziBenkiController@matumizi_benki_store')->name('matumizi_benki.store');
    Route::post('matumizi_benki/edit','MatumiziBenkiController@matumizi_benki_update')->name('matumizi_benki.update');
    Route::get('matumizi_benki/destroy/{id}','MatumiziBenkiController@matumizi_benki_destroy');
    Route::get('matumizi_benki/edit/{id}','MatumiziBenkiController@edit');
    Route::get('matumizi_benki_zaidi','MatumiziBenkiController@matumizi_benki_zaidi');
    Route::get('matumizi_benki_mwezi/{id}','MatumiziBenkiController@matumizi_benki_mwezi');

    //the controller to handle the mapato matumizi
    Route::get('mapato_matumizi_takwimu','MapatoMatumiziController@mapato_matumizi_takwimu');
    Route::get('mapato_zaidi','MapatoMatumiziController@mapato_zaidi');
    Route::get('matumizi_zaidi','MapatoMatumiziController@matumizi_zaidi');

    //function to handle the matukio part of wavuti
    Route::resource('matukio','MatukioController');
    Route::post('matukio/edit','MatukioController@update')->name('matukio.update');
    Route::get('matukio/destroy/{id}','MatukioController@destroy');

    //function to handle the historia part of wavuti
    Route::resource('historia','HistoriaController');
    Route::post('historia/edit','HistoriaController@update')->name('historia.update');
    Route::get('historia/destroy/{id}','HistoriaController@destroy');

    //function to handle the matangazo part of wavuti
    Route::resource('matangazo','MatangazoController');
    Route::post('matangazo/edit','MatangazoController@update')->name('matangazo.update');
    Route::get('matangazo/destroy/{id}','MatangazoController@destroy');

    //function to handle the aina za mali
    Route::resource('aina_za_mali','AinaZaMaliController');
    Route::post('aina_za_mali/edit','AinaZaMaliController@update')->name('aina_za_mali.update');
    Route::get('aina_za_mali/destroy/{id}','AinaZaMaliController@destroy');

    //function to handle the mali za kanisa
    Route::resource('mali_za_kanisa','MaliZaKanisaController');
    Route::post('mali_za_kanisa/edit','MaliZaKanisaController@update')->name('mali_za_kanisa.update');
    Route::get('mali_za_kanisa/destroy/{id}','MaliZaKanisaController@destroy');

    //function to handle the sadaka jumuiya
    Route::get('sadaka_jumuiya','SadakaJumuiyaController@sadaka_jumuiya_index');
    Route::post('sadaka_jumuiya/store','SadakaJumuiyaController@sadaka_jumuiya_store')->name('sadaka_jumuiya.store');
    Route::post('sadaka_jumuiya/update','SadakaJumuiyaController@sadaka_jumuiya_update')->name('sadaka_jumuiya.update');
    Route::get('sadaka_jumuiya/edit/{id}','SadakaJumuiyaController@sadaka_jumuiya_edit');
    Route::get('sadaka_jumuiya/destroy/{id}','SadakaJumuiyaController@sadaka_jumuiya_destroy');
    Route::get('sadaka_jumuiya_zaidi','SadakaJumuiyaController@sadaka_jumuiya_zaidi');
    Route::get('sadaka_jumuiya_mwezi/{id}','SadakaJumuiyaController@sadaka_jumuiya_mwezi');
    Route::get('sadaka_jumuiya_husika_mwezi/{id}/{jumuiya}','SadakaJumuiyaController@sadaka_jumuiya_husika_mwezi');
    Route::get('sadaka_jumuiya_husika/{jumuiya}','SadakaJumuiyaController@sadaka_jumuiya_husika');


    //function to handle the sms
    Route::get('ukurasa_meseji','MessageController@message_index');
    Route::post('ukurasa_meseji/post','MessageController@send_message')->name('ukurasa_meseji.send_message');
    Route::get('ukurasa_meseji/invoice','MessageController@messageInvoice')->name('messages.invoice');

    //function to handle the sms for ukumbusho na shukrani
    Route::get('meseji_shukrani_ukumbusho','MessageShukraniUkumbushoController@shukrani_ukumbusho_index');
    Route::get('ajax_get_number_wasiotoa','MessageShukraniUkumbushoController@ajax_get_number_wasiotoa')->name('ajax_get_number_wasiotoa');
    Route::get('shukrani_ukumbusho_sample','MessageShukraniUkumbushoController@shukrani_ukumbusho_sample');
    Route::get('shukrani_ukumbusho_sample/{id}','MessageShukraniUkumbushoController@shukrani_ukumbusho_edit');
    Route::post('shukrani_ukumbusho_sample/edit','MessageShukraniUkumbushoController@shukrani_ukumbusho_update')->name('shukrani_ukumbusho_update');
    Route::post('shukrani_ukumbusho_sample','MessageShukraniUkumbushoController@shukrani_ukumbusho_save')->name('shukrani_ukumbusho_save');
    Route::get('shukrani_ukumbusho_sample/destroy/{id}','MessageShukraniUkumbushoController@shukrani_ukumbusho_destroy');
    Route::post('shukrani_ukumbusho_sample/get','MessageShukraniUkumbushoController@shukrani_ukumbusho_message')->name('shukrani_ukumbusho.get_message');
    Route::post('shukrani_ukumbusho_sample/post','MessageShukraniUkumbushoController@shukrani_ukumbusho_send')->name('shukrani_ukumbusho.post_message');

    //controller to handle the reports for family report
    Route::get('familia_orodha_pdf','FamiliaReportController@familia_orodha_pdf');
    Route::get('familia_orodha_excel','FamiliaReportController@familia_orodha_excel');
    Route::get('familia_orodha_kijumuiya_pdf','FamiliaReportController@familia_orodha_kijumuiya_pdf');
    Route::get('familia_orodha_jumuiya_husika_pdf/{id}','FamiliaReportController@familia_orodha_jumuiya_husika_pdf');
    Route::get('familia_orodha_jumuiya_husika_excel/{id}','FamiliaReportController@familia_orodha_jumuiya_husika_excel');
    Route::get('familia_orodha_kijumuiya_excel','FamiliaReportController@familia_orodha_kijumuiya_excel');
    Route::get('wanafamilia_orodha_pdf','FamiliaReportController@wanafamilia_orodha_pdf');
    Route::get('wanafamilia_orodha_excel','FamiliaReportController@wanafamilia_orodha_excel');
    Route::get('wanafamilia_husika_pdf/{id}','FamiliaReportController@wanafamilia_husika_pdf');
    Route::get('wanafamilia_husika_excel/{id}','FamiliaReportController@wanafamilia_husika_excel');

    //controller for handling the vikundi report
    Route::get('vyama_kitume_pdf','KikundiReportController@vyama_vya_kitume_pdf');
    Route::get('vyama_kitume_excel','KikundiReportController@vyama_vya_kitume_excel');
    Route::get('chama_kitume_pdf/{id}','KikundiReportController@chama_cha_kitume_pdf');
    Route::get('chama_kitume_excel/{id}','KikundiReportController@chama_cha_kitume_excel');

    //controller to handle the jumuiya report
    Route::get('jumuiya_orodha_pdf','JumuiyaReportController@jumuiya_orodha_pdf');
    Route::get('jumuiya_orodha_excel','JumuiyaReportController@jumuiya_orodha_excel');
    Route::get('jumuiya_wanajumuiya_husika_pdf/{jumuiya}','JumuiyaReportController@jumuiya_wanajumuiya_husika_pdf');
    Route::get('jumuiya_wanajumuiya_husika_excel/{jumuiya}','JumuiyaReportController@jumuiya_wanajumuiya_husika_excel');


    //controller to handle the kanda report
    Route::get('kanda_orodha_pdf','KandaReportController@kanda_orodha_pdf');
    Route::get('jumuiya_kanda_husika_pdf/{kanda}','KandaReportController@jumuiya_kanda_husika_pdf');
    Route::get('jumuiya_kanda_husika_excel/{kanda}','KandaReportController@jumuiya_kanda_husika_excel');
    Route::get('kanda_orodha_excel','KandaReportController@kanda_orodha_excel');

    //controller to handle masakramenti reports
    Route::get('masakramenti_jumla_pdf','MasakramentiReportController@masakramenti_kijumla_pdf');
    Route::get('masakramenti_kikanda_pdf','MasakramentiReportController@masakramenti_kikanda_pdf');
    Route::get('masakramenti_kanda_husika_pdf/{id}','MasakramentiReportController@masakramenti_kanda_husika_pdf');
    Route::get('masakramenti_jumuiya_pdf','MasakramentiReportController@masakramenti_jumuiya_pdf');
    Route::get('masakramenti_jumuiya_husika_pdf/{id}','MasakramentiReportController@masakramenti_jumuiya_husika_pdf');
    Route::get('masakramenti_jumla_excel','MasakramentiReportController@masakramenti_kijumla_excel');
    Route::get('masakramenti_kikanda_excel','MasakramentiReportController@masakramenti_kikanda_excel');
    Route::get('masakramenti_kanda_husika_excel/{id}','MasakramentiReportController@masakramenti_kanda_husika_excel');
    Route::get('masakramenti_jumuiya_excel','MasakramentiReportController@masakramenti_jumuiya_excel');
    Route::get('masakramenti_jumuiya_husika_excel/{id}','MasakramentiReportController@masakramenti_jumuiya_husika_excel');

    //function to handle mengineyo
    Route::get('aina_za_mali_pdf','MengineyoReportController@aina_za_mali_pdf');
    Route::get('mali_za_kanisa_pdf','MengineyoReportController@mali_za_kanisa_pdf');
    Route::get('aina_za_huduma_pdf','MengineyoReportController@aina_za_huduma_pdf');
    Route::get('watoahuduma_pdf','MengineyoReportController@watoahuduma_pdf');
    Route::get('akaunti_za_benki_pdf','MengineyoReportController@akaunti_za_benki_pdf');
    Route::get('aina_za_misa_pdf','MengineyoReportController@aina_za_misa_pdf');
    Route::get('aina_za_mali_excel','MengineyoReportController@aina_za_mali_excel');
    Route::get('mali_za_kanisa_excel','MengineyoReportController@mali_za_kanisa_excel');
    Route::get('aina_za_huduma_excel','MengineyoReportController@aina_za_huduma_excel');
    Route::get('watoahuduma_excel','MengineyoReportController@watoahuduma_excel');
    Route::get('akaunti_za_benki_pdf','MengineyoReportController@akaunti_za_benki_pdf');
    Route::get('akaunti_za_benki_excel','MengineyoReportController@akaunti_za_benki_excel');
    Route::get('aina_za_misa_excel','MengineyoReportController@aina_za_misa_excel');
    Route::get('aina_za_sadaka_excel','MengineyoReportController@aina_za_sadaka_excel');
    Route::get('aina_za_sadaka_pdf','MengineyoReportController@aina_za_sadaka_pdf');

    //function to handle the viongozi
    Route::get('orodha_idadi_viongozi_pdf','UserReportController@orodha_idadi_viongozi_pdf');
    Route::get('orodha_idadi_viongozi_excel','UserReportController@orodha_idadi_viongozi_excel');
    Route::get('orodha_viongozi_pdf','UserReportController@orodha_viongozi_pdf');
    Route::get('orodha_viongozi_excel','UserReportController@orodha_viongozi_excel');
    Route::get('orodha_viongozi_husika_pdf/{id}','UserReportController@orodha_viongozi_husika_pdf');
    Route::get('orodha_viongozi_husika_excel/{id}','UserReportController@orodha_viongozi_husika_excel');

    //function to handle all the sadakas
    Route::get('sadaka_takwimu_pdf','SadakaReportController@sadaka_takwimu_pdf');
    Route::get('sadaka_kuu_pdf','SadakaReportController@sadaka_kuu_pdf');
    Route::get('sadaka_kuu_misa_husika_pdf/{misa}','SadakaReportController@sadaka_kuu_misa_husika_pdf');
    Route::get('sadaka_jumuiya_pdf','SadakaReportController@sadaka_jumuiya_pdf');
    Route::get('sadaka_jumuiya_zaidi_pdf','SadakaReportController@sadaka_jumuiya_zaidi_pdf');
    Route::get('sadaka_jumuiya_mwezi_pdf/{id}','SadakaReportController@sadaka_jumuiya_mwezi_pdf');
    Route::get('sadaka_jumuiya_husika_pdf/{jumuiya}','SadakaReportController@sadaka_jumuiya_husika_pdf');
    Route::get('sadaka_kuu_zaidi_pdf','SadakaReportController@sadaka_kuu_zaidi_pdf');
    Route::get('sadaka_kuu_mwezi_pdf/{id}','SadakaReportController@sadaka_kuu_mwezi_pdf');
    Route::get('sadaka_takwimu_excel','SadakaReportController@sadaka_takwimu_excel');
    Route::get('sadaka_jumuiya_excel','SadakaReportController@sadaka_jumuiya_excel');
    Route::get('sadaka_jumuiya_husika_excel/{jumuiya}','SadakaReportController@sadaka_jumuiya_husika_excel');
    Route::get('sadaka_kuu_excel','SadakaReportController@sadaka_kuu_excel');
    Route::get('sadaka_kuu_misa_husika_excel/{misa}','SadakaReportController@sadaka_kuu_misa_husika_excel');
    Route::get('sadaka_kuu_zaidi_excel','SadakaReportController@sadaka_kuu_zaidi_excel');
    Route::get('sadaka_kuu_mwezi_excel/{id}','SadakaReportController@sadaka_kuu_mwezi_excel');
    Route::get('sadaka_jumuiya_zaidi_excel','SadakaReportController@sadaka_jumuiya_zaidi_excel');
    Route::get('sadaka_jumuiya_mwezi_excel/{id}','SadakaReportController@sadaka_jumuiya_mwezi_excel');

    //function to handle the reports for zaka
    Route::get('zaka_takwimu_pdf','ZakaReportController@zaka_takwimu_pdf');
    Route::get('zaka_jumuiya_pdf','ZakaReportController@zaka_jumuiya_pdf');
    Route::get('zaka_jumuiya_husika_pdf/{id}','ZakaReportController@zaka_jumuiya_husika_pdf');
    Route::get('zaka_mwezi_husika_pdf/{mwezi}','ZakaReportController@zaka_mwezi_husika_pdf');
    Route::get('zaka_jumuiya_mwezi_husika_pdf/{jumuiya}/{mwezi}','ZakaReportController@zaka_jumuiya_mwezi_husika_pdf');
    Route::get('zaka_takwimu_excel','ZakaReportController@zaka_takwimu_excel');
    Route::get('zaka_jumuiya_excel','ZakaReportController@zaka_jumuiya_excel');
    Route::get('zaka_jumuiya_husika_excel/{id}','ZakaReportController@zaka_jumuiya_husika_excel');
    Route::get('zaka_mwezi_husika_excel/{mwezi}','ZakaReportController@zaka_mwezi_husika_excel');
    Route::get('zaka_jumuiya_mwezi_husika_excel/{jumuiya}/{mwezi}','ZakaReportController@zaka_jumuiya_mwezi_husika_excel');
    Route::get('zaka_kwa_miezi_wanajumuiya_pdf/{jumuiya}/{mwaka}','ZakaReportController@zakaKwaMieziWanajumuiyaPDF')
    ->name('zaka_kwa_miezi_wanajumuiya_pdf');
    Route::get('zaka_kwa_miezi_wanajumuiya_excel/{jumuiya}/{mwaka}','ZakaReportController@zakaKwaMieziWanajumuiyaExcel')
    ->name('zaka_kwa_miezi_wanajumuiya_excel');

    //function to handle michango report
    Route::get('michango_takwimu_pdf','MichangoReportController@michango_takwimu_pdf');
    Route::get('michango_taslimu_kijumuiya_pdf','MichangoReportController@michango_taslimu_kijumuiya_pdf');
    Route::get('michango_taslimu_jumuiya_husika_pdf/{jumuiya}','MichangoReportController@michango_taslimu_jumuiya_husika_pdf');
    Route::get('michango_taslimu_jumuiya_mchango_husika_pdf/{mchango}/{jumuiya}','MichangoReportController@michango_taslimu_jumuiya_mchango_husika_pdf');
    Route::get('michango_taslimu_pdf','MichangoReportController@michango_taslimu_pdf');
    Route::get('michango_taslimu_miezi_pdf','MichangoReportController@michango_taslimu_miezi_pdf');
    Route::get('michango_taslimu_mwezi_husika_pdf/{id}','MichangoReportController@michango_taslimu_mwezi_husika_pdf');
    Route::get('michango_taslimu_jumuiya_mwezi_husika_pdf/{mwezi}/{jumuiya}','MichangoReportController@michango_taslimu_jumuiya_mwezi_husika_pdf');
    Route::get('michango_taslimu_jumuiya_mwezi_mchango_husika_pdf/{mchango}/{jumuiya}/{mwezi}','MichangoReportController@michango_taslimu_jumuiya_mwezi_mchango_husika_pdf');
    Route::get('michango_benki_kijumuiya_pdf','MichangoReportController@michango_benki_kijumuiya_pdf');
    Route::get('michango_benki_jumuiya_husika_pdf/{jumuiya}','MichangoReportController@michango_benki_jumuiya_husika_pdf');
    Route::get('michango_benki_jumuiya_mchango_husika_pdf/{mchango}/{jumuiya}','MichangoReportController@michango_benki_jumuiya_mchango_husika_pdf');
    Route::get('michango_benki_miezi_pdf','MichangoReportController@michango_benki_miezi_pdf');
    Route::get('michango_benki_pdf','MichangoReportController@michango_benki_pdf');
    Route::get('michango_benki_mwezi_husika_pdf/{id}','MichangoReportController@michango_benki_mwezi_husika_pdf');
    Route::get('michango_benki_jumuiya_mwezi_husika_pdf/{mwezi}/{jumuiya}','MichangoReportController@michango_benki_jumuiya_mwezi_husika_pdf');
    Route::get('michango_benki_jumuiya_mwezi_mchango_husika_pdf/{mwezi}/{jumuiya}/{mchango}','MichangoReportController@michango_benki_jumuiya_mwezi_mchango_husika_pdf');
    Route::get('michango_takwimu_excel','MichangoReportController@michango_takwimu_excel');
    Route::get('michango_taslimu_kijumuiya_excel','MichangoReportController@michango_taslimu_kijumuiya_excel');
    Route::get('michango_taslimu_jumuiya_husika_excel/{jumuiya}','MichangoReportController@michango_taslimu_jumuiya_husika_excel');
    Route::get('michango_taslimu_jumuiya_mchango_husika_excel/{mchango}/{jumuiya}','MichangoReportController@michango_taslimu_jumuiya_mchango_husika_excel');
    Route::get('michango_taslimu_excel','MichangoReportController@michango_taslimu_excel');
    Route::get('michango_taslimu_miezi_excel','MichangoReportController@michango_taslimu_miezi_excel');
    Route::get('michango_taslimu_mwezi_husika_excel/{id}','MichangoReportController@michango_taslimu_mwezi_husika_excel');
    Route::get('michango_taslimu_jumuiya_mwezi_husika_excel/{mwezi}/{jumuiya}','MichangoReportController@michango_taslimu_jumuiya_mwezi_husika_excel');
    Route::get('michango_taslimu_jumuiya_mwezi_mchango_husika_excel/{mchango}/{jumuiya}/{mwezi}','MichangoReportController@michango_taslimu_jumuiya_mwezi_mchango_husika_excel');
    Route::get('michango_benki_kijumuiya_excel','MichangoReportController@michango_benki_kijumuiya_excel');
    Route::get('michango_benki_jumuiya_husika_excel/{jumuiya}','MichangoReportController@michango_benki_jumuiya_husika_excel');
    Route::get('michango_benki_jumuiya_mchango_husika_excel/{mchango}/{jumuiya}','MichangoReportController@michango_benki_jumuiya_mchango_husika_excel');
    Route::get('michango_benki_miezi_excel','MichangoReportController@michango_benki_miezi_excel');
    Route::get('michango_benki_excel','MichangoReportController@michango_benki_excel');
    Route::get('michango_benki_mwezi_husika_excel/{id}','MichangoReportController@michango_benki_mwezi_husika_excel');
    Route::get('michango_benki_jumuiya_mwezi_husika_excel/{mwezi}/{jumuiya}','MichangoReportController@michango_benki_jumuiya_mwezi_husika_excel');
    Route::get('michango_benki_jumuiya_mwezi_mchango_husika_excel/{mwezi}/{jumuiya}/{mchango}','MichangoReportController@michango_benki_jumuiya_mwezi_mchango_husika_excel');

    //function to handle the mapato matumizi report
    Route::get('mapato_matumizi_takwimu_pdf','MapatoMatumiziReportController@mapato_matumizi_takwimu_pdf');
    Route::get('mapato_zaidi_pdf','MapatoMatumiziReportController@mapato_zaidi_pdf');
    Route::get('mapato_matumizi_takwimu_excel','MapatoMatumiziReportController@mapato_matumizi_takwimu_excel');
    Route::get('mapato_zaidi_excel','MapatoMatumiziReportController@mapato_zaidi_excel');

    //function to handle the makundi ya umri
    Route::resource('makundi_rika','MakundiRikaController');
    Route::post('makundi_rika/edit','MakundiRikaController@update')->name('makundi_rika.update');
    Route::get('makundi_rika/destroy/{id}','MakundiRikaController@destroy');

    //function to handle main report for masakramenti
    Route::get('masakramenti_ripoti','MainMasakramentiReport@masakramenti_ripoti');
    Route::post('masakramenti_ripoti_generate','MainMasakramentiReport@masakramenti_ripoti_generate')->name('masakramenti_ripoti_generate');

    //function to handle main report for fedha
    Route::get('fedha_ripoti','MainFedhaReportController@fedha_ripoti');
    Route::post('fedha_ripoti_generate','MainFedhaReportController@fedha_ripoti_generate')->name('fedha_ripoti_generate');
   
    Route::get('fedha_ripoti_generate/{print_type}/{kuanzia}/{ukomo}','MainFedhaReportController@fedha_ripoti_generate_print')->name('fedha_ripoti_generate_print');


    Route::get('mapato_ya_kawaida/{aina_ya_mapato}/{kuanzia}/{ukomo}','SubFedhaReportController@mapato_ya_kawaida')->name('mapato_ya_kawaida');
    Route::get('mapato_ya_kawaida/{aina_ya_mapato}/{kuanzia}/{ukomo}/jumuiya','SubFedhaReportController@mapato_ya_kawaida_jumuiya')->name('mapato_ya_kawaida_jumuiya');

    Route::get('mapato_ya_kawaida/{aina_ya_mapato}/{kuanzia}/{ukomo}/{jumuiya}','SubFedhaReportController@mapato_ya_kawaida_zaka_wanajumuiya')->name('mapato_ya_kawaida_wanajumuiya');

    Route::get('mapato_ya_kawaida_print/{type}/{aina_ya_mapato}/{kuanzia}/{ukomo}','SubFedhaReportController@mapato_ya_kawaida_print')->name('mapato_ya_kawaida_print');
    //function to handle bajeti makisio
    Route::get('mapato_ya_maendeleo/{aina_ya_mchango}/{print_type}/{kuanzia}/{ukomo}','SubFedhaReportController@mapato_ya_maendeleo')->name('mapato_ya_maendeleo');
    Route::get('matumizi/{kundi}/{aina_ya_matumizi}/{print_type}/{kuanzia}/{ukomo}','SubFedhaReportController@matumizi')->name('matumizi');
    Route::get('salio/{print_type}/{kuanzia}/{ukomo}','SubFedhaReportController@salio')->name('salio');
    Route::resource('bajeti_makisio','BajetiMakisioController');

    //function to handle the bajeti makisio mapato
    Route::resource('bajeti_makisio_mapato','BajetiMakisioMapatoController');
    Route::post('bajeti_makisio_mapato/edit','BajetiMakisioMapatoController@update')->name('bajeti_makisio_mapato.update');
    Route::get('bajeti_makisio_mapato/destroy/{id}','BajetiMakisioMapatoController@destroy');

    //function to handle the bajeti makisio matumizi
    Route::resource('bajeti_makisio_matumizi','BajetiMakisioMatumiziController');
    Route::post('bajeti_makisio_matumizi/edit','BajetiMakisioMatumiziController@update')->name('bajeti_makisio_matumizi.update');
    Route::get('bajeti_makisio_matumizi/destroy/{id}','BajetiMakisioMatumiziController@destroy');

    //function to handle the stakabadhi
    Route::get('stakabadhi','StakabadhiController@stakabadhi_index');
    Route::post('stakabadhi_mchango_mkupuo','StakabadhiController@stakabadhi_mchango_mkupuo')->name('stakabadhi_mchango_mkupuo.post');
    Route::post('stakabadhi_zaka_mkupuo','StakabadhiController@stakabadhi_zaka_mkupuo')->name('stakabadhi_zaka_mkupuo.post');
    Route::post('stakabadhi_zaka_kawaida','StakabadhiController@stakabadhi_zaka_kawaida')->name('stakabadhi_zaka_kawaida.post');
    Route::post('stakabadhi_mchango_kawaida','StakabadhiController@stakabadhi_mchango_kawaida')->name('stakabadhi_mchango_kawaida.post');

    //function to handle denominations
    Route::resource('denomination','DenominationController');

    Route::get('download_correction_template/index', function () {

        $file= public_path(). "/storage/templates/template_ya_marekebisho_ya_taarifa.xlsx";

        $headers = ['Content-Type: application/pdf'];
        return Response::download($file, 'template_ya_marekebisho_ya_taarifa.xlsx', $headers);
    })->name('download_correction_template.index');

    Route::get('mafundisho_enrollments/index/{year}/{type}','MafundishoEnrollmentController@index')
    ->name('mafundisho_enrollments.index');

    Route::get('mafundisho_enrollments/index/{type}','MafundishoEnrollmentController@takwimu')
        ->name('mafundisho_enrollments.takwimu');

    Route::get('mafundisho_enrollments/download_excel/{year}/{type}','MafundishoEnrollmentController@downloadExcel')
        ->name('mafundisho_enrollments.download_excel');

    Route::get('mafundisho_enrollments/download_pdf/{year}/{type}','MafundishoEnrollmentController@downloadPDF')
        ->name('mafundisho_enrollments.download_pdf');

    Route::post('mafundisho_enrollments/store','MafundishoEnrollmentController@store')
        ->name('mafundisho_enrollments.store');

    Route::post('mafundisho_enrollments/import','MafundishoEnrollmentController@import')
        ->name('mafundisho_enrollments.import');

    Route::put('mafundisho_enrollments/{mafundisho_enrollment}/update','MafundishoEnrollmentController@update')
        ->name('mafundisho_enrollments.update');

    Route::delete('mafundisho_enrollments/{mafundisho_enrollment}/delete','MafundishoEnrollmentController@destroy')
        ->name('mafundisho_enrollments.delete');

    Route::get('sadaka_za_misas/index/','SadakaZaMisaController@index')
        ->name('sadaka_za_misas.index');
    Route::get('sadaka_za_misa_takwimu','SadakaZaMisaController@takwimu')
        ->name('sadaka_za_misas.takwimu');

    Route::get('sadaka_za_misas/download_excel','SadakaZaMisaController@downloadExcel')
        ->name('sadaka_za_misas.download_excel');

    Route::get('sadaka_za_misas/download_pdf','SadakaZaMisaController@downloadPDF')
        ->name('sadaka_za_misas.download_pdf');

    Route::post('sadaka_za_misas/store','SadakaZaMisaController@store')
        ->name('sadaka_za_misas.store');

    Route::put('sadaka_za_misas/{sadaka_za_misa}/update','SadakaZaMisaController@update')
        ->name('sadaka_za_misas.update');

    Route::delete('sadaka_za_misas/{sadaka_za_misa}/delete','SadakaZaMisaController@destroy')
        ->name('sadaka_za_misas.delete');

    Route::get('mafundisho_enrollments_template/download', function () {

        $file= public_path(). "/storage/templates/template_ya_wanafunzi_mafundisho.xlsx";

        $headers = ['Content-Type: application/pdf'];
        return Response::download($file, 'template_ya_wanafunzi_mafundisho.xlsx', $headers);
    })->name('mafundisho_enrollments_template.download');
});



