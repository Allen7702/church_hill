<div class="signature" style="margin-top:20px;">
    <div class="imeandaliwa">
    <label class="pull-left">Imeandaliwa na:</label>
    <br />
    <br />
    <label class="pull-right">Jina:  {{Auth::user()->jina_kamili}}</label>
    <br />
    <br />
    <label class="pull-right">Saini..........................</label>
    <br />
    <br />
    <label class="pull-right">Tarehe: @php echo date('d/m/Y'); @endphp</label>
    </div>
    <div class="imethibitishwa">
        <label class="pull-left">Imethibitishwa na:
        </label>
        <br />
        <br />
        <label class="pull-right">Jina............................................................</label>
        <br />
        <br />
        <label class="pull-right">Wadhifa/Cheo..........................................</label>
        <br />
        <br />
        <label class="pull-right">Saini.....................  Tarehe....../..../20........</label>
        </div>
</div>

<style>
    .signature{
    margin: 0 auto;
    }
    .imeandaliwa{
        width: 40%;
    float: left;

    }
    .imethibitishwa{
        width: 60%;
    background: #ffffff;
    float:right;
 
    margin-right: 0px;

    }
</style>