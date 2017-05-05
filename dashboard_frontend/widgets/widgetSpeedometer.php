<?php
/* Dashboard Builder.
   Copyright (C) 2016 DISIT Lab http://www.disit.org - University of Florence

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
?>

<script type="text/javascript">
    var colors = {
      GREEN: '#008800',
      ORANGE: '#FF9933',
      LOW_YELLOW: '#ffffcc',
      RED: '#FF0000'
    };


    $(document).ready(function <?= $_GET['name'] ?>(firstLoad) 
    {
        <?php
            $titlePatterns = array();
            $titlePatterns[0] = '/_/';
            $titlePatterns[1] = '/\'/';
            $replacements = array();
            $replacements[0] = ' ';
            $replacements[1] = '&apos;';
            $title = $_GET['title'];
        ?> 
                
        var hostFile = "<?= $_GET['hostFile'] ?>";
        var widgetName = "<?= $_GET['name'] ?>";
        var divContainer = $("#<?= $_GET['name'] ?>_content");
        var widgetContentColor = "<?= $_GET['color'] ?>";
        var widgetHeaderColor = "<?= $_GET['frame_color'] ?>";
        var widgetHeaderFontColor = "<?= $_GET['headerFontColor'] ?>";
        var nome_wid = "<?= $_GET['name'] ?>_div";
        var linkElement = $('#<?= $_GET['name'] ?>_link_w');
        var color = '<?= $_GET['color'] ?>';
        var fontSize = "<?= $_GET['fontSize'] ?>";
        var fontColor = "<?= $_GET['fontColor'] ?>";
        var timeToReload = <?= $_GET['freq'] ?>;
        var widgetPropertiesString, widgetProperties, thresholdObject, infoJson, styleParameters, metricType, metricData, pattern, totValues, shownValues, 
            descriptions, udm, threshold, thresholdEval, stopsArray, delta, deltaPerc, seriesObj, dataObj, pieObj, legendLength,
            rangeMin, rangeMax, widgetParameters, paneObj, limSup1, limSup2, minGauge, maxGauge, shownValue, thicknessVal, plotOptionsObj, sizeRowsWidget, alarmSet = null;
        var metricId = "<?= $_GET['metric'] ?>";
        var elToEmpty = $("#<?= $_GET['name'] ?>_chartContainer");
        var url = "<?= $_GET['link_w'] ?>";
        var chartColors = new Array();
        
        if(url === "null")
        {
            url = null;
        }
        
        //Definizioni di funzione specifiche del widget
        /*Restituisce il JSON delle soglie se presente, altrimenti NULL*/
        function getThresholdsJson()
        {
            var thresholdsJson = null;
            if(jQuery.parseJSON(widgetProperties.param.parameters !== null))
            {
                thresholdsJson = widgetProperties.param.parameters; 
            }
            
            return thresholdsJson;
        }
        
        /*Restituisce il JSON delle info se presente, altrimenti NULL*/
        function getInfoJson()
        {
            var infoJson = null;
            if(jQuery.parseJSON(widgetProperties.param.infoJson !== null))
            {
                infoJson = jQuery.parseJSON(widgetProperties.param.infoJson); 
            }
            
            return infoJson;
        }
        
        /*Restituisce il JSON delle info se presente, altrimenti NULL*/
        function getStyleParameters()
        {
            var styleParameters = null;
            if(jQuery.parseJSON(widgetProperties.param.styleParameters !== null))
            {
                styleParameters = jQuery.parseJSON(widgetProperties.param.styleParameters); 
            }
            
            return styleParameters;
        }
        //Fine definizioni di funzione 
        
        setWidgetLayout(hostFile, widgetName, widgetContentColor, widgetHeaderColor, widgetHeaderFontColor);
        if(firstLoad === false)
        {
            showWidgetContent(widgetName);
        }
        else
        {
            setupLoadingPanel(widgetName, widgetContentColor, firstLoad);
        }
        addLink(widgetName, url, linkElement, divContainer);
        $("#<?= $_GET['name'] ?>_titleDiv").html("<?= preg_replace($titlePatterns, $replacements, $title) ?>");
        widgetProperties = getWidgetProperties(widgetName);
        
        if((widgetProperties !== null) && (widgetProperties !== ''))
        {
            //Inizio eventuale codice ad hoc basato sulle proprietà del widget
            styleParameters = getStyleParameters();//Restituisce null finché non si usa il campo per questo widget
            widgetParameters = JSON.parse(widgetProperties.param.parameters);
            udm = widgetProperties.param.udm;
            sizeRowsWidget = parseInt(widgetProperties.param.size_rows);
            
            if(widgetParameters !== null)
            {
                if((widgetParameters.rangeMin !== null) && (widgetParameters.rangeMin !== "") && (typeof widgetParameters.rangeMin !== "undefined"))
                {
                    rangeMin = widgetParameters.rangeMin;
                }
                else
                {
                    rangeMin = null;
                }

                if((widgetParameters.rangeMax !== null) && (widgetParameters.rangeMax !== "") && (typeof widgetParameters.rangeMax !== "undefined"))
                {
                    rangeMax = widgetParameters.rangeMax;
                }
                else
                {
                    rangeMax = null;
                }

                if((widgetParameters.color1 !== null) && (widgetParameters.color1 !== "") && (typeof widgetParameters.color1 !== "undefined")) 
                {
                    chartColors[0] = widgetParameters.color1;
                }
                else
                {
                    chartColors[0] = colors.GREEN;
                }
                
                if((widgetParameters.color2 !== null) && (widgetParameters.color2 !== "") && (typeof widgetParameters.color2 !== "undefined")) 
                {
                    chartColors[1] = widgetParameters.color2;
                }
                else
                {
                    chartColors[1] = colors.ORANGE; 
                }

                if((widgetParameters.color3 !== null) && (widgetParameters.color3 !== "") && (typeof widgetParameters.color3 !== "undefined")) 
                {
                    chartColors[2] = widgetParameters.color3;
                }
                else
                {
                    chartColors[2] = colors.RED; 
                }

                if((widgetParameters.limitSup1 !== null) && (widgetParameters.limitSup1 !== "") && (typeof widgetParameters.limitSup1 !== "undefined")) 
                {
                    limSup1 = widgetParameters.limitSup1;
                }
                else
                {
                    limSup1 = null;
                }

                if((widgetParameters.limitSup2 !== null) && (widgetParameters.limitSup2 !== "") && (typeof widgetParameters.limitSup2 !== "undefined")) 
                {
                    limSup2 = widgetParameters.limitSup2;
                }
                else
                {
                    limSup2 = null;
                }
            }
            else 
            {
                chartColors[0] = colors.GREEN; 
                chartColors[1] = colors.ORANGE; 
                chartColors[2] = colors.RED; 
                limSup1 = null;
                limSup2 = null;
                rangeMin = null;
                rangeMax = null;
            }
            //Fine eventuale codice ad hoc basato sulle proprietà del widget
            
            metricData = getMetricData(metricId);
            if(metricData !== null)
            {
                if(metricData.data[0] !== 'undefined')
                {
                    if(metricData.data.length > 0)
                    {
                        pattern = /Percentuale\//;
                        metricType = metricData.data[0].commit.author.metricType;
                        threshold = parseInt(metricData.data[0].commit.author.threshold);
                        thresholdEval = metricData.data[0].commit.author.thresholdEval;
                        var seriesDat = [];

                        if(pattern.test(metricType))
                        {
                            minGauge = 0;
                            maxGauge = parseInt(metricType.substring(12));
                            if(metricData.data[0].commit.author.quant_perc1 !== null)
                            {
                                shownValue = parseFloat(parseFloat(metricData.data[0].commit.author.quant_perc1).toFixed(1));
                            }
                        }
                        else
                        {
                            switch(metricType)
                            {
                                case "Intero":
                                    if(metricData.data[0].commit.author.value_num !== null)
                                    {
                                        shownValue = parseInt(metricData.data[0].commit.author.value_num);
                                    }
                                    
                                    if((rangeMin !== null) && (rangeMax !== null))
                                    {
                                        minGauge = parseInt(rangeMin);
                                        maxGauge = parseInt(rangeMax);
                                    }
                                    else
                                    {
                                        minGauge = 0;
                                        maxGauge = shownValue;
                                    }
                                    break;

                                case "Float":
                                    if(metricData.data[0].commit.author.value_num !== null)
                                    {
                                        shownValue = parseFloat(parseFloat(metricData.data[0].commit.author.value_num).toFixed(1));
                                    }
                                    
                                    if((rangeMin !== null) && (rangeMax !== null))
                                    {
                                        minGauge = parseInt(rangeMin);
                                        maxGauge = parseInt(rangeMax);
                                    }
                                    else
                                    {
                                        minGauge = 0;
                                        maxGauge = shownValue;
                                    }
                                    break;

                                case "Percentuale":
                                    minGauge = 0;
                                    maxGauge = 100;
                                    if(metricData.data[0].commit.author.value_perc1 !== null)
                                    {
                                        udm = "%";
                                        shownValue = parseFloat(parseFloat(metricData.data[0].commit.author.value_perc1).toFixed(1));
                                    }
                                    break;

                                default:
                                    break;
                            }
                        }

                        if(shownValue > maxGauge)
                        {
                            maxGauge = shownValue; 
                        }
                        if(shownValue < minGauge)
                        {
                            minGauge = shownValue; 
                        }

                        if(sizeRowsWidget <= 4)
                        {
                            thicknessVal = 7;
                        }
                        else
                        {
                            thicknessVal = 10;
                        }

                        //Controllo tipo metrica non compatibile col widget
                        if((shownValue !== null) && (minGauge !== null) && (maxGauge !== null))
                        {
                            if((threshold === null) || (thresholdEval === null))
                            {
                                //In questo caso non mostriamo soglia d'allarme.
                                threshold = 0;

                                //Per qualsiasi combinazione non prevista impostiamo un unico colore (verde)
                                plotBandSet = [{
                                            from: minGauge,
                                            to: maxGauge,
                                            color: chartColors[0],
                                            thickness: thicknessVal
                                        }];

                                if((limSup1 === null) && (limSup2 === null))
                                {
                                    plotBandSet = [{
                                            from: minGauge,
                                            to: maxGauge,
                                            color: chartColors[0],
                                            thickness: thicknessVal
                                        }];
                                }
                                else
                                {
                                    if(limSup1 !== null)
                                    {
                                        plotBandSet = [
                                        {  
                                            from: minGauge,
                                            to: limSup1,
                                            color: chartColors[0],
                                            thickness: thicknessVal
                                        },
                                        {  
                                            from: limSup1,
                                            to: maxGauge,
                                            color: chartColors[1],
                                            thickness: thicknessVal
                                        }
                                        ];
                                    }
                                    if((limSup1 !== null) && (limSup2 !== null))
                                    {
                                        plotBandSet = [
                                        {  
                                            from: minGauge,
                                            to: limSup1,
                                            color: chartColors[0],
                                            thickness: thicknessVal
                                        },
                                        {  
                                            from: limSup1,
                                            to: limSup2,
                                            color: chartColors[1],
                                            thickness: thicknessVal
                                        },
                                        {  
                                            from: limSup2,
                                            to: maxGauge,
                                            color: chartColors[2],
                                            thickness: thicknessVal
                                        }        
                                        ];
                                    }
                                }
                            }
                            else
                            {
                                //Distinguiamo in base all'operatore di confronto
                                switch(thresholdEval)
                                {
                                    //Allarme attivo se il valore attuale è sotto la soglia
                                    case '<':
                                        if(shownValue < threshold)
                                        {
                                           //Allarme
                                           //alarmSet = true;
                                        }

                                        plotBandSet = [
                                            {
                                                from: minGauge,
                                                to: threshold,
                                                color: chartColors[2],
                                                thickness: thicknessVal
                                            },        
                                            {
                                                from: threshold,
                                                to: maxGauge,
                                                color: chartColors[0],
                                                thickness: thicknessVal
                                            }
                                        ];
                                        break;

                                    //Allarme attivo se il valore attuale è sopra la soglia
                                    case '>':
                                        if(shownValue > threshold)
                                        {
                                           //Allarme
                                           //alarmSet = true;
                                        }

                                        plotBandSet = [
                                            {
                                                from: minGauge,
                                                to: threshold,
                                                color: chartColors[0],
                                                thickness: thicknessVal
                                            }, 
                                            {
                                                from: threshold,
                                                to: maxGauge,
                                                color: chartColors[2],
                                                thickness: thicknessVal
                                            }
                                        ];
                                        break;

                                    //Allarme attivo se il valore attuale è uguale alla soglia (errore sui float = 0.01%)
                                    case '=':
                                        delta = Math.abs(shownValue - threshold);
                                        deltaPerc = ((delta / threshold)*100);

                                        if(deltaPerc <= 0.01)
                                        {
                                            //Allarme
                                            //alarmSet = true;
                                        }

                                        var incAlr = parseInt(threshold*0.05);
                                        var infAlr = threshold-incAlr;
                                        var supAlr = parseInt(threshold) + incAlr;

                                        plotBandSet = [
                                            {
                                                from: minGauge,
                                                to: infAlr,
                                                color: chartColors[0],
                                                thickness: thicknessVal
                                            },
                                            {
                                                from: infAlr,
                                                to: supAlr,
                                                color: chartColors[2],
                                                thickness: thicknessVal
                                            },
                                            {
                                                from: supAlr,
                                                to: maxGauge,
                                                color: chartColors[0],
                                                thickness: thicknessVal
                                            }
                                        ];
                                        break;    

                                    //Non gestiamo altri operatori 
                                    default:
                                        threshold = 0;
                                        plotBandSet = [{
                                            from: minGauge,
                                            to: maxGauge,
                                            color: chartColors[0],
                                            thickness: thicknessVal
                                        }];
                                        break;
                                 }
                            }

                            if(sizeRowsWidget <= 4)
                            {
                                //Speedo piccolo (anche in caso di valore errato su DB)
                                plotOptionsObj = {
                                    gauge : {
                                        dial : {
                                            baseWidth: 2,
                                            topWidth: 1
                                        }
                                    }
                                };

                                paneObj = [{
                                    startAngle: -135,
                                    endAngle: 135,
                                    size: '112%',
                                    center: ['50%', '52%'],
                                    background: [{
                                            backgroundColor: {
                                                linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                                                stops: [
                                                    [0, '#FFF'],
                                                    [1, '#333']
                                                ]
                                            },
                                            borderWidth: 0,
                                            outerRadius: '100%'
                                        }, {
                                            backgroundColor: {
                                                linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                                                stops: [
                                                    [0, '#333'],
                                                    [1, '#FFF']
                                                ]
                                            },
                                            borderWidth: 0,
                                            outerRadius: '100%'
                                        }, {
                                            // default background
                                        }, {
                                            backgroundColor: '#DDD',
                                            borderWidth: 0,
                                            outerRadius: '100%',
                                            innerRadius: '100%'
                                        }]
                                }];

                                dataLabelsObj = {
                                    enabled: true,
                                    style: {
                                        fontWeight: 'bold',
                                        fontSize: '12px',
                                        "text-shadow": "1px 1px 1px rgba(0,0,0,0.20)",
                                        "textOutline": "1px 1px contrast",
                                        fontFamily: 'Verdana'
                                    },
                                    padding: 1,
                                    borderWidth: 0,
                                    y: 60,
                                    formatter: function () {
                                        var val = this.y;
                                        return val;
                                    }
                                };

                                udmObj = {
                                        text: udm, 
                                        y: 60,
                                        style: {
                                            fontWeight:'normal',
                                            fontSize: '15px',
                                            color: 'black',
                                            "text-shadow": "1px 1px 1px rgba(0,0,0,0.20)",
                                            "textOutline": "1px 1px contrast",
                                            fontFamily: 'Verdana'
                                        },
                                    };

                                yAxisObj = {
                                    min: minGauge,
                                    max: maxGauge,
                                    minorTickInterval: 'auto',
                                    minorTickWidth: 1,
                                    minorTickLength: 5,
                                    minorTickPosition: 'inside',
                                    minorTickColor: '#666',
                                    tickPixelInterval: 30,
                                    tickWidth: 2,
                                    tickPosition: 'inside',
                                    tickLength: 7,
                                    //tickInterval: 2,
                                    tickColor: '#666',
                                    labels: {
                                        step: 2,
                                        rotation: 'auto',
                                        distance: -20,
                                        style: {
                                            "text-shadow": "1px 1px 1px rgba(0,0,0,0.20)",
                                            "textOutline": "1px 1px contrast",
                                            fontFamily: 'Verdana'
                                        }
                                    },
                                    title: udmObj,
                                    plotBands: plotBandSet 
                                }; 
                            }
                            else
                            {
                                //Speedo grande (anche in caso di valore errato su DB)
                                plotOptionsObj = {
                                    gauge : {
                                        dial : {
                                            baseWidth: 3,
                                            topWidth: 1
                                        }
                                    }
                                };

                                paneObj = [{
                                    startAngle: -135,
                                    endAngle: 135,
                                    size: '97%',
                                    center: ['50%', '51%'],
                                    background: [{
                                            backgroundColor: {
                                                linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                                                stops: [
                                                    [0, '#FFF'],
                                                    [1, '#333']
                                                ]
                                            },
                                            borderWidth: 0,
                                            outerRadius: '100%'
                                        }, {
                                            backgroundColor: {
                                                linearGradient: {x1: 0, y1: 0, x2: 0, y2: 1},
                                                stops: [
                                                    [0, '#333'],
                                                    [1, '#FFF']
                                                ]
                                            },
                                            borderWidth: 0,
                                            outerRadius: '100%'
                                        }, {
                                            // default background
                                        }, {
                                            backgroundColor: '#DDD',
                                            borderWidth: 0,
                                            outerRadius: '100%',
                                            innerRadius: '100%'
                                        }]
                                }];

                                dataLabelsObj = {
                                    enabled: true,
                                    style: {
                                        fontSize: '20px',
                                        "text-shadow": "1px 1px 1px rgba(0,0,0,0.20)",
                                        "textOutline": "1px 1px contrast",
                                        fontFamily: 'Verdana'
                                    },
                                    y: 85,
                                    borderWidth: 0,
                                    formatter: function () {
                                        var val = this.y;
                                        return val;
                                    }
                                };

                                udmObj = {
                                        text: udm, 
                                        y: 120,
                                        style: {
                                            fontWeight:'bold',
                                            fontSize: '18px',
                                            color: 'black',
                                            "text-shadow": "1px 1px 1px rgba(0,0,0,0.20)",
                                            "textOutline": "1px 1px contrast",
                                            fontFamily: 'Verdana'
                                        }
                                    };

                                yAxisObj = {
                                    min: minGauge,
                                    max: maxGauge,
                                    minorTickInterval: 'auto',
                                    minorTickWidth: 1,
                                    minorTickLength: 6,
                                    minorTickPosition: 'inside',
                                    minorTickColor: '#666',
                                    tickPixelInterval: 30,
                                    tickWidth: 2,
                                    tickPosition: 'inside',
                                    tickLength: 8,
                                    //tickInterval: 2,
                                    tickColor: '#666',
                                    labels: {
                                        step: 2,
                                        rotation: 'auto',
                                        style: {
                                            "text-shadow": "1px 1px 1px rgba(0,0,0,0.20)",
                                            "textOutline": "1px 1px contrast",
                                            fontFamily: 'Verdana'
                                        }
                                    },
                                    title: udmObj,
                                    plotBands: plotBandSet 
                                }; 
                            }

                            if(firstLoad !== false)
                            {
                                showWidgetContent(widgetName);
                            }
                            else
                            {
                                elToEmpty.empty();
                            }

                            //Disegno del diagramma
                            var chart = $('#<?= $_GET['name'] ?>_chartContainer').highcharts({
                                credits: {
                                    enabled: false
                                },
                                chart: {
                                    type: 'gauge',
                                    backgroundColor: '<?= $_GET['color'] ?>',
                                    plotBackgroundColor: null,
                                    plotBackgroundImage: null,
                                    plotBorderWidth: 0,
                                    plotShadow: false
                                },
                                //NON RIMUOVERE        
                                title: {
                                    text: ''
                                },
                                pane: paneObj,
                                plotOptions: plotOptionsObj,
                                yAxis: yAxisObj,
                                series: [{
                                        data: [shownValue],
                                        tooltip: {
                                            enabled: false
                                        },
                                        dataLabels: dataLabelsObj
                                    }],
                                exporting: {
                                    enabled: false
                                }
                            });
                        }
                    }
                    else
                    {
                        showWidgetContent(widgetName);
			$('#<?= $_GET['name'] ?>_noDataAlert').show();
                    }  
                
                
                    
                }
                else
                {
                    showWidgetContent(widgetName);
                    $('#<?= $_GET['name'] ?>_noDataAlert').show();
                }
                /*Fine eventuale codice ad hoc basato sui dati della metrica*/
            }
            else
            {
                showWidgetContent(widgetName);
                $('#<?= $_GET['name'] ?>_noDataAlert').show();
            } 
        }    
        else
        {
            alert("Error while loading widget properties");
        }
        startCountdown(widgetName, timeToReload, <?= $_GET['name'] ?>, elToEmpty, "widgetSpeedometer", null, null);
    });//Fine document.ready            
