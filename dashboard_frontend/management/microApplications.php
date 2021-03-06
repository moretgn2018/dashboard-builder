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

    include('../config.php');
    include('process-form.php');
    if(!isset($_SESSION))
    {
       session_start();
    }
    
    $link = mysqli_connect($host, $username, $password);
    mysqli_select_db($link, $dbname);
    
    if(!isset($_SESSION['loggedRole']))
    {
        header("location: unauthorizedUser.php");
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Snap4City</title>

        <!-- Bootstrap Core CSS -->
        <link href="../css/bootstrap.css" rel="stylesheet">

        <link href="../css/bootstrap-colorpicker.min.css" rel="stylesheet">

        <!-- jQuery -->
        <script src="../js/jquery-1.10.1.min.js"></script>

        <!-- JQUERY UI -->
        <script src="../js/jqueryUi/jquery-ui.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="../js/bootstrap.min.js"></script>

        <!-- Custom Core JavaScript -->
        <script src="../js/bootstrap-colorpicker.min.js"></script>

        <!-- Bootstrap toggle button -->
       <link href="../bootstrapToggleButton/css/bootstrap-toggle.min.css" rel="stylesheet">
       <script src="../bootstrapToggleButton/js/bootstrap-toggle.min.js"></script>
       
       <!-- Bootstrap editable tables -->
       <!--<link href="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
       <script src="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>-->
       
       <link href="../bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet">
       <script src="../bootstrap3-editable/js/bootstrap-editable.js"></script>

       <!-- Bootstrap table -->
       <link rel="stylesheet" href="../boostrapTable/dist/bootstrap-table.css">
       <script src="../boostrapTable/dist/bootstrap-table.js"></script>
       <!-- Questa inclusione viene sempre DOPO bootstrap-table.js -->
       <script src="../boostrapTable/dist/locale/bootstrap-table-en-US.js"></script>
       
       <!-- Dynatable -->
       <link rel="stylesheet" href="../dynatable/jquery.dynatable.css">
       <script src="../dynatable/jquery.dynatable.js"></script>
       
       <!-- Bootstrap slider -->
        <script src="../bootstrapSlider/bootstrap-slider.js"></script>
        <link href="../bootstrapSlider/css/bootstrap-slider.css" rel="stylesheet"/>
        
        <!-- Filestyle -->
        <script type="text/javascript" src="../js/filestyle/src/bootstrap-filestyle.min.js"></script>

       <!-- Font awesome icons -->
        <link rel="stylesheet" href="../js/fontAwesome/css/font-awesome.min.css">

        <link href="https://fonts.googleapis.com/css?family=Cabin:400,500,600,700|Catamaran|Varela+Round" rel="stylesheet">
        
        <!-- Custom CSS -->
        <link href="../css/dashboard.css" rel="stylesheet">
        <link href="../css/dashboardList.css" rel="stylesheet">
        <link href="../css/iotApplications.css" rel="stylesheet">
        
        <!-- Custom scripts -->
        <script type="text/javascript" src="../js/dashboard_mng.js"></script>
        
        <!--<link href="https://fonts.googleapis.com/css?family=Cabin:400,500,600,700|Catamaran|Varela+Round" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">-->
    </head>
    <body class="guiPageBody">
        <div class="container-fluid">
            <?php include "sessionExpiringPopup.php" ?> 
            
            <div class="row mainRow">
                <?php include "mainMenu.php" ?>
                <div class="col-xs-12 col-md-10" id="mainCnt">
                    <div class="row hidden-md hidden-lg">
                        <div id="mobHeaderClaimCnt" class="col-xs-12 hidden-md hidden-lg centerWithFlex">
                            Snap4City
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-10 col-md-12 centerWithFlex" id="headerTitleCnt">Micro applications</div>
                        <div class="col-xs-2 hidden-md hidden-lg centerWithFlex" id="headerMenuCnt"><?php include "mobMainMenu.php" ?></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12" id="mainContentCnt" style='background-color: rgba(138, 159, 168, 1)'>
                            <div class="row mainContentRow" id="iotApplicationsIframeRow">
                                <div class="col-xs-12 mainContentCellCnt" id="iotApplicationsIframeCnt">
                                    <iframe id="iotApplicationsIframe"></iframe>
                                </div>
                            </div>    
                            
                            
                            <div class="row mainContentRow" id="dashboardsListTableRow">
                                <div class="col-xs-12 mainContentCellCnt" style='background-color: rgba(138, 159, 168, 1)'>
                                    <div id="dashboardsListMenu" class="row">
                                        <!--<div id="dashboardListsViewMode" class="hidden-xs col-sm-6 col-md-2 dashboardsListMenuItem">
                                            <div class="dashboardsListMenuItemContent centerWithFlex col-xs-12">
                                                <input id="dashboardListsViewModeInput" type="checkbox">
                                            </div>
                                        </div>-->
                                        <div id="dashboardListsCardsSort" class="col-xs-12 col-sm-6 col-md-1 col-md-offset-2 dashboardsListMenuItem">
                                            <div class="dashboardsListMenuItemContent centerWithFlex col-xs-12">
                                                <div class="col-xs-6 centerWithFlex">
                                                    <div class="dashboardsListSortBtnCnt">
                                                        <i class="fa fa-sort-alpha-asc dashboardsListSort"></i>
                                                    </div> 
                                                </div>
                                                <div class="col-xs-6 centerWithFlex">
                                                    <div class="dashboardsListSortBtnCnt">
                                                        <i class="fa fa-sort-alpha-desc dashboardsListSort"></i>
                                                    </div>    
                                                </div>
                                            </div>
                                        </div>
                                        <div id="dashboardListsPages" class="col-xs-12 col-sm-6 col-md-3 dashboardsListMenuItem">
                                           <!--<div class="dashboardsListMenuItemTitle centerWithFlex col-xs-4">
                                                List<br>pages
                                            </div>-->
                                            <div class="dashboardsListMenuItemContent centerWithFlex col-xs-12">
                                                
                                            </div>
                                        </div>
                                        
                                        <div id="dashboardListsSearchFilter" class="col-xs-12 col-sm-6 col-md-4 dashboardsListMenuItem">
                                            <!--<div class="dashboardsListMenuItemTitle centerWithFlex col-xs-3">
                                                Search
                                            </div>-->
                                            <div class="dashboardsListMenuItemContent centerWithFlex col-xs-12">
                                                <div class="input-group">
                                                    <div class="input-group-btn">
                                                      <button type="button" id="searchDashboardBtn" class="btn"><i class="fa fa-search"></i></button>
                                                      <button type="button" id="resetSearchDashboardBtn" class="btn"><i class="fa fa-close"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="dashboardListsNewDashboard" class="col-xs-12 col-sm-6 col-md-2 dashboardsListMenuItem">
                                            <!--<div class="dashboardsListMenuItemTitle centerWithFlex col-xs-4">
                                                New<br>dashboard
                                            </div>-->
                                            <div class="dashboardsListMenuItemContent centerWithFlex col-xs-12">
                                                <button id="link_add_dashboard" data-toggle="modal" data-target="#modal-add-metric" type="button" class="btn btn-warning">Request new</button>
                                                <!--<i id="link_add_dashboard" data-toggle="modal" data-target="#modal-add-metric" class="fa fa-plus-square"></i>-->
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                    <table id="list_dashboard" class="table">
                                        <thead class="dashboardsTableHeader">
                                            <tr>
                                                <th data-dynatable-column="title_header">Title</th>
                                                <th data-dynatable-column="user">Creator</th>
                                                <th data-dynatable-column="creation_date">Creation date</th>
                                                <th data-dynatable-column="last_edit_date">Last edit date</th>
                                                <th data-dynatable-column="status_dashboard">Status</th>
                                                <th>Edit</th>
                                                <th>View</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                    
                                    <div id="list_dashboard_cards" class="container-fluid">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modale creazione dashboard -->
        <div class="modal fade" id="modalCreateDashboard" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <form id="form-setting-dashboard" class="form-horizontal" name="form-setting-dashboard" role="form" method="post" action="" data-toggle="validator" enctype="multipart/form-data">  
              <div class="modal-content">
                <div class="modalHeader centerWithFlex">
                  Create dashboard
                </div>

                <div id="addDashboardModalBody" class="modal-body modalBody">
                    <ul class="nav nav-tabs nav-justified">
                        <li class="active"><a data-toggle="tab" href="#measuresTab">Measures</a></li>
                        <li><a data-toggle="tab" href="#headerTab">Header</a></li>
                        <li><a data-toggle="tab" href="#bodyTab">Body</a></li>
                        <li><a data-toggle="tab" href="#visibilityTab">Visibility</a></li>
                        <li><a data-toggle="tab" href="#embeddabilityTab">Embeddability</a></li>
                    </ul>
                    
                    <div class="tab-content">
                        <!-- Measures tab -->
                        <div id="measuresTab" class="tab-pane fade in active">
                            <div class="row">
                                <div class="col-xs-12 modalCell">
                                    <div class="modalFieldCnt">
                                        <select name="inputDashboardViewMode" class="modalInputTxt" id="inputDashboardViewMode" required>
                                            <option value="fixed">Fixed width dashboard</option>
                                            <option value="smallResponsive">Responsive on small displays (width < 768px)</option>
                                            <option value="mediumResponsive">Responsive on small and medium displays (width < 992px)</option>
                                            <option value="largeResponsive">Responsive on small, medium and large displays (width < 1200px)</option>
                                            <option value="alwaysResponsive">Always responsive</option>
                                        </select>
                                    </div>
                                    <div class="modalFieldLabelCnt">View mode</div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-xs-12 col-md-12 modalCell">
                                    <div class="modalFieldCnt">
                                        <input id="inputWidthDashboard" name="inputWidthDashboard" data-slider-id="inputWidthDashboardSlider" type="text" data-slider-min="1" data-slider-max="100" data-slider-step="1" data-slider-value="10"/>
                                    </div>
                                    <div class="modalFieldLabelCnt">Width (cells)</div>
                                </div>
                                <div class="col-xs-6 modalCell">
                                    <div class="modalFieldCnt">
                                        <input type="text" class="modalInputTxt" name="pixelWidth" id="pixelWidth" disabled> 
                                    </div>
                                    <div class="modalFieldLabelCnt">Pixel width</div>
                                </div>
                                <div class="col-xs-6 modalCell">
                                    <div class="modalFieldCnt">
                                        <input type="text" class="modalInputTxt" name="percentWidth" id="percentWidth" disabled> 
                                    </div>
                                    <div class="modalFieldLabelCnt">Width (%) on your screen</div>
                                </div>
                            </div>
                        </div>
                        <!-- Header tab -->
                        <div id="headerTab" class="tab-pane fade">
                            <div class="row">
                                <div class="col-xs-12 col-md-6 modalCell">
                                    <div class="modalFieldCnt">
                                        <input type="text" class="modalInputTxt" name="inputTitleDashboard" id="inputTitleDashboard" required> 
                                    </div>
                                    <div class="modalFieldLabelCnt">Title</div>
                                </div>
                                <div class="col-xs-12 col-md-6 modalCell">
                                    <div class="modalFieldCnt">
                                        <input type="text" class="modalInputTxt" name="inputSubTitleDashboard" id="inputSubTitleDashboard"> 
                                    </div>
                                    <div class="modalFieldLabelCnt">Subtitle</div>
                                </div>
                                <div class="col-xs-12 col-md-6 modalCell">
                                    <div class="modalFieldCnt">
                                        <div class="input-group customColorChoice">
                                            <input type="text" class="modalInputTxt" id="inputColorDashboard" name="inputColorDashboard" value="#5367ce" required>
                                            <span class="input-group-addon"><i></i></span>
                                        </div>
                                    </div>
                                    <div class="modalFieldLabelCnt">Header color</div>
                                </div>
                                <div class="col-xs-12 col-md-2 col-md-offset-2 modalCell">
                                    <div class="modalFieldCnt">
                                        <input id="headerVisible" name="headerVisible" checked type="checkbox">
                                    </div>
                                    <div class="modalFieldLabelCnt">Show header</div>
                                </div>
                                <div class="col-xs-12 col-md-6 modalCell">
                                    <div class="modalFieldCnt">
                                        <input id="headerFontSize" name="headerFontSize" data-slider-id="headerFontSizeSlider" type="text" data-slider-min="1" data-slider-max="36" data-slider-step="1" data-slider-value="28"/>
                                    </div>
                                    <div class="modalFieldLabelCnt">Header font size</div>
                                </div>
                                <div class="col-xs-12 col-md-6 modalCell">
                                    <div class="modalFieldCnt">
                                        <div class="input-group customColorChoice">
                                            <input type="text" class="modalInputTxt" id="headerFontColor" name="headerFontColor" value="#ffffff">
                                            <span class="input-group-addon"><i id="color_hf"></i></span>
                                        </div>
                                    </div>
                                    <div class="modalFieldLabelCnt">Header font color</div>
                                </div>
                                <div class="col-xs-12 col-md-6 modalCell">
                                    <div class="modalFieldCnt">
                                        <input id="dashboardLogoInput" name="dashboardLogoInput" type="file" class="filestyle modalInputTxt" data-badge="false" data-input ="true" data-size="nr" data-buttonName="btn-primary" data-buttonText="File">
                                    </div>
                                    <div class="modalFieldLabelCnt">Header logo</div>
                                </div>
                                <div class="col-xs-12 col-md-6 modalCell">
                                    <div class="modalFieldCnt">
                                        <input type="text" class="modalInputTxt" name="dashboardLogoLinkInput" id="dashboardLogoLinkInput"> 
                                    </div>
                                    <div class="modalFieldLabelCnt">Header logo link</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Body tab -->
                        <div id="bodyTab" class="tab-pane fade">
                            <div class="row">
                                <div class="col-xs-12 col-md-6 modalCell">
                                    <div class="modalFieldCnt">
                                        <div class="input-group customColorChoice">
                                            <input type="text" class="modalInputTxt" id="inputColorBackgroundDashboard" name="inputColorBackgroundDashboard" value="#ffffff" required>
                                            <span class="input-group-addon"><i></i></span>
                                        </div> 
                                    </div>
                                    <div class="modalFieldLabelCnt">Widgets area color</div>
                                </div>
                                <div class="col-xs-12 col-md-6 modalCell">
                                    <div class="modalFieldCnt">
                                        <div class="input-group customColorChoice">
                                            <input type="text" class="modalInputTxt" id="inputExternalColorDashboard" name="inputExternalColorDashboard" value="#ffffff" required>
                                            <span class="input-group-addon"><i></i></span>
                                        </div>
                                    </div>
                                    <div class="modalFieldLabelCnt">External frame color</div>
                                </div>
                                <div class="col-xs-12 col-md-4 col-md-offset-1 modalCell">
                                    <input id="widgetsBorders" name="widgetsBorders" checked type="checkbox">
                                    <div class="modalFieldLabelCnt">Widgets borders</div>
                                </div>
                                <div class="col-xs-12 col-md-6 col-md-offset-1 modalCell">
                                    <div class="modalFieldCnt">
                                        <div class="input-group customColorChoice">
                                            <input type="text" class="modalInputTxt" id="inputWidgetsBordersColor" name="inputWidgetsBordersColor" value="#dddddd" required>
                                            <span class="input-group-addon"><i></i></span>
                                        </div>
                                    </div>
                                    <div class="modalFieldLabelCnt">Widgets borders color</div>
                                </div>
                            </div>    
                        </div>
                        
                        <!-- Visibility tab -->
                        <div id="visibilityTab" class="tab-pane fade">
                            <div class="row">
                                <div class="col-xs-12 modalCell">
                                    <div class="modalFieldCnt">
                                        <select name="inputDashboardVisibility" class="modalInputTxt" id="inputDashboardVisibility" required>
                                            <option value="author">Dashboard author only</option>
                                            <option value="restrict">Author and selected users</option>
                                            <option value="public">Everybody (public)</option>
                                        </select>
                                    </div>
                                    <!--<div class="modalFieldLabelCnt">Permission type</div>-->
                                </div>
                                <div class="col-xs-12 modalCell">
                                    <div class="modalFieldCnt">
                                        <table id="inputDashboardVisibilityUsersTable"></table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Embeddability tab -->
                        <div id="embeddabilityTab" class="tab-pane fade">
                            <div class="row">
                                <div class="col-xs-12 modalCell">
                                    <div class="modalFieldCnt">
                                        <table id="authorizedPagesTable">
                                            <thead>
                                                <th>Authorized pages</th>
                                                <th><i id="addAuthorizedPageBtn" class="fa fa-plus"></i></th>
                                            </thead>
                                            <tbody></tbody>
                                        </table> 
                                    </div>
                                    <input type="hidden" id="authorizedPagesJson" name="authorizedPagesJson" />
                                </div>
                            </div>
                        </div>
                    </div>
                <div id="addDashboardModalFooter" class="modal-footer">
                  <button type="button" id="addDashboardCancelBtn" class="btn cancelBtn" data-dismiss="modal">Cancel</button>
                  <button type="submit" id="addDashboardConfirmBtn" name="addDashboard" class="btn confirmBtn internalLink">Confirm</button>
                </div>
              </div>
            </form>  
            </div>
        </div>
    </body>
</html>

<script type='text/javascript'>
    $(document).ready(function () 
    {
        var dashboardsList = null;
        
        var sessionEndTime = "<?php echo $_SESSION['sessionEndTime']; ?>";
        $('#sessionExpiringPopup').css("top", parseInt($('body').height() - $('#sessionExpiringPopup').height()) + "px");
        $('#sessionExpiringPopup').css("left", parseInt($('body').width() - $('#sessionExpiringPopup').width()) + "px");
        
        setInterval(function(){
            var now = parseInt(new Date().getTime() / 1000);
            var difference = sessionEndTime - now;
            
            if(difference === 300)
            {
                $('#sessionExpiringPopupTime').html("5 minutes");
                $('#sessionExpiringPopup').show();
                $('#sessionExpiringPopup').css("opacity", "1");
                setTimeout(function(){
                    $('#sessionExpiringPopup').css("opacity", "0");
                    setTimeout(function(){
                        $('#sessionExpiringPopup').hide();
                    }, 1000);
                }, 4000);
            }
            
            if(difference === 120)
            {
                $('#sessionExpiringPopupTime').html("2 minutes");
                $('#sessionExpiringPopup').show();
                $('#sessionExpiringPopup').css("opacity", "1");
                setTimeout(function(){
                    $('#sessionExpiringPopup').css("opacity", "0");
                    setTimeout(function(){
                        $('#sessionExpiringPopup').hide();
                    }, 1000);
                }, 4000);
            }
            
            if((difference > 0)&&(difference <= 60))
            {
                $('#sessionExpiringPopup').show();
                $('#sessionExpiringPopup').css("opacity", "1");
                $('#sessionExpiringPopupTime').html(difference + " seconds");
            }
            
            if(difference <= 0)
            {
                location.href = "logout.php?sessionExpired=true";
            }
        }, 1000);
        
        $('#mainContentCnt').height($('#mainMenuCnt').height() - $('#headerTitleCnt').height());
        $('#iotApplicationsIframeCnt').height($('#mainMenuCnt').height() - $('#headerTitleCnt').height());
        
        $(window).resize(function(){
            $('#mainContentCnt').height($('#mainMenuCnt').height() - $('#headerTitleCnt').height());
            $('#iotApplicationsIframeCnt').height($('#mainMenuCnt').height() - $('#headerTitleCnt').height());
            $('#sessionExpiringPopup').css("top", parseInt($('body').height() - $('#sessionExpiringPopup').height()) + "px");
            $('#sessionExpiringPopup').css("left", parseInt($('body').width() - $('#sessionExpiringPopup').width()) + "px");
        });
        
        $('#microApplicationsLink .mainMenuItemCnt').addClass("mainMenuItemCntActive");
        /*$('#mobMainMenuPortraitCnt #dashboardsLink .mobMainMenuItemCnt').addClass("mainMenuItemCntActive");
        $('#mobMainMenuLandCnt #dashboardsLink .mobMainMenuItemCnt').addClass("mainMenuItemCntActive");*/
        
        var loggedRole = "<?= $_SESSION['loggedRole'] ?>";
        var loggedType = "<?= $_SESSION['loggedType'] ?>";
        var usr = "<?= $_SESSION['loggedUsername'] ?>";
        var tableFirstLoad = true;
            
        $('#color_hf').css("background-color", '#ffffff');
            
        $("#logoutBtn").off("click");
        $("#logoutBtn").click(function(event)
        {
           event.preventDefault();
           location.href = "logout.php";
           /*$.ajax({
                url: "iframeProxy.php",
                action: "notificatorRemoteLogout",
                async: true,
                success: function()
                {

                },
                error: function(errorData)
                {
                    console.log("Remote logout from Notificator failed");
                    console.log(JSON.stringify(errorData));
                },
                complete: function()
                {
                    location.href = "logout.php";
                }
            });*/
        });
            
        function myRowWriter(rowIndex, record, columns, cellWriter)
        {
            var statusBtn, cssClass = null;
            var title = record.title_header;

            if(rowIndex%2 !== 0)
            {
                cssClass = 'blueRow';
            }
            else
            {
                cssClass = 'whiteRow';
            }

            if(title.length > 75)
            {
               title = title.substr(0, 75) + " ...";
            }

            var user = record.user;
            if(user.length > 75)
            {
               user = user.substr(0, 75) + " ...";
            }

            if((record.status_dashboard === '0')||(record.status_dashboard === 0))
            {
                statusBtn = '<input type="checkbox" data-toggle="toggle" class="changeDashboardStatus">';
            }
            else
            {
                statusBtn = '<input type="checkbox" checked data-toggle="toggle" class="changeDashboardStatus">';
            }

            var newRow = '<tr data-dashTitle="' + record.title_header + '" data-uniqueid="' + record.Id + '" data-authorName="' + record.user + '"><td class="' + cssClass + '" style="font-weight: bold">' + title + '</td><td class="' + cssClass + '">' + user + '</td><td class="' + cssClass + '">' + record.creation_date + '</td><td class="' + cssClass + '">' + record.last_edit_date + '</td><td class="' + cssClass + '">' + statusBtn + '</td><td class="' + cssClass + '"><button type="button" class="editDashBtn">edit</button></td><td class="' + cssClass + '"><button type="button" class="viewDashBtn">view</button></td></tr>';

            return newRow;
        }
    
        function myCardsWriter(rowIndex, record, columns, cellWriter)
        {
            var title = record.sub_nature;

            if(title.length > 100)
            {
               title = title.substr(0, 100) + " ...";
            }

             var cardDiv = '<div data-uniqueid="' + record.id + '" data-title="' + title + '" data-url="' + record.parameters + '" data-icon="' + record.microAppExtServIcon + '" class="dashboardsListCardDiv col-xs-12 col-sm-6 col-md-3">' + 
                               '<div class="dashboardsListCardInnerDiv">' +
                                  '<div class="dashboardsListCardTitleDiv col-xs-12 centerWithFlex">' + title + '</div>' + 
                                  '<div class="dashboardsListCardOverlayDiv col-xs-12 centerWithFlex"></div>' +
                                  '<div class="dashboardsListCardOverlayTxt col-xs-12 centerWithFlex">View</div>' +
                                  '<div class="dashboardsListCardImgDiv"></div>' + 
                                  '<div class="dashboardsListCardClick2EditDiv col-xs-12 centerWithFlex" style="background-color: inherit; color: inherit">' + 
                                      //'<button type="button" class="editDashBtnCard">Edit</button>' + 
                                  '</div>' +  
                               '</div>' +
                            '</div>';   

             return cardDiv;
        }
            
            //Nuova tabella
            $.ajax({
                url: "../controllers/getMicroApplications.php",
                data: {
                },
                type: "GET",
                async: true,
                dataType: 'json',
                success: function(data) 
                {
                    dashboardsList = data.applications;
                    //Ricordati di metterlo PRIMA dell'istanziamento della tabella
                    $('#list_dashboard_cards').bind('dynatable:afterProcess', function(e, dynatable){
                        $('#dashboardsListTableRow').css('padding-top', '0px');
                        $('#dashboardsListTableRow').css('padding-bottom', '0px');
                        
                        $('#dashboardListsViewModeInput').bootstrapToggle({
                            on: 'View as table',
                            off: 'View as cards',
                            onstyle: 'default',
                            offstyle: 'info',
                            size: 'normal'
                        });
                        
                        $('label.toggle-off').css("background-color", "rgba(0, 162, 211, 1)");
                        $('label.toggle-off').css("font-weight", "bold");
                        $('label.toggle-off').css("padding-left", "18px");
                        $('label.toggle-on').css("background-color", "rgba(255, 204, 0, 1)");
                        $('label.toggle-on').css("color", "rgba(255, 255, 255, 1)");
                        $('label.toggle-on').css("font-weight", "bold");
                        $('label.toggle-on').css("padding-right", "24px");
                        
                        /*$('#dashboardListsViewModeInput').change(function() {
                            if($(this).prop('checked'))
                            {
                                //Visione a tabella
                                $('#list_dashboard_cards').hide();
                                $('#list_dashboard').show();
                                $("#dynatable-pagination-links-list_dashboard_cards").hide();
                                $("#dynatable-query-search-list_dashboard_cards").hide();
                                $('#dashboardListsCardsSort').hide();
                                $('#dashboardListsPages').removeClass('col-md-3');
                                $('#dashboardListsPages').addClass('col-md-4');
                                $("#dashboardListsItemsPerPage").show();
                                $("#dynatable-pagination-links-list_dashboard").show();
                                $("#dynatable-query-search-list_dashboard").show();
                                
                                $('#searchDashboardBtn').off('click');
                                $('#searchDashboardBtn').click(function(){
                                    var dynatable = $('#list_dashboard').data('dynatable');
                                    dynatable.queries.run();
                                }); 

                                $('#resetSearchDashboardBtn').off('click');
                                $('#resetSearchDashboardBtn').click(function(){
                                    var dynatable = $('#list_dashboard').data('dynatable');
                                    $("#dynatable-query-search-list_dashboard").val("");
                                    dynatable.queries.runSearch("");
                                }); 
                            }
                            else
                            {
                                //Visione a cards
                                $('#list_dashboard').hide();
                                $('#list_dashboard_cards').show();
                                $("#dynatable-pagination-links-list_dashboard").hide();
                                $("#dynatable-query-search-list_dashboard").hide();
                                $("#dashboardListsItemsPerPage").hide();
                                $('#dashboardListsCardsSort').show();
                                $('#dashboardListsPages').removeClass('col-md-4');
                                $('#dashboardListsPages').addClass('col-md-3');
                                $("#dynatable-query-search-list_dashboard_cards").show();
                                $("#dynatable-pagination-links-list_dashboard_cards").show();
                                
                                $('#searchDashboardBtn').off('click');
                                $('#searchDashboardBtn').click(function(){
                                    var dynatable = $('#list_dashboard_cards').data('dynatable');
                                    dynatable.queries.run();
                                }); 

                                $('#resetSearchDashboardBtn').off('click');
                                $('#resetSearchDashboardBtn').click(function(){
                                    var dynatable = $('#list_dashboard_cards').data('dynatable');
                                    $("#dynatable-query-search-list_dashboard_cards").val("");
                                    dynatable.queries.runSearch("");
                                }); 
                            }
                        });*/


                        //Ricicliamolo come link CREATE NEW
                        $('#link_add_dashboard').off('click');
                        $('#link_add_dashboard').click(function(){
                            
                            
                        });
                        
                        $("#dynatable-pagination-links-list_dashboard_cards").appendTo("#dashboardListsPages div.dashboardsListMenuItemContent");
                        //$("#dynatable-pagination-links-list_dashboard_cards li").eq(0).remove();
                        $("#dynatable-pagination-links-list_dashboard_cards li").eq(0).remove();
                        //$("#dynatable-pagination-links-list_dashboard_cards li").eq($("#dynatable-pagination-links-list_dashboard_cards li").length - 1).remove();
                        $('#dashboardListsPages div.dashboardsListMenuItemContent').css("font-family", "Montserrat");
                        $('#dashboardListsPages div.dashboardsListMenuItemContent').css("font-weight", "bold");
                        $('#dashboardListsPages div.dashboardsListMenuItemContent').css("color", "white");
                        $('#dashboardListsPages div.dashboardsListMenuItemContent a').css("font-family", "Montserrat");
                        $('#dashboardListsPages div.dashboardsListMenuItemContent a').css("font-weight", "bold");
                        $('#dashboardListsPages div.dashboardsListMenuItemContent a').css("color", "white");
                        $("ul#dynatable-pagination-links-list_dashboard_cards").css("-webkit-padding-start", "0px");
                        $("ul#dynatable-pagination-links-list_dashboard_cards").css("-webkit-margin-before", "0px");
                        $("ul#dynatable-pagination-links-list_dashboard_cards").css("-webkit-margin-after", "0px");
                        $("ul#dynatable-pagination-links-list_dashboard_cards").css("padding", "0px");
                        
                        $("#dynatable-query-search-list_dashboard_cards").prependTo("#dashboardListsSearchFilter div.dashboardsListMenuItemContent div.input-group");
                        $('#dynatable-search-list_dashboard_cards').remove();
                        $("#dynatable-query-search-list_dashboard_cards").css("border", "none");
                        $("#dynatable-query-search-list_dashboard_cards").attr("placeholder", "Filter");
                        $("#dynatable-query-search-list_dashboard_cards").css("width", "100%");
                        $("#dynatable-query-search-list_dashboard_cards").addClass("form-control");
                        
                        $('#list_dashboard_cards div.dashboardsListCardDiv').each(function(i){
                            //$(this).find('div.dashboardsListCardImgDiv').css("background-image", "url(../img/microApplications/" + $(this).attr('data-uniqueid') + "/" + $(this).attr('data-icon') + ")");
                            $(this).find('div.dashboardsListCardImgDiv').css("background-image", "url(../img/microApplications/" + $(this).attr('data-icon') + ")");
                            $(this).find('div.dashboardsListCardImgDiv').css("background-size", "100% auto");
                            $(this).find('div.dashboardsListCardImgDiv').css("background-repeat", "no-repeat");
                            $(this).find('div.dashboardsListCardImgDiv').css("background-position", "center top");
                            $(this).find('div.dashboardsListCardInnerDiv').css("width", "100%");
                            $(this).find('div.dashboardsListCardInnerDiv').css("height", $(this).height() + "px");
                            $(this).find('div.dashboardsListCardOverlayDiv').css("height", $(this).find('div.dashboardsListCardImgDiv').height() + "px");
                            $(this).find('div.dashboardsListCardOverlayTxt').css("height", $(this).find('div.dashboardsListCardImgDiv').height() + "px");
                            
                            $(this).find('.dashboardsListCardImgDiv').off('mouseenter');
                            $(this).find('.dashboardsListCardImgDiv').off('mouseleave');
                            
                            $(this).find('.dashboardsListCardOverlayTxt').hover(function(){
                                $(this).parents('.dashboardsListCardDiv').find('div.dashboardsListCardOverlayTxt').css("opacity", "1");
                                $(this).parents('.dashboardsListCardDiv').find('div.dashboardsListCardOverlayDiv').css("opacity", "0.8");
                                $(this).css("cursor", "pointer");
                            }, function(){
                                $(this).parents('.dashboardsListCardDiv').find('div.dashboardsListCardOverlayTxt').css("opacity", "0");
                                $(this).parents('.dashboardsListCardDiv').find('div.dashboardsListCardOverlayDiv').css("opacity", "0.05");
                                $(this).css("cursor", "normal");
                            });
                            
                            $(this).find('.dashboardsListCardOverlayTxt').off('click');
                            $(this).find('.dashboardsListCardOverlayTxt').click(function() 
                            {
                                var url = $(this).parents('div.dashboardsListCardDiv').attr('data-url');
                                
                                $('#dashboardsListTableRow').hide();
                                $('#iotApplicationsIframeRow').show();
                                $('#mainContentCnt').css('padding', '0px 0px 0px 0px');
                                $('#iotApplicationsIframeCnt').css('padding-left', '0px');
                                $('#iotApplicationsIframeCnt').css('padding-right', '0px');
                                $('#iotApplicationsIframe').attr('src', url + '&coordinates=43.7712;11.2561&lang=ita&maxDistance=2&maxResults=150');
                            });
                        });
                        
                        $('#dashboardListsViewMode').hide();
                        
                        $('#searchDashboardBtn').off('click');
                        $('#searchDashboardBtn').click(function(){
                            var dynatable = $('#list_dashboard_cards').data('dynatable');
                            dynatable.queries.run();
                        }); 
                        
                        $('#resetSearchDashboardBtn').off('click');
                        $('#resetSearchDashboardBtn').click(function(){
                            var dynatable = $('#list_dashboard_cards').data('dynatable');
                            $("#dynatable-query-search-list_dashboard_cards").val("");
                            dynatable.queries.runSearch("");
                        }); 
                        
                      });
                    
                    
                    $('#list_dashboard_cards').dynatable({
                        table: {
                            bodyRowSelector: 'div'
                          },
                        dataset: {
                          records: data.applications,
                          perPageDefault: 12,
                          perPageOptions: [4, 8, 12]
                        },
                        writers: {
                            _rowWriter: myCardsWriter
                        },
                        inputs: {
                            paginationLinkPlacement: 'before'
                        },
                        features: {
                            recordCount: false,
                            perPageSelect: false,
                            search: true
                        }
                      });
                      
                      var dynatable = $('#list_dashboard_cards').data('dynatable');
                      dynatable.sorts.clear();
                      dynatable.sorts.add('title', 1); // 1=ASCENDING, -1=DESCENDING
                      dynatable.process();
                      
                      $('#dashboardListsCardsSort div.dashboardsListSortBtnCnt').eq(0).css('background-color', 'rgba(255, 204, 0, 1)');
                      $('#dashboardListsCardsSort i.dashboardsListSort').eq(0).click(function(){
                          var dynatable = $('#list_dashboard_cards').data('dynatable');
                          dynatable.sorts.clear();
                          dynatable.sorts.add('title', 1); // 1=ASCENDING, -1=DESCENDING
                          dynatable.process();
                          $('#dashboardListsCardsSort div.dashboardsListSortBtnCnt').eq(1).css('background-color', 'rgba(0, 162, 211, 1)');
                          $('#dashboardListsCardsSort div.dashboardsListSortBtnCnt').eq(0).css('background-color', 'rgba(255, 204, 0, 1)');
                      });
                      
                      $('#dashboardListsCardsSort i.dashboardsListSort').eq(1).click(function(){
                          var dynatable = $('#list_dashboard_cards').data('dynatable');
                          dynatable.sorts.clear();
                          dynatable.sorts.add('title', -1); // 1=ASCENDING, -1=DESCENDING
                          dynatable.process();
                          $('#dashboardListsCardsSort div.dashboardsListSortBtnCnt').eq(0).css('background-color', 'rgba(0, 162, 211, 1)');
                          $('#dashboardListsCardsSort div.dashboardsListSortBtnCnt').eq(1).css('background-color', 'rgba(255, 204, 0, 1)');
                      });
                    
                    /*$('#list_dashboard').bind('dynatable:afterProcess', function(e, dynatable){
                        $('span.dynatable-per-page-label').remove();
                        
                        //$('#dynatable-per-page-list_dashboard').parents('span.dynatable-per-page').appendTo("#dashboardListsItemsPerPage div.dashboardsListMenuItemContent");
                        //$('#dynatable-per-page-list_dashboard').addClass('form-control');
                        
                        $("#dynatable-pagination-links-list_dashboard").appendTo("#dashboardListsPages div.dashboardsListMenuItemContent");
                        $("#dynatable-pagination-links-list_dashboard li").eq(0).remove();
                        //$("#dynatable-pagination-links-list_dashboard li").eq(0).remove();
                        //$("#dynatable-pagination-links-list_dashboard li").eq($("#dynatable-pagination-links-list_dashboard li").length - 1).remove();
                        $('#dashboardListsPages div.dashboardsListMenuItemContent').css("font-family", "Montserrat");
                        $('#dashboardListsPages div.dashboardsListMenuItemContent').css("font-weight", "bold");
                        $('#dashboardListsPages div.dashboardsListMenuItemContent').css("color", "white");
                        $('#dashboardListsPages div.dashboardsListMenuItemContent a').css("font-family", "Montserrat");
                        $('#dashboardListsPages div.dashboardsListMenuItemContent a').css("font-weight", "bold");
                        $('#dashboardListsPages div.dashboardsListMenuItemContent a').css("color", "white");
                        $("ul#dynatable-pagination-links-list_dashboard").css("-webkit-padding-start", "0px");
                        $("ul#dynatable-pagination-links-list_dashboard").css("-webkit-margin-before", "0px");
                        $("ul#dynatable-pagination-links-list_dashboard").css("-webkit-margin-after", "0px");
                        $("ul#dynatable-pagination-links-list_dashboard").css("padding", "0px");
                        
                        $("#dynatable-query-search-list_dashboard").prependTo("#dashboardListsSearchFilter div.dashboardsListMenuItemContent div.input-group");
                        $('#dynatable-search-list_dashboard').remove();
                        $("#dynatable-query-search-list_dashboard").css("border", "none");
                        $("#dynatable-query-search-list_dashboard").attr("placeholder", "Filter by dashboard title, author...");
                        $("#dynatable-query-search-list_dashboard").css("width", "100%");
                        $("#dynatable-query-search-list_dashboard").addClass("form-control");
                        
                        $('#list_dashboard input.changeDashboardStatus').bootstrapToggle({
                            on: "On",
                            off: "Off",
                            onstyle: "primary",
                            offstyle: "default",
                            size: "mini"
                        });
                        
                        $('#list_dashboard tbody input.changeDashboardStatus').off('change');
                        $('#list_dashboard tbody input.changeDashboardStatus').change(function() {
                            if($(this).prop('checked') === false)
                            {
                                var newStatus = 0;
                            }
                            else
                            {
                                var newStatus = 1;
                            }

                            $.ajax({
                                url: "process-form.php",
                                data: {
                                    modify_status_dashboard: true,
                                    dashboardId: $(this).parents('tr').attr('data-uniqueid'),
                                    newStatus: newStatus
                                },
                                type: "POST",
                                async: true,
                                success: function(data)
                                {
                                    if(data !== "Ok")
                                    {
                                        console.log("Error updating dashboard status");
                                        console.log(data);
                                        alert("Error updating dashboard status");
                                        location.reload();
                                    }
                                    else
                                    {
                                        if($('#dashboardTotActiveCnt .pageSingleDataCnt').html() !== "-")
                                        {
                                            if(newStatus === 0)
                                            {
                                                $('#dashboardTotActiveCnt .pageSingleDataCnt').html(parseInt($('#dashboardTotActiveCnt .pageSingleDataCnt').html()) - 1);
                                            }
                                            else
                                            {
                                                $('#dashboardTotActiveCnt .pageSingleDataCnt').html(parseInt($('#dashboardTotActiveCnt .pageSingleDataCnt').html()) + 1);
                                            }
                                        }
                                    }
                                },
                                error: function(errorData)
                                {
                                    console.log("Error updating dashboard status");
                                    console.log(errorData);
                                    alert("Error updating dashboard status");
                                    location.reload();
                                }
                            });
                        });
                        
                        $('#list_dashboard button.editDashBtn').off('click');
                        $('#list_dashboard button.editDashBtn').click(function() 
                        {
                            var dashboardId = $(this).parents('tr').attr('data-uniqueid');
                            var dashboardTitle = $(this).parents('tr').attr('data-dashTitle');
                            var dashboardAuthorName = $(this).parents('tr').attr('data-authorName');
                            
                            window.open("../management/dashboard_configdash.php?dashboardId=" + dashboardId + "&dashboardAuthorName=" + dashboardAuthorName + "&dashboardEditorName=" + encodeURI("<?= $_SESSION['loggedUsername']?>" + "&dashboardTitle=" + encodeURI(dashboardTitle)));
                        });
                        
                        $('#list_dashboard button.viewDashBtn').off('click');
                        $('#list_dashboard button.viewDashBtn').click(function () 
                        {
                            var dashboardId = $(this).parents('tr').attr("data-uniqueid");
                            window.open("../view/index.php?iddasboard=" + btoa(dashboardId));
                        });
                    });
                    
                    
                      $('#list_dashboard').dynatable({
                        dataset: {
                          records: data,
                          perPageDefault: 20,
                          perPageOptions: [5, 10, 20, 30, 40]
                        },
                        writers: {
                            _rowWriter: myRowWriter
                        },
                        features: {
                            recordCount: false,
                            perPageSelect: false
                        },
                        inputs: {
                            perPagePlacement: 'after'
                        }
                      });
                      $("#dynatable-pagination-links-list_dashboard").hide();
                      $("#dynatable-query-search-list_dashboard").hide();*/
                      
                },
                error: function(errorData)
                {
                    
                }
            });
    });
</script>  