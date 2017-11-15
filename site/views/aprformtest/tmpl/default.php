<?php

defined('_JEXEC') or die('RESTRICTED ACCESS');

JHtml::_('bootstrap.framework');
JHtml::_('jquery.framework',false);

?>

<?php 
$year = date("Y"); 
$month = date("n");
$isLeapYear = ($year % 4) || (($year % 100 === 0) &&($year % 400)) ? 0 : 1;
$daysInMonth = 31 - (($month == 2) ?(3 - $isLeapYear) : (($month - 1) % 7 % 2));
?>

<script type="text/javascript">

function getDays(year,month){
    var day = new Date(year,month,0);
    return day.getDate();
}

function valida_calidad(e){
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla==8){
        return true;
    }
        
    // Patron de entrada, en este caso solo acepta numeros
    patron =/[0-5]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}
function valida_cantidad(e){
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla==8){
        return true;
    }
        
    // Patron de entrada, en este caso solo acepta numeros
    patron =/[0-9]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}


function refreshData(){
    <!-- ADVERTENCIA, HORRENDO CODIGO ADELANTE -->

    var temp="";

    var ago = document.getElementById("ago");
    var mes = document.getElementById("mes");
    var apr = document.getElementById("apr");

    var agoS = ago.options[ago.selectedIndex].value;
    var mesS = mes.options[mes.selectedIndex].value;
    var aprS = apr.options[apr.selectedIndex].value;

    var day = getDays(ago.options[ago.selectedIndex].value,mes.options[mes.selectedIndex].value);

    for(var i=1;i<=31;i++){
        temp="row";
        temp = temp += i;
        var hide = document.getElementById(temp);
        hide.style.display = "none";
    }

    for(var i=1;i<=day;i++){
        temp="row";
        temp = temp += i;
        var hide = document.getElementById(temp);
        hide.style.display = "";
        var a = "Cantidad";
        var b = "Calidad";
        var c = "Precipitacion";
        a = a += i;
        b = b += i;
        c = c += i;

        var cantidad = document.getElementById(a);
        var calidad = document.getElementById(b);
        var precipitacion = document.getElementById(c);

        cantidad.value = "";
        calidad.value = "";
        precipitacion.value = "";
    }

    jQuery.ajax({
        url:"index.php?option=com_aprformtest&task=LoadData&format=json",
        method:"POST",
        dataType:'json',
        data:{year: ago.options[ago.selectedIndex].value, month:mes.options[mes.selectedIndex].value, id:apr.options[apr.selectedIndex].value},
        success: function (response){
            console.log(response);
            for (i = 0; i < response.length; i++){
                var a = "Cantidad";
                var b = "Calidad";
                var c = "Precipitacion";
                a = a += response[i][0];
                b = b += response[i][0];
                c = c += response[i][0];


                var cantidad = document.getElementById(a);
                var calidad = document.getElementById(b);
                var precipitacion = document.getElementById(c);

                cantidad.value = response[i][3];
                calidad.value = response[i][2];
                precipitacion.value = response[i][1];
                }
            }
        });
}
function alertaMen(){
    alert("Datos ingresados correctamente");
}

function saveData(){

    var aux1,aux2,aux3;

    var ago = document.getElementById("ago");
    var mes = document.getElementById("mes");
    var apr = document.getElementById("apr");

    var agoS = ago.options[ago.selectedIndex].value;
    var mesS = mes.options[mes.selectedIndex].value;
    var aprS = apr.options[apr.selectedIndex].value;

    var day = getDays(ago.options[ago.selectedIndex].value,mes.options[mes.selectedIndex].value);

    var data = [];
    for (var i=1;i<=day;i++){
        var fecha = agoS+"-"+mesS+"-"+i;
        var row = document.getElementById("row"+i);
        if (row.childNodes[3].childNodes[0].value != "" || row.childNodes[5].childNodes[0].value!="" || row.childNodes[7].childNodes[0].value!=""){
            if(row.childNodes[3].childNodes[0].value == ""){
                aux1 = "Null"}else{
                    aux1 = row.childNodes[3].childNodes[0].value;
                }
            if(row.childNodes[5].childNodes[0].value == ""){
                aux2 = "Null"}else{
                    aux2 = row.childNodes[5].childNodes[0].value;
                }
            if(row.childNodes[7].childNodes[0].value == ""){
                aux3 = "Null"}else{
                    aux3 = row.childNodes[7].childNodes[0].value;
                }
            data.push({
                "year": agoS,
                "month": mesS,
                "apr": aprS,
                "dia": i,
                "fecha": fecha,
                "cantidad": aux1,
                "calidad" : aux2,
                "precipitacion": aux3
            });
        }
    }
    var dataJson=JSON.stringify(data);
    console.log(dataJson);
    jQuery.ajax({
        url:"index.php?option=com_aprformtest&task=SaveData&format=json",
        method:"POST",
        dataType:'json',
        data:{data:dataJson,year:agoS,month:mesS,id:aprS},
        success:function(response){
            console.log("exitoo "+response);
            alertaMen();
        }
    });
}

