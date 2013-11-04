<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>Smartdate KPI - Summary</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="css/styles.css" />
        <link rel="stylesheet" type="text/css" href="css/jquery.datepick.css" />
        <link rel="stylesheet" type="text/css" href="css/jquery.tooltip.css" />
        <script type="text/javascript" src="js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="js/jquery.datepick.min.js"></script>
        <script type="text/javascript" src="js/jquery.tooltip.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                // Start with rows hidden
                $('#rangePick').datepick({rangeSelect: true, monthsToShow: 2, showTrigger: '#calImg', dateFormat: 'yyyy/mm/dd'});

                $('.ttip').tooltip({
                    track: true,
                    delay: 0,
                    showURL: false,
                    showBody: " - ",
                    extraClass: "pretty",
                    fixPNG: true,
                    opacity: 0.95,
                    left: -120
                });

                
            });

            function submitForm() {
                country = $("#countryFilter").val();
                dates = $("#rangePick").val();
                data = $("#dataBlock").val();
                $("#chartArea").html("<div class='loading'>&nbsp;</div>");
                action = "GenChart?countryFilter="+country+"&rangePick="+encodeURIComponent(dates)+"&dataBlock="+data;
                $("#chartArea").load(action);
                /*$.get(action, function(data){
                    $("#chartArea").load(action);
                });*/
             }
        </script>
    </head>
    <body>

        <?php include("KPI/View/Elements/banner.php"); ?>
        <?php include("KPI/View/Elements/menu.php"); ?>


        <br style="clear:both" />

        

        <br/>

        <form id="graphForm" class="filter" method="GET">
           <?php $countryFilter = isset($countryFilter) ? $countryFilter : "all" ?>
            Country:
            <select id="countryFilter" name="countryFilter">
                <option value="all" <?php echo $countryFilter=="all" ? "selected" : ""; ?> >All countries</option>
                <option value="fr" <?php echo $countryFilter=="fr" ? "selected" : ""; ?>>France</option>
                <option value="be" <?php echo $countryFilter=="be" ? "selected" : ""; ?>>Belgium</option>
                <option value="es" <?php echo $countryFilter=="es" ? "selected" : ""; ?>>Spain</option>
                <option value="it" <?php echo $countryFilter=="it" ? "selected" : ""; ?>>Italy</option>
                <option value="us" <?php echo $countryFilter=="us" ? "selected" : ""; ?>>United States</option>
                <option value="nl" <?php echo $countryFilter=="nl" ? "selected" : ""; ?>>Netherlands</option>
            </select>
            &nbsp;&nbsp;&nbsp;
            Date range: <input id="rangePick" name="rangePick" type="text" size="23" value="<?php echo $dateRange;?>"/>
            &nbsp;&nbsp;&nbsp;
            <?php $dataBlock = isset($dataBlock) ? $dataBlock : "Registrations" ?>
            Data:
            <select id="dataBlock" name="dataBlock">
                <option value="Registrations" <?php echo $dataBlock=="Registrations" ? "selected" : ""; ?>>Registrations</option>
                <option value="Sales" <?php echo $dataBlock=="Sales" ? "selected" : ""; ?> >Sales</option>
                <option value="FO_sales" <?php echo $dataBlock=="FO_sales" ? "selected" : ""; ?>>FO Revenue</option>
                <option value="RN_sales" <?php echo $dataBlock=="RN_sales" ? "selected" : ""; ?>>RN Revenue</option>
                <option value="R2FO" <?php echo $dataBlock=="R2FO" ? "selected" : ""; ?>>Regs2FO</option>
            </select>
            <a href="javascript: submitForm()" id="draw_graph" >Draw graph</a>
        </form>

        <div id="chartArea">


        </div>
       
    </body>

    <script>
 URL        = "";
 SourceURL  = "";
 LastOpened = "";

 function showHideMenu(Element)
  {
   status = document.getElementById(Element).style.display;
   if ( status == "none" )
    {
     if ( LastOpened != "" && LastOpened != Element ) { showHideMenu(LastOpened); }

     document.getElementById(Element).style.display = "inline";
     document.getElementById(Element+"_main").style.fontWeight = "bold";
     LastOpened = Element;
    }
   else
    {
     document.getElementById(Element).style.display = "none";
     document.getElementById(Element+"_main").style.fontWeight = "normal";
     LastOpened = "";
    }
  }

 function render(PictureName)
  {
   opacity("render",100,0,100);

   RandomKey = Math.random(100);
   URL       = PictureName + "?Seed=" + RandomKey;
   SourceURL = PictureName;

   ajaxRender(URL);
  }

 function StartFade()
  {
   Loader     = new Image();
   Loader.src = URL;
   setTimeout("CheckLoadingStatus()", 200);
  }

 function CheckLoadingStatus()
  {
   if ( Loader.complete == true )
    {
     changeOpac(0, "render");
     HTMLResult = "<center><img src='" + URL + "' alt=''/></center>";
     document.getElementById("render").innerHTML = HTMLResult;

     opacity("render",0,100,100);
     view(SourceURL);
    }
   else
    setTimeout("CheckLoadingStatus()", 200);
  }

 function changeOpac(opacity, id)
  {
   var object = document.getElementById(id).style;
   object.opacity = (opacity / 100);
   object.MozOpacity = (opacity / 100);
   object.KhtmlOpacity = (opacity / 100);
   object.filter = "alpha(opacity=" + opacity + ")";
  }

 function wait()
  {
   HTMLResult = "<center><img src='resources/wait.gif' width=24 height=24 alt=''/><br>Rendering</center>";
   document.getElementById("render").innerHTML = HTMLResult;
   changeOpac(20, "render");
  }

 function opacity(id, opacStart, opacEnd, millisec)
  {
   var speed = Math.round(millisec / 100);
   var timer = 0;

   if(opacStart > opacEnd)
    {
     for(i = opacStart; i >= opacEnd; i--)
      {
       setTimeout("changeOpac(" + i + ",'" + id + "')",(timer * speed));
       timer++;
      }
     setTimeout("wait()",(timer * speed));
    }
   else if(opacStart < opacEnd)
    {
     for(i = opacStart; i <= opacEnd; i++)
      {
       setTimeout("changeOpac(" + i + ",'" + id + "')",(timer * speed));
       timer++;
      }
    }
  }

 function ajaxRender(URL)
  {
   var xmlhttp=false;
   /*@cc_on @*/
   /*@if (@_jscript_version >= 5)
    try { xmlhttp = new ActiveXObject("Msxml2.XMLHTTP"); } catch (e) { try { xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); } catch (E) { xmlhttp = false; } }
   @end @*/

   if (!xmlhttp && typeof XMLHttpRequest!='undefined')
    { try { xmlhttp = new XMLHttpRequest(); } catch (e) { xmlhttp=false; } }

   if (!xmlhttp && window.createRequest)
    { try { xmlhttp = window.createRequest(); } catch (e) { xmlhttp=false; } }

   xmlhttp.open("GET", URL,true);

   xmlhttp.onreadystatechange=function() { if (xmlhttp.readyState==4) { StartFade();  } }
   xmlhttp.send(null)
  }

 function view(URL)
  {
   var xmlhttp=false;
   /*@cc_on @*/
   /*@if (@_jscript_version >= 5)
    try { xmlhttp = new ActiveXObject("Msxml2.XMLHTTP"); } catch (e) { try { xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); } catch (E) { xmlhttp = false; } }
   @end @*/

   URL = "index.php?Action=View&Script=" + URL;

   if (!xmlhttp && typeof XMLHttpRequest!='undefined')
    { try { xmlhttp = new XMLHttpRequest(); } catch (e) { xmlhttp=false; } }

   if (!xmlhttp && window.createRequest)
    { try { xmlhttp = window.createRequest(); } catch (e) { xmlhttp=false; } }

   xmlhttp.open("GET", URL,true);

   xmlhttp.onreadystatechange=function() { if (xmlhttp.readyState==4) { Result = xmlhttp.responseText; document.getElementById("source").innerHTML = Result.replace("/\<BR\>/");  } }
   xmlhttp.send(null)
  }
</script>
</html>
