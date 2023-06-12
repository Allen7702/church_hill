@if($church_details != "")

<style>
    table {
        width: 100%;
    }
    
    td,
    th {
        text-align: left;
        padding: 2px;
        border-collapse: collapse;
    }
</style>
<div>
    <table class="head">
        <tr>
            <td style="width:10%;text-align:center;"><img style="border-radius: 70%" src="{{ asset('/uploads/images/'.$church_details->photo) }}" width="120px" height="120px"></td>
            <td style="width:80%;">
                <table>

                    <tr class="text-center">
                        <td style="font-size: 18px; font-weight:500; color: #0080FF; text-align:center;">
                            {{strtoupper($church_details->jimbo)}}
                        </td>
                    </tr>

                    <tr class="text-center">
                        <td style="font-size: 16px; font-weight:500; color: #0080FF;text-align:center;">
                            {{strtoupper($church_details->centre_name)}}
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align:center;">Anwani: {{$church_details->address}} {{$church_details->region}} {{$church_details->country}}
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;">Simu ya mezani: {{$church_details->telephone1}} || Baruapepe: {{$church_details->email}}
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center; font-weight: bold; color: #0080FF;">{{strtoupper($title)}}</td>
                    </tr>
                </table>
            </td>
            <td style="width:20%;text-align:center;"><img style="border-radius: 70%" src="{{ asset('/uploads/images/'.$church_details->photo) }}" width="120px" height="120px"></td>
        </tr>
    </table>
</div>

@endif