</script>
<h1> APR Form </h1>
<body onload=refreshData()>
    <form action="" method="POST">
        <div>
        <fieldset>
                <legend>Formulario de datos APR</legend>
                <select id="ago" name="ago">
                <?php 
                for($year;$year>=2013;$year--){?>
                    <option value=<?php echo $year; ?> onClick=refreshData()><?php echo $year; ?></option>
                <?php }?>
                </select>

                <select value="<?php echo $month;?>" id="mes" name"mes">
                <option value="1" <?php if ($month==1){ ?>selected="true" <?php } ?> onClick=refreshData()>Enero</option>
                <option value="2" <?php if ($month==2){ ?>selected="true" <?php } ?> onClick=refreshData()>Febrero</option>
                <option value="3" <?php if ($month==3){ ?>selected="true" <?php } ?> onClick=refreshData()>Marzo</option>
                <option value="4" <?php if ($month==4){ ?>selected="true" <?php } ?> onClick=refreshData()>Abril</option>
                <option value="5" <?php if ($month==5){ ?>selected="true" <?php } ?> onClick=refreshData()>Mayo</option>
                <option value="6" <?php if ($month==6){ ?>selected="true" <?php } ?> onClick=refreshData()>Junio</option>
                <option value="7" <?php if ($month==7){ ?>selected="true" <?php } ?> onClick=refreshData()>Julio</option>
                <option value="8" <?php if ($month==8){ ?>selected="true" <?php } ?> onClick=refreshData()>Agosto</option>
                <option value="9" <?php if ($month==9){ ?>selected="true" <?php } ?> onClick=refreshData()>Septiembre</option>
                <option value="10" <?php if ($month==10){ ?>selected="true" <?php } ?> onClick=refreshData()>Octubre</option>
                <option value="11" <?php if ($month==11){ ?>selected="true" <?php } ?> onClick=refreshData()>Noviembre</option>
                <option value="12" <?php if ($month==12){ ?>selected="true" <?php } ?> onClick=refreshData()>Diciembre</option>
                </select>

                <select id="apr" name="apr"><?php
                    foreach ($this->aprs as $apr){ ?>
                        <option value="<?php echo $apr[0] ?>" onClick=refreshData()> <?php echo $apr[1] ?> </option>
                    <?php }?>
                </select>
            </fieldset>
        </div>
        <div>
        <table class="border border-primary">
            <tr class="border border-primary">
                <th class="border border-primary">Dia</th>
                <th class="border border-primary">Cantidad</th>
                <th class="border border-primary">Calidad</th>
                <th class="border border-primary">Precipitacion</th>
            </tr>
            <?php for($i=1;$i<=31;$i++){?>
            <tr class="border border-primary" id=row<?php echo $i ?>>
                <td class="border border-primary"><text><?php echo $i;?></text></td>
                <td class="border border-primary"><input onkeypress="return valida_cantidad(event)" id ="Cantidad<?php echo $i ?>" name ="Cantidad<?php echo $i ?>"> </td>
                <td class="border border-primary"><input onkeypress="return valida_calidad(event)" maxlength="1" id ="Calidad<?php echo $i ?>" name ="Calidad<?php echo $i ?>"></td>
                <td class="border border-primary"><input onkeypress="return valida_cantidad(event)" id ="Precipitacion<?php echo $i ?>" name ="Precipitacion<?php echo $i ?>"></td>
            </tr>
            <?php }?>
        </table>
        <button type="button" onClick=saveData()>Ingresar Datos</button>
        </div>
    </form>
</body>
