<?php 
    /* Dashboard Builder.
   Copyright (C) 2018 DISIT Lab https://www.disit.org - University of Florence

   This program is free software; you can redistribute it and/or
   modify it under the terms of the GNU General Public License
   as published by the Free Software Foundation; either version 2
   of the License, or (at your option) any later version.
   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.
   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA. */
   include '../config.php';
   header("Cache-Control: private, max-age=$cacheControlMaxAge");
   
   //Va studiata una soluzione, per ora tolto error reporting
   error_reporting(0);
   
   $dashId = base64_decode($_REQUEST['iddasboard']);
   
   session_start();
   
    $link = mysqli_connect($host, $username, $password) or die();
    mysqli_select_db($link, $dbname);

    $query = "SELECT * FROM Dashboard.Config_dashboard WHERE Config_dashboard.Id = $dashId";
    $queryResult = mysqli_query($link, $query);
    
    if(isset($_REQUEST['embedPolicy']))
    {
        $embedPolicy = $_REQUEST['embedPolicy'];
    }
    else
    {
        $embedPolicy = 'manual';
    }
    
    if(isset($_REQUEST['autofit']))
    {
        $embedAutofit = $_REQUEST['autofit'];
    }
    else
    {
        $embedAutofit = 'no';
    }
    
    if(isset($_REQUEST['showHeader']))
    {
        $showHeaderEmbedded = $_REQUEST['showHeader'];
    }
    else
    {
        $showHeaderEmbedded = 'yes';
    }

    if($queryResult) 
    {
       if($queryResult->num_rows > 0) 
       {     
           while($row = mysqli_fetch_array($queryResult)) 
           {
              $embeddable = $row['embeddable'];
              $authorizedPages = $row['authorizedPagesJson'];
           }
       }
       else
       {
           $embeddable = 'no';
       }
    }
    else
    {
        $embeddable = 'no';
    }
    
   mysqli_close($link);
   
   if(isset($_SERVER['HTTP_REFERER']))
   {
       if((strpos($_SERVER['HTTP_REFERER'], "http://".$appHost) !== false)||(strpos($_SERVER['HTTP_REFERER'], "https://".$appHost) !== false))
       {
           //Caso embed in una dashboard e previewer: in questo caso dev'essere sempre possibile fare l'embed
           $embeddable = 'yes';
       }
       else
       {
            //Caso embed in pagina esterna
            if($embeddable == "no")
            {
                header('X-Frame-Options: DENY');
            }
            else
            {
                if(($authorizedPages != '')&&($authorizedPages != null)&&($authorizedPages != 'NULL'))
                {
                    $authorizedPages = json_decode($authorizedPages);
                    $isAuthorized = false;
                    for($i = 0; $i < count($authorizedPages); $i++)
                    {
                        if(strpos($_SERVER['HTTP_REFERER'], $authorizedPages[$i]) !== false)
                        {
                            $isAuthorized = true;
                            break;
                        }
                    }

                    if(!$isAuthorized)
                    {
                        header('X-Frame-Options: DENY');
                    }
                }
                else
                {
                    header('X-Frame-Options: DENY');
                }
            }
        }
   } 
   else 
   {
       //Va studiata una soluzione, per ora tolto error reporting
       /*if(strpos($_SERVER['HTTP_REFERER'], $appUrl) !== false)
       {
           $embeddable = 'no';
       } */
   }