</script>
<div class="widget" id="<?= $_GET['name'] ?>_div">
    <div class='ui-widget-content'>
        <div id='<?= $_GET['name'] ?>_header' class="widgetHeader">
            <div id="<?= $_GET['name'] ?>_infoButtonDiv" class="infoButtonContainer">
                <a id ="info_modal" href="#" class="info_source"><img id="source_<?= $_GET['name'] ?>" src="../management/img/info.png" class="source_button"></a>
            </div>    
            <div id="<?= $_GET['name'] ?>_titleDiv" class="titleDiv"></div>
            <div id="<?= $_GET['name'] ?>_buttonsDiv" class="buttonsContainer">
                <a class="icon-cfg-widget" href="#"><span class="glyphicon glyphicon-cog glyphicon-modify-widget" aria-hidden="true"></span></a>
                <a class="icon-remove-widget" href="#"><span class="glyphicon glyphicon-remove glyphicon-modify-widget" aria-hidden="true"></span></a>
            </div>
            <div id="<?= $_GET['name'] ?>_countdownContainerDiv" class="countdownContainer">
                <div id="<?= $_GET['name'] ?>_countdownDiv" class="countdown"></div> 
            </div>   
        </div>
        
        <div id="<?= $_GET['name'] ?>_loading" class="loadingDiv">
            <div class="loadingTextDiv">
                <p>Loading data, please wait</p>
            </div>
            <div class ="loadingIconDiv">
                <i class='fa fa-spinner fa-spin'></i>
            </div>
        </div>
        
        <div id="<?= $_GET['name'] ?>_content" class="content">
            <p id="<?= $_GET['name'] ?>_noDataAlert" style='text-align: center; font-size: 18px; display:none'>Nessun dato disponibile</p>
            <div id="<?= $_GET['name'] ?>_chartContainer" class="chartContainer"></div>
        </div>
    </div>	
</div> 
        