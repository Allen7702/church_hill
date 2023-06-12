@extends('layouts.receipt_master')
@section('content')
<style>
    *{margin:1px;padding:0}
</style>
<body>
    <table style="border-color:#8707B0;">
        
        <tr style="text-align: center;">
            <td style="width:99%;" style="text-align: center;">
                <table>
                    
                    <tr style="margin-bottom:10mm;">
                        <td style="width:99%;text-align:center;">
                            <img src="{{ public_path('/uploads/images/'.$church_details->photo)}}" style="width:1.5in;height:10mm;text-align:center;" >
                        </td>
                    </tr>

                    <tr>
                        <td width="99%" style="font-size: 12px; font-weight:800; text-align:center; color: #0f2e93;">
                            {{strtoupper($church_details->jimbo)}}</td>
                    </tr>
                    <tr>
                        <td style="width: 99%; text-align:center;">{{strtoupper($church_details->centre_name)}}</td>
                    </tr>
                    <tr>
                        <td style="width: 99%; text-align:center;">Anwani: {{strtoupper($church_details->address)}}</td>
                    </tr>
                    <tr>
                        <td style="width: 99%; text-align:center;">Mawasiliano: {{strtoupper($church_details->telephone1)}} </td>
                    </tr>
                    <tr>
                        <td style="width: 99%; text-align:center;">Baruapepe: {{$church_details->email}}</td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td width="99%">*****************************************************************************</td>
        </tr>

        <tr>
            <td width="99%">Jina: <b>{{$mwanajumuiya}}</b></td>
        </tr>

        <tr>
            <td width="99%">Namba utambulisho: <b>{{$namba_utambulisho}}</b></td>
        </tr>

        <tr>
            <td width="99%">Kanda: <b>{{$kanda}}</b></td>
        </tr>

        <tr>
            <td width="99%">Jumuiya: <b>{{$jumuiya}}</b></td>
        </tr>

        <tr>
            <td width="99%">*****************************************************************************</td>
        </tr>

        <tr>
            <td width="99%">Aina ya mchango: <b>{{$aina_ya_mchango}}</b></td>
        </tr>

        <tr>
            <td width="99%">Kiasi: <b>{{$kiasi}}</b> </td>
        </tr>

        <tr>
            <td width="99%">Tarehe: <b>{{$tarehe}}</b> </td>
        </tr>

        <tr>
            <td width="99%">Nambari ya kumbukumbu: <b>{{$nukushi}}</b> </td>
        </tr>

        <tr>
            <td width="99%">*****************************************************************************</td>
        </tr>

        <tr>
            <td style="width: 99%; text-align: center;">TUMSIFU YESU KRISTO</td>
        </tr>

        <tr>
            <td width="99%">*****************************************************************************</td>
        </tr>

        
    </table>
    
</body>