?>
<!DOCTYPE html>
<html lang="en">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard Management System</title>

    <!-- Bootstrap Core CSS -->
    <link href="../css/bootstrap.css" rel="stylesheet">
    
    <!-- Modernizr -->
    <script src="../js/modernizr-custom.js"></script>

    <!-- Custom CSS -->
    <link href="../css/dashboard.css?v=<?php echo time();?>" rel="stylesheet">
    <link href="../css/dashboardView.css?v=<?php echo time();?>" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles_gridster.css" type="text/css" />
    <link href="../css/widgetCtxMenu.css?v=<?php echo time();?>" rel="stylesheet">
    <link rel="stylesheet" href="../css/style_widgets.css?v=<?php echo time();?>" type="text/css" />
    
    <!-- Material icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- jQuery -->
    <script src="../js/jquery-1.10.1.min.js"></script>
    
    <!-- JQUERY UI -->
    <script src="../js/jqueryUi/jquery-ui.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.min.js"></script>

    <!-- Gridster -->
    <link rel="stylesheet" type="text/css" href="../css/jquery.gridster.css">
    <script src="../js/jquery.gridsterMod.js" type="text/javascript" charset="utf-8"></script>
    <!--<link rel="stylesheet" type="text/css" href="../newGridster/dist/jquery.gridster.css">
    <script src="../newGridster/dist/jquery.gridster.js" type="text/javascript" charset="utf-8"></script>-->

    <!-- Highcharts --> 
    <script src="../js/highcharts/code/highcharts.js"></script>
    <script src="../js/highcharts/code/modules/exporting.js"></script>
    <script src="../js/highcharts/code/highcharts-more.js"></script>
    <script src="../js/highcharts/code/modules/solid-gauge.js"></script>
    <script src="../js/highcharts/code/highcharts-3d.js"></script>
    
    <!-- TinyColors -->
    <script src="../js/tinyColor.js" type="text/javascript" charset="utf-8"></script>
    
    <!-- Font awesome icons -->
    <link rel="stylesheet" href="../js/fontAwesome/css/font-awesome.min.css">
    
    <!-- Leaflet -->
    <!-- Versione locale: 1.3.1 --> 
    <link rel="stylesheet" href="../leafletCore/leaflet.css" />
    <script src="../leafletCore/leaflet.js"></script>
   
   <!-- Leaflet marker cluster plugin -->
   <link rel="stylesheet" href="../leaflet-markercluster/MarkerCluster.css" />
   <link rel="stylesheet" href="../leaflet-markercluster/MarkerCluster.Default.css" />
   <script src="../leaflet-markercluster/leaflet.markercluster-src.js" type="text/javascript" charset="utf-8"></script>
   
   <!-- Leaflet Wicket: libreria per parsare i file WKT --> 
   <script src="../wicket/wicket.js"></script> 
   <script src="../wicket/wicket-leaflet.js"></script>
   
   <!-- Dot dot dot -->
   <script src="../dotdotdot/jquery.dotdotdot.js" type="text/javascript"></script>
   
    <!-- Bootstrap select -->
    <link href="../bootstrapSelect/css/bootstrap-select.css" rel="stylesheet"/>
    <script src="../bootstrapSelect/js/bootstrap-select.js"></script>
    
    <!-- Moment -->
    <script type="text/javascript" src="../moment/moment.js"></script>
    
    <!-- html2canvas -->
    <script type="text/javascript" src="../js/html2canvas.js"></script>
    
    <!-- Bootstrap datetimepicker -->
    <script src="../datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
    <link rel="stylesheet" href="../datetimepicker/build/css/bootstrap-datetimepicker.min.css">
    
    <!-- Weather icons -->
    <link rel="stylesheet" href="../img/meteoIcons/singleColor/css/weather-icons.css?v=<?php echo time();?>">
    
    <!-- Text fill -->
    <script src="../js/jquery.textfill.min.js"></script>
    
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    
    <!-- Reconnecting WS -->
    <script src="../reconnecting-websocket/reconnecting-websocket.min.js"></script>
    
    <script src="../js/widgetsCommonFunctions.js?v=<?php echo time();?>" type="text/javascript" charset="utf-8"></script>
    <script src="../widgets/trafficEventsTypes.js?v=<?php echo time();?>" type="text/javascript" charset="utf-8"></script>
    <script src="../widgets/alarmTypes.js?v=<?php echo time();?>" type="text/javascript" charset="utf-8"></script>
    <script src="../widgets/fakeGeoJsons.js?v=<?php echo time();?>" type="text/javascript" charset="utf-8"></script>

    <script type='text/javascript'>
        var array_metrics = new Array();
        var headerFontSize, headerModFontSize, subtitleFontSize, subtitleModFontSize, dashboardId, dashboardName, logoFilename, logoLink, 
            clockFontSizeMod, logoWidth, logoHeight, headerVisible = null;
    
        var dashboardZoomEventHandler = function(event)
        {
            document.body.style.zoom = event.data;
        };

        window.addEventListener('message', dashboardZoomEventHandler, false);    
        
        $(document).ready(function () 
        {
            var widgetsBorders, widgetsBordersColor, embedWidget, embedWidgetPolicy, headerVisible, wrapperWidth, dashboardViewMode, gridster, gridsterCellW, gridsterCellH, widgetsContainerWidth, num_cols = null;
            var firstLoad = true;
            var loggedUserFirstAttempt = true;
            var myGpsActive, myGpsPeriod, myGpsInterval, globalDashboardTitle = null;
            var embedPreview = "<?php if(isset($_REQUEST['embedPreview'])){echo $_REQUEST['embedPreview'];}else{echo 'false';} ?>";
            
            // Fullscreen: passargli sempre il documentElement 
            $('#fullscreenButton').click(function(){
                if(document.documentElement.requestFullscreen) 
                {
                    document.documentElement.requestFullscreen();
                } 
                else if(document.documentElement.mozRequestFullScreen) 
                {
                    document.documentElement.mozRequestFullScreen();
                }
                else if(document.documentElement.webkitRequestFullScreen) 
                {
                    document.documentElement.webkitRequestFullScreen();
                } 
                else if(document.documentElement.msRequestFullscreen) 
                {
                    document.documentElement.msRequestFullscreen();
                }
                $('#fullscreenButton').hide();
                $('#restorescreenButton').show();
            });
            
            $('#restorescreenButton').click(function(){
                if(document.exitFullscreen) 
                {
                    document.exitFullscreen();
                } 
                else if(document.webkitExitFullscreen) 
                {
                    document.webkitExitFullscreen();
                } 
                else if(document.mozCancelFullScreen) 
                {
                    document.mozCancelFullScreen();
                } 
                else if(document.msExitFullscreen) 
                {
                    document.msExitFullscreen();
                }
                $('#restorescreenButton').hide();
                $('#fullscreenButton').show();
            });
            
            $(window).resize(function(){
                $('#clock').textfill({
                    maxFontPixels: 24
                });
                
                $('#fullscreenBtnContainer').textfill({
                    maxFontPixels: 32
                });
                
                $('#dashboardTitle').textfill({
                    maxFontPixels: -20
                });
                
                $('#dashboardSubtitle').textfill({
                    maxFontPixels: -20
                });
        
                switch(dashboardViewMode)
                {
                    case "fixed":
                        gridsterCellW = 76;
                        gridsterCellH = 38;
                        widgetsContainerWidth = num_cols*(gridsterCellW + 2);
                        break;
                        
                    case "smallResponsive":
                        if($(window).width() > 768)
                        {
                            gridsterCellW = 76;
                            gridsterCellH = 38;
                            widgetsContainerWidth = num_cols*(gridsterCellW + 2);
                        }
                        else
                        {
                            gridsterCellW = Math.floor(parseInt($('body').width()*0.98) / num_cols) - 2;
                            gridsterCellH = gridsterCellW/2;
                            widgetsContainerWidth = num_cols*(gridsterCellW + 2);
                        }
                        break;
                        
                    case "mediumResponsive":
                        if($(window).width() > 992)
                        {
                            gridsterCellW = 76;
                            gridsterCellH = 38;
                            widgetsContainerWidth = num_cols*(gridsterCellW + 2);
                        }
                        else
                        {
                            gridsterCellW = Math.floor(parseInt($('body').width()*0.98) / num_cols) - 2;
                            gridsterCellH = gridsterCellW/2;
                            widgetsContainerWidth = num_cols*(gridsterCellW + 2);
                        }
                        break;
                        
                    case "largeResponsive":
                        if($(window).width() > 1200)
                        {
                            gridsterCellW = 76;
                            gridsterCellH = 38;
                            widgetsContainerWidth = num_cols*(gridsterCellW + 2);
                        }
                        else
                        {
                            gridsterCellW = Math.floor(parseInt($('body').width()*0.98) / num_cols) - 2;
                            gridsterCellH = gridsterCellW/2;
                            widgetsContainerWidth = num_cols*(gridsterCellW + 2);
                        }
                        break;    
                        
                    case "alwaysResponsive":
                        gridsterCellW = Math.floor(parseInt($('body').width()*0.98) / num_cols) - 2;
                        gridsterCellH = gridsterCellW/2;
                        widgetsContainerWidth = num_cols*(gridsterCellW + 2);
                        break;
                        
                    default:
                        gridsterCellW = 76;
                        gridsterCellH = 38;
                        widgetsContainerWidth = num_cols*(gridsterCellW + 2);
                        break;    
                }
                
                $('#dashboardViewWidgetsContainer').css('width', widgetsContainerWidth + "px");
                $('div.footerLogos').css('margin-right', ($('body').width() - widgetsContainerWidth)/2);

                gridster.resize_widget_dimensions({
                    widget_base_dimensions: [gridsterCellW, gridsterCellH],
                    widget_margins: [1, 1]
                });
                                
                $('li.gs_w').trigger({
                    type: "resizeWidgets"
                });                
            });
            
            //Definizioni di funzione
            function loadDashboard(dashboardParams, dashboardWidgets)
            {
                var minEmbedDim, autofitAlertFontSize;
                
                globalDashboardTitle = dashboardParams.title_header;
                
                if('<?php echo $embeddable; ?>' === 'yes')
                {
                    if(window.self !== window.top)
                    {
                        if(('<?php echo $embedPolicy; ?>' === 'auto')||(('<?php echo $embedPolicy; ?>' !== 'auto')&&('<?php echo $embedAutofit; ?>' === 'yes')))
                        {
                            $('#autofitAlert').css("width", $(window).width());
                            $('#autofitAlert').css("height", $(window).height());
                            $('#autofitAlertMsgContainer').css("height", $(window).height()*0.45);
                            $('#autofitAlertIconContainer').css("height", $(window).height()*0.55);
                            
                            if($(window).height() < $(window).width())
                            {
                                minEmbedDim = $(window).height();
                            }
                            else
                            {
                                minEmbedDim = $(window).width();
                            }
                            
                            if((minEmbedDim > 0) && (minEmbedDim < 300))
                            {
                                autofitAlertFontSize = 16;
                            }
                            else
                            {
                                if((minEmbedDim >= 300) && (minEmbedDim < 600))
                                {
                                    autofitAlertFontSize = 24;
                                }
                                else
                                {
                                    if((minEmbedDim >= 600) && (minEmbedDim < 900))
                                    {
                                        autofitAlertFontSize = 32;
                                    }
                                    else
                                    {
                                        autofitAlertFontSize = 36;
                                    }
                                }
                            }
                            
                            $('#autofitAlertMsgContainer').css("font-size", autofitAlertFontSize + "px");
                            $('#autofitAlertIconContainer i.fa-spin').css("font-size", autofitAlertFontSize*2 + "px");
                            
                            $('#autofitAlert').show();
                        }
                    }
                }
                
                $('body').removeClass("dashboardViewBodyAuth");
                
                dashboardId = <?= base64_decode($_GET['iddasboard']) ?>;
                dashboardName = dashboardParams.name_dashboard;
                logoFilename = dashboardParams.logoFilename;
                logoLink = dashboardParams.logoLink;
                headerVisible = dashboardParams.headerVisible;
                dashboardViewMode = dashboardParams.viewMode;
                widgetsBorders = dashboardParams.widgetsBorders;
                widgetsBordersColor = dashboardParams.widgetsBordersColor;
                $("#headerLogoImg").css("display", "none");
                $("#dashboardViewHeaderContainer").css("background-color", dashboardParams.color_header);

                //Sfondo
                $("body").css("background-color", dashboardParams.external_frame_color);
                $("#dashboardViewWidgetsContainer").css("background-color", dashboardParams.color_background);
                var headerFontColor = dashboardParams.headerFontColor;
                var headerFontSize = dashboardParams.headerFontSize;
                
                $("#dashboardTitle").css("color", headerFontColor);
                $("#dashboardTitle span").text(dashboardParams.title_header);
                $("#clock").css("color", headerFontColor);
                $('#fullscreenBtnContainer').css("color", headerFontColor);
                
                $('#clock').textfill({
                    maxFontPixels: -20
                });
                
                $('#fullscreenBtnContainer').textfill({
                    maxFontPixels: 32
                });

                var whiteSpaceRegex = '^[ t]+';
                if((dashboardParams.subtitle_header === "") || (dashboardParams.subtitle_header === null) ||(typeof dashboardParams.subtitle_header === 'undefined') ||(dashboardParams.subtitle_header.match(whiteSpaceRegex)))
                {
                    $("#dashboardTitle").css("height", "100%");
                    $("#dashboardSubtitle").css("display", "none");
                }
                else
                {
                    $("#dashboardTitle").css("height", "70%");
                    $("#dashboardSubtitle").css("height", "30%");
                    $("#dashboardSubtitle").css("display", "flex");
                    $("#dashboardSubtitle").css("color", headerFontColor);
                    $("#dashboardSubtitle span").text(dashboardParams.subtitle_header);
                }
                
                $('#dashboardTitle').textfill({
                    maxFontPixels: -20
                });
                
                $('#dashboardSubtitle').textfill({
                    maxFontPixels: -20
                });

                if(logoFilename !== null)
                {
                    $("#headerLogoImg").prop("src", "../img/dashLogos/dashboard" + dashboardId + "/" + logoFilename);
                    $("#headerLogoImg").prop("alt", "Dashboard logo");
                    $("#headerLogoImg").show();
                    if((logoLink !== null) && (logoLink !== ''))
                    {
                       var logoImage = $('#headerLogoImg');
                       var logoLinkElement = $('<a href="' + logoLink + '" target="_blank" class="pippo">'); 
                       logoImage.wrap(logoLinkElement); 
                    }
                }

                num_cols = dashboardParams.num_columns;
                
                switch(dashboardViewMode)
                {
                    case "fixed":
                        gridsterCellW = 76;
                        gridsterCellH = 38;
                        widgetsContainerWidth = num_cols*(gridsterCellW + 2);
                        break;
                        
                    case "smallResponsive":
                        if($(window).width() > 768)
                        {
                            gridsterCellW = 76;
                            gridsterCellH = 38;
                            widgetsContainerWidth = num_cols*(gridsterCellW + 2);
                        }
                        else
                        {
                            gridsterCellW = Math.floor(parseInt($('body').width()*0.98) / num_cols) - 2;
                            gridsterCellH = gridsterCellW/2;
                            widgetsContainerWidth = num_cols*(gridsterCellW + 2);
                        }
                        break;
                        
                    case "mediumResponsive":
                        if($(window).width() > 992)
                        {
                            gridsterCellW = 76;
                            gridsterCellH = 38;
                            widgetsContainerWidth = num_cols*(gridsterCellW + 2);
                        }
                        else
                        {
                            gridsterCellW = Math.floor(parseInt($('body').width()*0.98) / num_cols) - 2;
                            gridsterCellH = gridsterCellW/2;
                            widgetsContainerWidth = num_cols*(gridsterCellW + 2);
                        }
                        break;
                        
                    case "largeResponsive":
                        if($(window).width() > 1200)
                        {
                            gridsterCellW = 76;
                            gridsterCellH = 38;
                            widgetsContainerWidth = num_cols*(gridsterCellW + 2);
                        }
                        else
                        {
                            gridsterCellW = Math.floor(parseInt($('body').width()*0.98) / num_cols) - 2;
                            gridsterCellH = gridsterCellW/2;
                            widgetsContainerWidth = num_cols*(gridsterCellW + 2);
                        }
                        break;     
                        
                    case "alwaysResponsive":
                        gridsterCellW = Math.floor(parseInt($('body').width()*0.98) / num_cols) - 2;
                        gridsterCellH = gridsterCellW/2;
                        widgetsContainerWidth = num_cols*(gridsterCellW + 2);
                        break;
                        
                    default:
                        gridsterCellW = 76;
                        gridsterCellH = 38;
                        widgetsContainerWidth = num_cols*(gridsterCellW + 2);
                        break;    
                }
                
                $('#dashboardViewWidgetsContainer').css('width', widgetsContainerWidth + "px");
                $('div.footerLogos').css('margin-right', ($('body').width() - widgetsContainerWidth)/2);
                
               if(window.self === window.top)
               {
                    //Controllo mostrare/nascondere header su view principale
                    if(headerVisible === '1')
                    {
                       $("#dashboardViewHeaderContainer").show();
                       $('#dashboardViewWidgetsContainer').css('margin-top', ($('#dashboardViewHeaderContainer').height() + 15) + "px");
                    }
                    else
                    {
                       $("#dashboardViewHeaderContainer").hide();
                       $('#dashboardViewWidgetsContainer').css('margin-top', "0px");
                    } 
               }
               else
               {
                    //Controllo mostrare/nascondere header in modalità embedded
                    if('<?php echo $embedPolicy; ?>' === 'auto')
                    {
                        $("#dashboardViewHeaderContainer").hide();
                        $("#dashboardViewHeaderContainer").css("margin-bottom", "0px");
                    }
                    else
                    {
                        if('<?php echo $showHeaderEmbedded; ?>' === 'no')
                        {
                            $("#dashboardViewHeaderContainer").hide();
                            $("#dashboardViewHeaderContainer").css("margin-bottom", "0px");
                        }
                        else
                        {
                            $("#dashboardViewHeaderContainer").show();
                            $('#dashboardViewWidgetsContainer').css('margin-top', ($('#dashboardViewHeaderContainer').height() + 15) + "px");
                        }
                    }
                    $("#logos a.footerLogo").hide();
                    $("#logos #embedAutoLogoContainer").show();
               }
               
                gridster = $("#gridsterUl").gridster({
                    widget_base_dimensions: [gridsterCellW, gridsterCellH],
                    widget_margins: [1, 1],
                    min_cols: num_cols,
                    max_size_x: 100,
                    max_rows: 100,
                    extra_rows: 100,
                    draggable: {ignore_dragging: false},
                    serialize_params: function ($w, wgd){
                        return {
                            id: $w.attr('id'),
                            col: wgd.col,
                            row: wgd.row,
                            size_x: wgd.size_x,
                            size_y: wgd.size_y
                        };
                    }
                }).data('gridster').disable();//Fine creazione Gridster
                
                for(var i = 0; i < dashboardWidgets.length; i++)
                {
                    var time = 0;
                    if(dashboardWidgets[i]['temporal_range_w'] === "Mensile") 
                    {
                        time = "30/DAY";
                    } 
                    else if (dashboardWidgets[i]['temporal_range_w'] === "Annuale") 
                    {
                        time = "365/DAY";
                    } 
                    else if (dashboardWidgets[i]['temporal_range_w'] === "Settimanale") 
                    {
                        time = "7/DAY";
                    } 
                    else if (dashboardWidgets[i]['temporal_range_w'] === "Giornaliera") 
                    {
                        time = "1/DAY";
                    } 
                    else if (dashboardWidgets[i]['temporal_range_w'] === "4 Ore") 
                    {
                        time = "4/HOUR";
                    } 
                    else if (dashboardWidgets[i]['temporal_range_w'] === "12 Ore") 
                    {
                        time = "12/HOUR";
                    }
                    var widget = ['<li data-widgetType="' + dashboardWidgets[i]['type_w'] + '" data-widgetId="' + dashboardWidgets[i]['Id'] + '" id="' + dashboardWidgets[i]['name_w'] + '"></li>', dashboardWidgets[i]['size_columns'], dashboardWidgets[i]['size_rows'], dashboardWidgets[i]['n_column'], dashboardWidgets[i]['n_row']];

                    gridster.add_widget.apply(gridster, widget);
                    
                    if(('<?php echo $embeddable; ?>' === 'yes')&&(window.self !== window.top))
                    {
                        embedWidget = true;
                    }
                    else
                    {
                        embedWidget = false;
                    }
                    embedWidgetPolicy = '<?php echo $embedPolicy; ?>';
                    
                    dashboardWidgets[i].time = time;
                    dashboardWidgets[i].embedWidget = embedWidget;
                    dashboardWidgets[i].embedWidgetPolicy = embedWidgetPolicy;
                    dashboardWidgets[i].hostFile = 'index';
                    
                    $("#gridsterUl").find("li#" + dashboardWidgets[i]['name_w']).load("../widgets/" + encodeURIComponent(dashboardWidgets[i]['type_w']) + ".php", dashboardWidgets[i]);

                }//Fine del secondo for

                //Applicazione bordi dei widgets
                if(widgetsBorders === 'yes')
                {
                    $(".gridster .gs_w").css("border", "1px solid " + widgetsBordersColor);
                }
                else
                {
                    $(".gridster .gs_w").css("border", "none");
                }

                //Icona info
                $(document).on('click', '.info_source', function () {
                    var name_widget_m = $(this).parents('li').attr('id');
                    $.ajax({
                        url: "../management/get_data.php",
                        data: {widget_info: name_widget_m, action: "get_info_widget"},
                        type: "GET",
                        async: true,
                        dataType: 'json',
                        success: function (data) {
                            $('#titolo_info').text(data['title_widget']);
                            $('#contenuto_infomazioni').html(data['info_mess']);
                            $('#dialog-information-widget').modal('show');
                            $('#dialog-information-widget').css({
                                'vertical-align': 'middle',
                                'position': 'absolute',
                                'top': '10%'
                            });
                        }
                    });
                });
                
                if(('<?php echo $embeddable; ?>' === 'yes')&&(window.self !== window.top))
                {
                    if('<?php echo $embedPolicy; ?>' === 'auto')
                    {
                        //Cambia logo se embedded in sito diverso dal dashboard manager
                        if(!document.referrer.includes(window.self.location.host)||((embedPreview === 'true')&&(document.referrer.includes(window.self.location.host))))
                        {
                            $('#page-wrapper div.container-fluid div.footerLogos').hide();
                            $('#page-wrapper #embedAutoLogoContainer').css("width", $('#wrapper-dashboard').css("width"));
                            $('#page-wrapper div.container-fluid div.footerLogos').hide();
                            $('#page-wrapper #embedAutoLogoContainer').css("background-color", $('#container-widgets').css("background-color"));
                            $('#page-wrapper #embedAutoLogoContainer').css("display", "flex");
                            $('#page-wrapper #embedAutoLogoContainer').css("align-items", "flex-start");
                            $('#page-wrapper #embedAutoLogoContainer').css("justify-content", "flex-start");
                            $('#page-wrapper #embedAutoLogoContainer').css("margin-left", "10px");
                        }
                        
                        $('#wrapper-dashboard').css("width", $('#wrapper-dashboard').width() - 40);
                        $('#page-wrapper div.container-fluid').css('padding-left', '0px');
                        $('#page-wrapper div.container-fluid').css('padding-right', '0px');
                        
                        var widthRatio, heightRatio, iframeW, iframeH, iframeCase = null;
                        
                        //Il timeout serve per consentire a Gridster il caricamento degli widget, purtroppo Gridster non innesca eventi in tal senso
                        setTimeout(function(){
                            if($(window).width() < $('#wrapper-dashboard').width())
                            {
                                iframeW = '0';
                            }
                            else
                            {
                               iframeW = '1';
                            }

                            if($(window).height() < $('#wrapper-dashboard').height())
                            {
                                iframeH = '0';
                            }
                            else
                            {
                                iframeH = '1';
                            }

                            iframeCase = iframeW + iframeH;
                            
                            //console.log("iframeCase: " + iframeCase);
                            
                            switch(iframeCase)
                            {
                                case '00':
                                    widthRatio = parseInt($(window).width() + 17) / $('#wrapper-dashboard').width();
                                    heightRatio = parseInt($(window).height() + 17) / $('#wrapper-dashboard').height();
                                    $('body').css('overflow', 'hidden');

                                    $('#wrapper-dashboard').css("-ms-transform-origin", '0 0');
                                    $('#wrapper-dashboard').css("-webkit-transform-origin", '0 0');
                                    $('#wrapper-dashboard').css("-moz-transform-origin", '0 0');
                                    $('#wrapper-dashboard').css("transform-origin", '0 0');
                                    $('#wrapper-dashboard').css('-ms-transform', 'scale(' + widthRatio + ', ' + heightRatio + ')');
                                    $('#wrapper-dashboard').css('-webkit-transform', 'scale(' + widthRatio + ', ' + heightRatio + ')');
                                    $('#wrapper-dashboard').css('-moz-transform', 'scale(' + widthRatio + ', ' + heightRatio + ')');
                                    $('#wrapper-dashboard').css('transform', 'scale(' + widthRatio + ', ' + heightRatio + ')');
                                    break;
                                    
                                case '01':
                                    widthRatio = parseInt($(window).width() + 0) / $('#wrapper-dashboard').width();
                                    heightRatio = parseInt($(window).height() + 17) / $('#wrapper-dashboard').height();
                                    $('body').css('overflow', 'hidden');

                                    $('#wrapper-dashboard').css("-ms-transform-origin", '0 0');
                                    $('#wrapper-dashboard').css("-webkit-transform-origin", '0 0');
                                    $('#wrapper-dashboard').css("-moz-transform-origin", '0 0');
                                    $('#wrapper-dashboard').css("transform-origin", '0 0');
                                    $('#wrapper-dashboard').css('-ms-transform', 'scale(' + widthRatio + ', ' + heightRatio + ')');
                                    $('#wrapper-dashboard').css('-webkit-transform', 'scale(' + widthRatio + ', ' + heightRatio + ')');
                                    $('#wrapper-dashboard').css('-moz-transform', 'scale(' + widthRatio + ', ' + heightRatio + ')');
                                    $('#wrapper-dashboard').css('transform', 'scale(' + widthRatio + ', ' + heightRatio + ')');
                                    break;    

                                case '10':
                                    widthRatio = parseInt($(window).width() + 17) / $('#wrapper-dashboard').width();
                                    heightRatio = parseInt($(window).height() + 0) / $('#wrapper-dashboard').height();
                                    $('body').css('overflow', 'hidden');
                                    var gapX = parseInt(($(window).width() - $('#wrapper-dashboard').width())/2);
                                    $('#wrapper-dashboard').css("-ms-transform-origin", '0 0');
                                    $('#wrapper-dashboard').css("-webkit-transform-origin", '0 0');
                                    $('#wrapper-dashboard').css("-moz-transform-origin", '0 0');
                                    $('#wrapper-dashboard').css("transform-origin", '0 0');
                                    $('#wrapper-dashboard').css('-ms-transform', 'translateX(-' + gapX + 'px) scale(' + widthRatio + ', ' + heightRatio + ')');
                                    $('#wrapper-dashboard').css('-webkit-transform', 'translateX(-' + gapX + 'px) scale(' + widthRatio + ', ' + heightRatio + ')');
                                    $('#wrapper-dashboard').css('-moz-transform', 'translateX(-' + gapX + 'px) scale(' + widthRatio + ', ' + heightRatio + ')');
                                    $('#wrapper-dashboard').css('transform', 'translateX(-' + gapX + 'px) scale(' + widthRatio + ', ' + heightRatio + ')');
                                    break;
                                    
                                case '11':
                                    widthRatio = parseInt($(window).width() + 0) / $('#wrapper-dashboard').width();
                                    heightRatio = parseInt($(window).height() - 5) / $('#wrapper-dashboard').height();
                                    $('body').css('overflow', 'hidden');
                                    var gapX = parseInt(($(window).width() - $('#wrapper-dashboard').width())/2);
                                    $('#wrapper-dashboard').css("-ms-transform-origin", '0 0');
                                    $('#wrapper-dashboard').css("-webkit-transform-origin", '0 0');
                                    $('#wrapper-dashboard').css("-moz-transform-origin", '0 0');
                                    $('#wrapper-dashboard').css("transform-origin", '0 0');
                                    $('#wrapper-dashboard').css('-ms-transform', 'translateX(-' + gapX + 'px) scale(' + widthRatio + ', ' + heightRatio + ')');
                                    $('#wrapper-dashboard').css('-webkit-transform', 'translateX(-' + gapX + 'px) scale(' + widthRatio + ', ' + heightRatio + ')');
                                    $('#wrapper-dashboard').css('-moz-transform', 'translateX(-' + gapX + 'px) scale(' + widthRatio + ', ' + heightRatio + ')');
                                    $('#wrapper-dashboard').css('transform', 'translateX(-' + gapX + 'px) scale(' + widthRatio + ', ' + heightRatio + ')');
                                    break;    
                            }
                            $('#autofitAlert').hide();
                        }, 2500);
                    }
                    else
                    {
                        //Cambia logo se embedded in sito diverso dal dashboard manager
                        if(!document.referrer.includes(window.self.location.host)||((embedPreview === 'true')&&(document.referrer.includes(window.self.location.host))))
                        {
                            $('#page-wrapper #embedAutoLogoContainer').css("width", $('#container-widgets').css("width"));
                            $('#page-wrapper div.container-fluid div.footerLogos').hide();
                            $('#page-wrapper #embedAutoLogoContainer').css("background-color", $('#container-widgets').css("background-color"));
                            $('#page-wrapper #embedAutoLogoContainer').css("display", "flex");
                            $('#page-wrapper #embedAutoLogoContainer').css("align-items", "flex-start");
                            $('#page-wrapper #embedAutoLogoContainer').css("justify-content", "flex-start");
                            $('#page-wrapper #embedAutoLogoContainer').css("margin-left", "10px");
                        }
                        
                        //Autofit in modalità manuale
                        if('<?php echo $embedAutofit; ?>' === 'yes')
                        {
                            $('#wrapper-dashboard').css("width", $('#wrapper-dashboard').width() - 40);
                            $('#page-wrapper div.container-fluid').css('padding-left', '0px');
                            $('#page-wrapper div.container-fluid').css('padding-right', '0px');

                            var widthRatio, heightRatio, iframeW, iframeH, iframeCase = null;

                            //Il timeout serve per consentire a Gridster il caricamento degli widget, purtroppo Gridster non innesca eventi in tal senso
                            setTimeout(function(){
                                if($(window).width() < $('#wrapper-dashboard').width())
                                {
                                    iframeW = '0';
                                }
                                else
                                {
                                   iframeW = '1';
                                }

                                if($(window).height() < $('#wrapper-dashboard').height())
                                {
                                    iframeH = '0';
                                }
                                else
                                {
                                    iframeH = '1';
                                }

                                iframeCase = iframeW + iframeH;

                                switch(iframeCase)
                                {
                                    case '00':
                                        widthRatio = parseInt($(window).width() + 17) / $('#wrapper-dashboard').width();
                                        heightRatio = parseInt($(window).height() + 17) / $('#wrapper-dashboard').height();
                                        $('body').css('overflow', 'hidden');

                                        $('#wrapper-dashboard').css("-ms-transform-origin", '0 0');
                                        $('#wrapper-dashboard').css("-webkit-transform-origin", '0 0');
                                        $('#wrapper-dashboard').css("-moz-transform-origin", '0 0');
                                        $('#wrapper-dashboard').css("transform-origin", '0 0');
                                        $('#wrapper-dashboard').css('-ms-transform', 'scale(' + widthRatio + ', ' + heightRatio + ')');
                                        $('#wrapper-dashboard').css('-webkit-transform', 'scale(' + widthRatio + ', ' + heightRatio + ')');
                                        $('#wrapper-dashboard').css('-moz-transform', 'scale(' + widthRatio + ', ' + heightRatio + ')');
                                        $('#wrapper-dashboard').css('transform', 'scale(' + widthRatio + ', ' + heightRatio + ')');
                                        break;
                                        
                                    case '01':
                                        widthRatio = parseInt($(window).width() + 0) / $('#wrapper-dashboard').width();
                                        heightRatio = parseInt($(window).height() + 17) / $('#wrapper-dashboard').height();
                                        $('body').css('overflow', 'hidden');

                                        $('#wrapper-dashboard').css("-ms-transform-origin", '0 0');
                                        $('#wrapper-dashboard').css("-webkit-transform-origin", '0 0');
                                        $('#wrapper-dashboard').css("-moz-transform-origin", '0 0');
                                        $('#wrapper-dashboard').css("transform-origin", '0 0');
                                        $('#wrapper-dashboard').css('-ms-transform', 'scale(' + widthRatio + ', ' + heightRatio + ')');
                                        $('#wrapper-dashboard').css('-webkit-transform', 'scale(' + widthRatio + ', ' + heightRatio + ')');
                                        $('#wrapper-dashboard').css('-moz-transform', 'scale(' + widthRatio + ', ' + heightRatio + ')');
                                        $('#wrapper-dashboard').css('transform', 'scale(' + widthRatio + ', ' + heightRatio + ')');
                                        break;    

                                    case '10':
                                        widthRatio = parseInt($(window).width() + 17) / $('#wrapper-dashboard').width();
                                        heightRatio = parseInt($(window).height() + 0) / $('#wrapper-dashboard').height();
                                        $('body').css('overflow', 'hidden');
                                        var gapX = parseInt(($(window).width() - $('#wrapper-dashboard').width())/2);
                                        $('#wrapper-dashboard').css("-ms-transform-origin", '0 0');
                                        $('#wrapper-dashboard').css("-webkit-transform-origin", '0 0');
                                        $('#wrapper-dashboard').css("-moz-transform-origin", '0 0');
                                        $('#wrapper-dashboard').css("transform-origin", '0 0');
                                        $('#wrapper-dashboard').css('-ms-transform', 'translateX(-' + gapX + 'px) scale(' + widthRatio + ', ' + heightRatio + ')');
                                        $('#wrapper-dashboard').css('-webkit-transform', 'translateX(-' + gapX + 'px) scale(' + widthRatio + ', ' + heightRatio + ')');
                                        $('#wrapper-dashboard').css('-moz-transform', 'translateX(-' + gapX + 'px) scale(' + widthRatio + ', ' + heightRatio + ')');
                                        $('#wrapper-dashboard').css('transform', 'translateX(-' + gapX + 'px) scale(' + widthRatio + ', ' + heightRatio + ')');
                                        break;

                                    case '11':
                                        widthRatio = parseInt($(window).width() + 0) / $('#wrapper-dashboard').width();
                                        heightRatio = parseInt($(window).height() + 0) / $('#wrapper-dashboard').height();
                                        $('body').css('overflow', 'hidden');
                                        var gapX = parseInt(($(window).width() - $('#wrapper-dashboard').width())/2);
                                        $('#wrapper-dashboard').css("-ms-transform-origin", '0 0');
                                        $('#wrapper-dashboard').css("-webkit-transform-origin", '0 0');
                                        $('#wrapper-dashboard').css("-moz-transform-origin", '0 0');
                                        $('#wrapper-dashboard').css("transform-origin", '0 0');
                                        $('#wrapper-dashboard').css('-ms-transform', 'translateX(-' + gapX + 'px) scale(' + widthRatio + ', ' + heightRatio + ')');
                                        $('#wrapper-dashboard').css('-webkit-transform', 'translateX(-' + gapX + 'px) scale(' + widthRatio + ', ' + heightRatio + ')');
                                        $('#wrapper-dashboard').css('-moz-transform', 'translateX(-' + gapX + 'px) scale(' + widthRatio + ', ' + heightRatio + ')');
                                        $('#wrapper-dashboard').css('transform', 'translateX(-' + gapX + 'px) scale(' + widthRatio + ', ' + heightRatio + ')');
                                        break;    
                                }
                                $('#autofitAlert').hide();
                            }, 2500); 
                        }
                    }
                }
                else
                {
                    function sendCurrentPosition(position)
                    {
                        console.log("sendCurrentPosition OK");

                        $.ajax({
                            url: "../management/nrSendGpsProxy.php",
                            type: "POST",
                            data: {
                                httpRelativeUrl: encodeURI(globalDashboardTitle),
                                //username: "<?= $_REQUEST['username'] ?>",
                                dashboardTitle: encodeURI(globalDashboardTitle),
                                gpsData: JSON.stringify({
                                    latitude: position.coords.latitude,
                                    longitude: position.coords.longitude,
                                    accuracy:  position.coords.accuracy,
                                    altitude:  position.coords.altitude,
                                    altitudeAccuracy:  position.coords.altitudeAccuracy,
                                    heading:  position.coords.heading,
                                    speed:  position.coords.speed
                                })
                            },
                            async: true,
                            dataType: 'json',
                            success: function(data)
                            {
                                console.log("Data sent to test GPS OK");
                                console.log(JSON.stringify(data));
                            },
                            error: function(errorData)
                            {
                                console.log("Data sent to test GPS KO");
                                console.log(JSON.stringify(errorData));
                            }
                        });
                    }
                    
                    function sendCurrentPositionError(obj)
                    {
                        console.log("Get current position KO: " + obj.message);
                    }
                    
                    <?php
                        $genFileContent = parse_ini_file("../conf/environment.ini");
                        $nodeEmittersApiContent = parse_ini_file("../conf/nodeEmittersApi.ini");
                        $myGpsActive = $nodeEmittersApiContent["gpsActive"][$genFileContent['environment']['value']];
                        $myGpsPeriod = $nodeEmittersApiContent["gpsPeriod"][$genFileContent['environment']['value']];
                        echo 'myGpsActive = "' . $myGpsActive . '";';
                        echo 'myGpsPeriod = ' . $myGpsPeriod . ';';
                    ?>
                                                                                                                    
                    if(myGpsActive === 'yes')
                    {
                        myGpsInterval = setInterval(function(){
                            if(navigator.geolocation) 
                            {
                                navigator.geolocation.getCurrentPosition(sendCurrentPosition, sendCurrentPositionError);
                            } 
                            else 
                            { 
                                console.log("Navigator not available");
                            }
                        }, parseInt(parseInt(myGpsPeriod)*1000))
                    }
                    else
                    {
                        console.log("Navigator not active");
                    }
                }
                
            }
            
            function authUser()
            {
                $.ajax({
                    url: "../management/getDashboardData.php",
                    //Lasciare il vecchio refuso "iddasboard" per non cambiare i link
                    data: 
                    { 
                        dashboardId: <?= base64_decode($_GET['iddasboard']) ?>,
                        username: $("#username").val(),
                        password: $("#password").val(),
                        loggedUserFirstAttempt: loggedUserFirstAttempt
                    },
                    type: "GET",
                    async: true,//LASCIARLA ASINCRONA.
                    dataType: 'json',
                    success: function (response) 
                    {  
                        switch(response.visibility)
                        {
                            case 'public':
                                $('body').removeClass("dashboardViewBodyAuth");
                                $('#authFormDarkBackground').hide();
                                $('#authFormContainer').hide();
                                $("#dashboardViewMainContainer").show();
                                loadDashboard(response.dashboardParams, response.dashboardWidgets);
                                break;

                            case 'author': case 'restrict':
                                $('body').addClass("dashboardViewBodyAuth");
                                $('#authFormDarkBackground').show();
                                $('#authFormContainer').show();
                                switch(response.detail)
                                {
                                    case "credentialsMissing":
                                        $("#dashboardViewMainContainer").hide();
                                        if(firstLoad === false)
                                        {
                                            $("#authFormMessage").html("Credentials missing");
                                        }
                                        else
                                        {
                                            $("#authFormMessage").html("");
                                            firstLoad = false;
                                        }
                                        $("#authBtn").click(authUser);
                                        break;
                                        
                                    case "checkUserQueryKo":
                                        //Fallimento query controllo presenza utente
                                        $("#dashboardViewMainContainer").hide();
                                        $("#authFormMessage").html("Failure during DB query to check user: please try again");
                                        $("#authBtn").click(authUser);
                                        break;
                                        
                                    case "checkLoggedUserQueryKo":
                                        //Fallimento query controllo presenza utente
                                        $("#dashboardViewMainContainer").hide();
                                        $("#authFormMessage").html("Failure during DB query to check user logged to main application: please try again");
                                        $("#authBtn").click(authUser);
                                        break;
                                        
                                    case "checkLoggedViewUserQueryKo":
                                        //Fallimento query controllo presenza utente
                                        $("#dashboardViewMainContainer").hide();
                                        $("#authFormMessage").html("Failure during DB query to check user logged to dashboard view: please try again");
                                        $("#authBtn").click(authUser);
                                        break;     
                                        
                                    case "userNotRegistered":
                                        $("#dashboardViewMainContainer").hide();
                                        $("#authFormMessage").html("User not registered or wrong username / password");
                                        $("#authBtn").click(authUser);
                                        break;
                                        
                                    case "Ok": 
                                        $('body').removeClass("dashboardViewBodyAuth");
                                        $('#authFormDarkBackground').hide();
                                        $('#authFormContainer').hide();
                                        $("#dashboardViewMainContainer").show();
                                        $('#hiddenUsername').val($("#username").val());
                                        loadDashboard(response.dashboardParams, response.dashboardWidgets);
                                        
                                        if(response.context === "View")
                                        {
                                            $("#viewLogoutBtn").show();
                                            $("#viewLogoutBtn").click(function(){
                                                event.preventDefault();
                                                $("#logoutViewModal").modal('show');
                                            });

                                            /*$("#confirmLogoutBtn").click(function(event){
                                                $.ajax({
                                                    url: "../management/sessionUpdate.php",
                                                    data: {
                                                      sessionAction: 'closeViewSession',
                                                      dashboardId: <?= base64_decode($_GET['iddasboard']) ?>
                                                    },
                                                    type: "POST",
                                                    async: false,
                                                    dataType: 'json',
                                                    success: function (data) 
                                                    {
                                                        switch(data.detail)
                                                        {
                                                            case "Ok":
                                                                $("#logoutViewModalFooter").hide();
                                                                $("#logoutViewModalMsg").hide();
                                                                $("#logoutViewModalOk").show();
                                                                setTimeout(function(){
                                                                    $("#logoutViewModal").modal('hide');
                                                                    location.reload();
                                                                }, 2000);
                                                                break;

                                                            case "Ko":
                                                                $("#logoutViewModalMsg").hide();
                                                                $("#logoutViewModalFooter").hide();
                                                                $("#logoutViewModalKo").show();
                                                                setTimeout(function(){
                                                                    $("#logoutViewModal").modal('hide');
                                                                    $("#logoutViewModalKo").hide();
                                                                    $("#logoutViewModalMsg").show();
                                                                    $("#logoutViewModalFooter").show();
                                                                }, 2000);
                                                                break;
                                                        }
                                                    },
                                                    error: function (data)
                                                    {
                                                        $("#logoutViewModalMsg").hide();
                                                        $("#logoutViewModalFooter").hide();
                                                        $("#logoutViewModalKo").show();
                                                        setTimeout(function(){
                                                            $("#logoutViewModal").modal('hide');
                                                            $("#logoutViewModalKo").hide();
                                                            $("#logoutViewModalMsg").show();
                                                            $("#logoutViewModalFooter").show();
                                                        }, 2000);
                                                        console.log("Error");
                                                        console.log(JSON.stringify(data));
                                                    }
                                                });
                                            });*/
                                        }
                                    break;

                                case "Ko": 
                                    $("#dashboardViewMainContainer").hide();
                                    $("#authFormMessage").html("User not allowed to see this dashboard");
                                    $('body').addClass("dashboardViewBodyAuth");
                                    $('#authFormDarkBackground').show();
                                    $('#authFormContainer').show();
                                    $("#authBtn").click(authUser);        
                                    break;

                                case "loggedUserKo": 
                                    loggedUserFirstAttempt = false;
                                    $("#dashboardViewMainContainer").hide();
                                    $("#authFormMessage").html("Logged user not allowed to see this dashboard");
                                    $('body').addClass("dashboardViewBodyAuth");
                                    $('#authFormDarkBackground').show();
                                    $('#authFormContainer').show();
                                    $("#authBtn").click(authUser);        
                                    break;

                                case "loggedViewUserKo": 
                                    loggedUserFirstAttempt = false;
                                    $("#dashboardViewMainContainer").hide();
                                    $("#authFormMessage").html("User logged to dashboard view not allowed to see this dashboard");
                                    $('body').addClass("dashboardViewBodyAuth");
                                    $('#authFormDarkBackground').show();
                                    $('#authFormContainer').show();
                                    $("#authBtn").click(authUser);        
                                    break;    
                            }
                            break; 
                        }
                    },
                    error: function (data)
                    {
                        $("#dashboardViewMainContainer").hide();
                        $("#authFormContainer").hide();
                        $("#getVisibilityError").show();
                        console.log("Error: " + JSON.stringify(data));
                    }
                }); 
            }
            //Fine definizioni di funzione
            
            //Main
            authUser();
        });
    </script>
