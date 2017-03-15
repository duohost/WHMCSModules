<?php

//This file use in WHMCS modules directory
if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}
/*
 *
 *  THIS SCRIPT IS DEVELOPED BY Nic Sepehr Co.
 *  THIS SCRIPT IS DISTRIBUTED UNDER APACHE 2.0 LICENSE.
 *  Copyright [2017] [Nic Sepehr Co]
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 *
 * */
function RootLicense_LiteSpeed_ConfigOptions()
{
    $configarray = array(
        // the dropdown field type renders a select menu of options
        "configoption" => array(
            "FriendlyName" => "LiteSpeed type",
            'Type' => 'dropdown',
            'Options' => "VPS Enterprise,Ultra VPS Enterprise,1-CPU Enterprise,2-CPU Enterprise,4-CPU Enterprise,8-CPU Enterprise",
            'description' => 'Please choose one of the options.'
        ),
    );
    return $configarray;
}

function RootLicense_LiteSpeed_CreateAccount(array $params)
{
    $result = mysql_query("SELECT `value` FROM `tbladdonmodules` WHERE `module` = 'rootlicense_addons' && `setting`='Email'");
    $email= mysql_fetch_array($result);

    $result2 = mysql_query("SELECT `value` FROM `tbladdonmodules` WHERE `module` = 'rootlicense_addons' && `setting`='Password'");
    $password = mysql_fetch_array($result2);

    if (empty($email)) {
        return "Please Active Your RootLicense Addon!";
    }
    $postfield['email'] = $email['value'];
    $postfield['password'] = $password['value'];
    $postfield['serviceid'] = $params['serviceid'];
    $serviceid =$params['serviceid'];
    $postfield['action'] = 'action';
    $postfield['product']=$params['configoption1'];
    $postfield['caching'] = $params['configoptions']['Caching'];



    $result3=mysql_query("SELECT `billingcycle` FROM `tblhosting` WHERE `id` = $serviceid ");
    $billingcycle = mysql_fetch_array($result3);
    $postfield['billing'] = $billingcycle['billingcycle'];

// send to the rootlicense severe

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://apinew.rootlicense.com/api/v1/litespeed/add');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postfield));
    $response = curl_exec($ch);
    if (curl_error($ch)) {
        die('Unable to connect: ' . curl_errno($ch) . ' - ' . curl_error($ch));
    }
    curl_close($ch);

// Attempt to decode response as json
    $jsonData = json_decode($response, true);
    if ($jsonData["result"]=="success"){
        update_query('tblhosting',array('domain' => $jsonData["message"] ,),
            array('id' =>  $params["serviceid"],));
        return 'success';
    }else {

        return $jsonData["message"] ;
    }
}

function RootLicense_LiteSpeed_SuspendAccount(array $params)
{
    $result = mysql_query("SELECT `value` FROM `tbladdonmodules` WHERE `module` = 'rootlicense_addons' && `setting`='Email'");
    $email= mysql_fetch_array($result);

    $result2 = mysql_query("SELECT `value` FROM `tbladdonmodules` WHERE `module` = 'rootlicense_addons' && `setting`='Password'");
    $password = mysql_fetch_array($result2);

    if (empty($email)) {
        return "Please Active Your RootLicense Addon!";
    }
    $postfield['email'] = $email['value'];
    $postfield['password'] = $password['value'];
    $postfield['serviceid'] = $params['serviceid'];
    $serviceid =$params['serviceid'];
    $postfield['action'] = 'action';
    $postfield['proudct']=$params['configoption1'];

    $result3=mysql_query("SELECT `billingcycle` FROM `tblhosting` WHERE `id` = $serviceid ");
    $billingcycle = mysql_fetch_array($result3);
    $postfield['biling'] = $billingcycle['billingcycle'];

// send to the rootlicense severe

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://apinew.rootlicense.com/api/v1/litespeed/suspend');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postfield));
    $response = curl_exec($ch);
    if (curl_error($ch)) {
        die('Unable to connect: ' . curl_errno($ch) . ' - ' . curl_error($ch));
    }
    curl_close($ch);

// Attempt to decode response as json
    $jsonData = json_decode($response, true);
    if ($jsonData["result"]=="success"){
        return 'success';
    }
    else {

        return $jsonData["message"] ;
    }

}

function RootLicense_LiteSpeed_UnsuspendAccount(array $params)
{
    $result = mysql_query("SELECT `value` FROM `tbladdonmodules` WHERE `module` = 'rootlicense_addons' && `setting`='Email'");
    $email= mysql_fetch_array($result);

    $result2 = mysql_query("SELECT `value` FROM `tbladdonmodules` WHERE `module` = 'rootlicense_addons' && `setting`='Password'");
    $password = mysql_fetch_array($result2);

    if (empty($email)) {
        return "Please Active Your RootLicense Addon!";
    }
    $postfield['email'] = $email['value'];
    $postfield['password'] = $password['value'];
    $postfield['serviceid'] = $params['serviceid'];
    $serviceid =$params['serviceid'];
    $postfield['action'] = 'action';
    $postfield['proudct']=$params['configoption1'];

    $result3=mysql_query("SELECT `billingcycle` FROM `tblhosting` WHERE `id` = $serviceid ");
    $billingcycle = mysql_fetch_array($result3);
    $postfield['biling'] = $billingcycle['billingcycle'];

// send to the rootlicense severe

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://apinew.rootlicense.com/api/v1/litespeed/unsuspend');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postfield));
    $response = curl_exec($ch);
    if (curl_error($ch)) {
        die('Unable to connect: ' . curl_errno($ch) . ' - ' . curl_error($ch));
    }
    curl_close($ch);

// Attempt to decode response as json
    $jsonData = json_decode($response, true);
    if ($jsonData["result"]=="success"){
        return 'success';
    }
    else {

        return $jsonData["message"] ;
    }

}

