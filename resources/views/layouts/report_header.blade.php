


@if($church_details != "")


    <div   class="main">
      
           <div class="img1"> <img  src="{{ public_path('/uploads/images/'.$church_details->photo) }}"  style="width:100px;height:100px; border-radius:100px ;"></div>
            <div class="content">
               
                    <div class="text-center">
                        <div style="font-size: 18px; font-weight:500; color: #0080FF; text-align:center;" class="paddingTop">
                            {{strtoupper($church_details->jimbo)}}
                        </div>
                    </div>

                    <div class="text-center">
                        <div style="font-size: 16px; font-weight:500; color: #0080FF;text-align:center;" class="paddingTop">
                            {{strtoupper($church_details->centre_name)}}
                        </div>
                    </div>

                    <div>
                        <div style="text-align:center;" class="paddingTop">Anwani: {{$church_details->address}} {{$church_details->region}}
                            {{$church_details->country}}</div>
                    </div>
                    <div>
                        <div style="text-align:center;" class="paddingTop">Simu ya mezani: {{$church_details->telephone1}} || Baruapepe:
                            {{strtolower($church_details->email)}}</div>
                    </div>
                    <div>
                        <div style="text-align: center; font-weight: bold; color: #0080FF;" class="paddingTop">{{strtoupper($title)}}</div>
                    </div>
              
            </div>
            <div class="img2"><img  src="{{ public_path('/uploads/images/'.$church_details->photo) }}"  style="width:100px;height:100px; border-radius:100px ;"></div>
        </div>

<hr />

<style>
    .main{
      margin-bottom:20%;
      display: block;
      
    }
    .img1{
      width:10%;
    float: left;
    }
    .img2{
        width:10%;
    float: right;
    }

    .content{
        width:80%;
        float:left; 
    }
    .paddingTop{
        margin-top:5px;
    }
</style>

@endif
