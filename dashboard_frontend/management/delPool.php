<?php

/*Dashboard Builder.
   Copyright (C) 2017 DISIT Lab https://www.disit.org - University of Florence

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
   
   //Altrimenti restituisce in output le warning
   error_reporting(E_ERROR | E_NOTICE);
   
   session_start(); 
   $link = mysqli_connect($host, $username, $password) or die("Failed to connect to server");
   mysqli_select_db($link, $dbname);
   
   $queryFail = null;
   
   if(!$link->set_charset("utf8")) 
   {
       die();
   }

   if(isset($_SESSION['loggedRole']))
   {
      if(($_SESSION['loggedRole'] == "ToolAdmin")||($_SESSION['loggedRole'] == "AreaManager"))
      {
         $poolIdToDelete = mysqli_real_escape_string($link, $_POST["poolIdToDelete"]);

         $beginTransactionResult = mysqli_begin_transaction($link, MYSQLI_TRANS_START_READ_WRITE);

         //Cancellazione pool
         $query = "DELETE FROM Dashboard.UsersPools WHERE poolId = '$poolIdToDelete'";
         $result = mysqli_query($link, $query) or die(mysqli_error($link));

         if($result)
         {
             //Cancellazione appartenenze al pool rimosso
             $query2 = "DELETE FROM Dashboard.UsersPoolsRelations WHERE poolId = '$poolIdToDelete'";
             $result2 = mysqli_query($link, $query2) or die(mysqli_error($link));

             if($result2)
             {
                 $commit = mysqli_commit($link);
                 mysqli_close($link);
                 echo 1;
                 die();
             }
             else
             {
                 $rollbackResult = mysqli_rollback($link);
                 mysqli_close($link);
                 echo 0;
                 die();
             }
         }
         else
         {
             $rollbackResult = mysqli_rollback($link);
             mysqli_close($link);
             echo 0;
             die();
         }
      }
   }    