</head>

<body>
    <?php include "../management/sessionExpiringPopup.php" ?>
    
    <div id="dashboardViewMainContainer" class="container-fluid">
        <nav id="dashboardViewHeaderContainer" class="navbar navbar-fixed-top" role="navigation">
            <div id="fullscreenBtnContainer" data-status="normal">
                <span>
                    <i id="fullscreenButton" class="fa fa-window-maximize"></i>
                    <i id="restorescreenButton" class="fa fa-window-restore"></i>            
                </span>
            </div>
            <div id="dashboardViewTitleAndSubtitleContainer">
                <div id="dashboardTitle">
                    <span></span>
                </div>
                <div id="dashboardSubtitle">
                    <span></span>
                </div>            
            </div>
            <div id="headerLogo">
                <img id="headerLogoImg"/>
            </div>
            <div id="clock">
                <span id="tick2"><?php include('../widgets/time.php'); ?></span>
            </div>
        </nav>
        
        <div id="dashboardViewWidgetsContainer" class="gridster">
            <ul id="gridsterUl"></ul>            
        </div>
        
        
        <div id="logos" class="footerLogos">
            <!--<a title="Logout from this dashboard" href="#" class="footerLogo"><i id="viewLogoutBtn" class="fa fa-sign-out"></i></a>-->
            <a title="Disit" href="https://www.disit.org" target="_new" class="footerLogo"><img src="https://dashboard.km4city.org/img/applicationLogos/disitLogoTransparent.png" /></a>
            <div id="embedAutoLogoContainer">
                <a title="Km4City" href="https://www.km4city.org" target="_new"><img id="embedAutoLogo" src="../img/PoweredByKm4City1Line.png" /></a>
            </div>
        </div>
    </div>
    
    <div id="authFormDarkBackground">
        <div class="row">
            <div class="col-xs-12 centerWithFlex" id="loginMainTitle">Dashboard Management System</div>
        </div>
        
        <div class="row">
            <div id="authFormContainer" class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
                <div class="col-xs-12" id="loginFormTitle" style="margin-top: 15px">
                   Restricted access dashboard
                </div>
                <form id="authForm" class="form-signin" role="form" method="post" action="">
                    <div class="col-xs-12" id="loginFormBody">
                        <div class="col-xs-12 modalCell">
                            <div class="modalFieldCnt">
                                <input type="text" class="modalInputTxt" id="username" name="username" required> 
                            </div>
                            <div class="modalFieldLabelCnt">Username</div>
                        </div>
                        <div class="col-xs-12 modalCell">
                            <div class="modalFieldCnt">
                                <input type="password" class="modalInputTxt" id="password" name="password" required> 
                            </div>
                            <div class="modalFieldLabelCnt">Password</div>
                        </div>
                        <div class="col-xs-12 modalCell">
                            <div id="authFormMessage"></div>
                        </div>
                        <input type="hidden" id="hiddenUsername" name="hiddenUsername"> 
                    </div>
                <div class="col-xs-12 centerWithFlex" id="loginFormFooter" style="margin-bottom: 15px">
                    <button type="reset" id="loginCancelBtn" class="btn cancelBtn" data-dismiss="modal">Reset</button>
                    <button type="button" id="authBtn" name="login" class="btn confirmBtn internalLink">Login</button>
                </div>
                </form>
            </div>
        </div>
    </div> 
    
    <!-- MODALI -->
    <!-- modale informazioni generali del widget -->
    <div class="modal fade" tabindex="-1" id="dialog-information-widget" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document" id="info01"> 
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="titolo_info">Descrizione:</h4>
                </div>
                <div class="modal-body">
                    <form id="form-information-widget" class="form-horizontal" name="form-information-widget" role="form" method="post" action="" data-toggle="validator">
                        <div id="contenuto_infomazioni"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modale informazioni campi widget -->
    <div class="modal fade" tabindex="-1" id="modalWidgetFieldsInfo" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document" id="info01"> 
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="modalWidgetFieldsInfoTitle"></h4>
                </div>
                <div class="modal-body">
                    <form id="modalWidgetFieldsInfoForm" class="form-horizontal" name="modalWidgetFieldsInfoForm" role="form" method="post" action="" data-toggle="validator">
                        <div id="modalWidgetFieldsInfoContent"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div> 
    
    <!-- Modale di conferma logout dashboard -->
    <div class="modal fade" id="logoutViewModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modalHeader centerWithFlex">
              Close this dashboard
            </div>
            <div id="delDsModalBody" class="modal-body modalBody">
                <div class="row" id="logoutViewModalMsg">
                    <div class="col-xs-12 modalCell">
                        <div class="modalDelMsg col-xs-12 centerWithFlex">
                            Do you want to confirm logout from this dashboard? 
                        </div>
                        <div class="modalDelObjName col-xs-12 centerWithFlex" id="delDsName"></div> 
                    </div>
                </div>
                <div class="row" id="logoutViewModalOk">
                    <div class="col-xs-12 centerWithFlex">Logout correctly executed</div>
                    <div class="col-xs-12 centerWithFlex"><i class="fa fa-thumbs-o-up" style="font-size:36px"></i></div>
                </div>
                <div class="row" id="logoutViewModalKo">
                    <div class="col-xs-12 centerWithFlex">Logout not possibile, please try again</div>
                    <div class="col-xs-12 centerWithFlex"><i class="fa fa-thumbs-o-down" style="font-size:36px"></i></div>
                </div>
            </div>
            <div id="logoutViewModalFooter" class="modal-footer">
              <button type="button" id="discardLogoutBtn" class="btn cancelBtn" data-dismiss="modal">Cancel</button>
              <button type="button" id="confirmLogoutBtn" class="btn confirmBtn internalLink">Confirm</button>
            </div>
          </div>
        </div>
    </div>
    
    <!-- Modale impossibilità di apertura link in nuovo tab per widgetExternalContent -->
    <div class="modal fade" tabindex="-1" id="newTabLinkOpenImpossibile" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document"> 
            <div class="modal-content">
                <div class="modal-header centerWithFlex">
                    <h4 class="modal-title">External content</h4>
                </div>
                <div class="modal-body">
                    <div id="newTabLinkOpenImpossibileMsg"></div>
                    <div id="newTabLinkOpenImpossibileIcon">
                         <i class="fa fa-frown-o"></i>           
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modale cambio stato evacuation plan -->
        <div class="modal fade" tabindex="-1" id="modalChangePlanStatus" role="dialog" aria-labelledby="myModalLabel">
            <div id="modalChangePlanStatusDialog" class="modal-dialog modal-lg" role="document"> 
                <div class="modal-content">
                    <div id="modalChangePlanStatusModalTitle" class="modal-header centerWithFlex">
                        evacuation plan status management
                    </div>
                    <div id="modalChangePlanStatusMain" class="modal-body container-fluid">
                        <div class="row">
                            <div class="col-sm-6 centerWithFlex modalChangePlanStatusLabel">
                                plan identifier
                            </div> 
                            <div class="col-sm-6 centerWithFlex modalChangePlanStatusLabel">
                                current approval status
                            </div>
                        </div>
                        <div class="row">
                           <div class="col-sm-6 centerWithFlex" id="modalChangePlanStatusTitle" ></div> 
                           <div class="col-sm-4 col-sm-offset-1 centerWithFlex" id="modalChangePlanStatusStatus"></div>
                        </div>
                       
                        <div class="row">
                           <div class="col-sm-6 col-sm-offset-3 centerWithFlex modalChangePlanStatusLabel">
                               new approval status 
                           </div> 
                        </div>
                        <div class="row">
                           <div class="col-sm-4 col-sm-offset-4 centerWithFlex">
                               <select class="form-control" id="modalChangePlanStatusSelect" name="modalChangePlanStatusSelect" required></select> 
                           </div> 
                        </div>
                    </div>
                    <div id="modalChangePlanStatusWait" class="modal-body container-fluid">
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-3 centerWithFlex">
                                updating status, please wait
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-3 centerWithFlex">
                                <i class="fa fa-spinner fa-spin" style="font-size:84px"></i>
                            </div> 
                        </div>
                    </div>
                    <div id="modalChangePlanStatusOk" class="modal-body container-fluid">
                        <div class="row">
                            <div class="col-sm-10 col-sm-offset-1 centerWithFlex">
                                status successfully updated
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-3 centerWithFlex">
                                <i class="fa fa-thumbs-o-up" style="font-size:84px"></i>
                            </div> 
                        </div>
                    </div>
                    <div id="modalChangePlanStatusKo" class="modal-body container-fluid">
                        <div class="row">
                            <div class="col-sm-12 centerWithFlex">
                                error while trying to send new status to server, please try again
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-3 centerWithFlex">
                                <i class="fa fa-thumbs-down" style="font-size:84px"></i>
                            </div> 
                        </div>
                    </div>
                    
                    <input type="hidden" id="modalChangePlanStatusPlanId" />
                    <input type="hidden" id="modalChangePlanStatusCurrentStatus" />
                   
                    <div id="modalChangePlanStatusFooter" class="modal-footer centerWithFlex">
                       <button type="button" class="btn btn-secondary" id="modalChangePlanStatusCancelBtn">cancel</button>
                       <button type="button" class="btn btn-primary" id="modalChangePlanStatusConfirmBtn">confirm</button>
                    </div>
                </div>
            </div>
        </div>
    
    <div id="getVisibilityError">
        <div id="wrapper">
            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.html">Dashboard management system</a>
                </div>
            </nav>
        </div>   
        <br/><br/><br/><br/>
        <h1>Error!</h1>
        <p>Error while trying to get dashboard visibility: please try again</p>
    </div>
     
    <div id="autofitAlert">
        <div class="row">
            <div id="autofitAlertMsgContainer" class="col-xs-12">
               Auto refit in progress, please wait                    
            </div>                     
        </div>
        <div class="row">
            <div id="autofitAlertIconContainer" class="col-xs-12">
               <i class="fa fa-circle-o-notch fa-spin"></i>                    
            </div>                     
        </div>                        
    </div>
</body>
</html>