function RootLicense_LiteSpeed_TerminateAccount(array $params)
{
    $result = mysql_query("SELECT `value` FROM `tbladdonmodules` WHERE `module` = 'rootlicense_addons' && `setting`='Email'");
    $email= mysql_fetch_array($result);

    $result2 = mysql_query("SELECT `value` FROM `tbladdonmodules` WHERE `module` = 'rootlicense_addons' && `setting`='Password'");
    $password = mysql_fetch_array($result2);

    if (empty($email)) {
        return "Please Active Your RootLicense Addon!";
    }
    $postfield['email'] = $email['value'];
    $postfield['password'] = $password['value'];
    $postfield['serviceid'] = $params['serviceid'];
    $serviceid =$params['serviceid'];
    $postfield['action'] = 'action';
    $postfield['proudct']=$params['configoption1'];
    $ip = $params["customfields"]["IP"];

    if (empty($ip)) {
        return "Ip address is not filled OR there is no customfield named (IP)";
    }
    $postfield['IP']=$params['customfields']['IP'];
    $result3=mysql_query("SELECT `billingcycle` FROM `tblhosting` WHERE `id` = $serviceid ");
    $billingcycle = mysql_fetch_array($result3);
    $postfield['biling'] = $billingcycle['billingcycle'];

// send to the rootlicense severe

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://apinew.rootlicense.com/api/v1/litespeed/terminate');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postfield));
    $response = curl_exec($ch);
    if (curl_error($ch)) {
        die('Unable to connect: ' . curl_errno($ch) . ' - ' . curl_error($ch));
    }
    curl_close($ch);

// Attempt to decode response as json
    $jsonData = json_decode($response, true);
    if ($jsonData["result"]=="success"){
        return 'success';
    }
    else {

        return $jsonData["message"] ;
    }
}
function RootLicense_LiteSpeed_Renew(array $params)
{
    $result = mysql_query("SELECT `value` FROM `tbladdonmodules` WHERE `module` = 'rootlicense_addons' && `setting`='Email'");
    $email= mysql_fetch_array($result);

    $result2 = mysql_query("SELECT `value` FROM `tbladdonmodules` WHERE `module` = 'rootlicense_addons' && `setting`='Password'");
    $password = mysql_fetch_array($result2);

    if (empty($email)) {
        return "Please Active Your RootLicense Addon!";
    }
    $postfield['email'] = $email['value'];
    $postfield['password'] = $password['value'];
    $postfield['serviceid'] = $params['serviceid'];
    $serviceid =$params['serviceid'];
    $postfield['action'] = 'action';
    $postfield['proudct']=$params['configoption1'];

    $result3=mysql_query("SELECT `billingcycle` FROM `tblhosting` WHERE `id` = $serviceid ");
    $billingcycle = mysql_fetch_array($result3);
    $postfield['biling'] = $billingcycle['billingcycle'];

// send to the rootlicense severe

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://apinew.rootlicense.com/api/v1/litespeed/renew');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postfield));
    $response = curl_exec($ch);
    if (curl_error($ch)) {
        die('Unable to connect: ' . curl_errno($ch) . ' - ' . curl_error($ch));
    }
    curl_close($ch);

// Attempt to decode response as json
    $jsonData = json_decode($response, true);
    if ($jsonData["result"]=="success"){
        return 'success';
    }
    else {

        return $jsonData["message"] ;
    }
}
function RootLicense_LiteSpeed_ClientArea($params){

    $result = mysql_query("SELECT `value` FROM `tbladdonmodules` WHERE `module` = 'rootlicense_addons' && `setting`='Email'");
    $email= mysql_fetch_array($result);

    $result2 = mysql_query("SELECT `value` FROM `tbladdonmodules` WHERE `module` = 'rootlicense_addons' && `setting`='Password'");
    $password = mysql_fetch_array($result2);

    $query = "SELECT * FROM `tblhosting` WHERE id = ".$params['serviceid'];
    $set = mysql_query($query);
    $res = mysql_fetch_assoc($set);

    $postfield['email'] = $email['value'];
    $postfield['password'] = $password['value'];
    $postfield['serviceid'] = $params['serviceid'];
    $postfield['serial'] = $res['domain'];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://apinew.rootlicense.com/api/v1/litespeed/customerdetails');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postfield));
    $response = curl_exec($ch);
    if (curl_error($ch)) {
        die('Unable to connect: ' . curl_errno($ch) . ' - ' . curl_error($ch));
    }
    curl_close($ch);

// Attempt to decode response as json
    $jsonData = json_decode($response, true);
    if($jsonData['result'] == "success"){
        $output = "<table>";
        foreach($jsonData['message']['details'] as $key => $value ){
            $output .= "<tr>";
            $output .= "<td>".$key."</td>";
            $output .= "<td>".$value."</td>";
            $output .= "</tr>";
        }
        $output .= "</table>";
    }

    echo $output;
}
?>