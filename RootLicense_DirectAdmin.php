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
function RootLicense_DirectAdmin_ConfigOptions()
{
    $configarray = array(
        // the dropdown field type renders a select menu of options
        "configoption" => array(
            "FriendlyName" => "Help",
            "Description" => "No settings are required here, Please review the following link: <a href=\"https://rootlicense.com/%D9%85%D8%A7%DA%98%D9%88%D9%84api/\">RootLicense.com Api Docs</a>",
        ),
    );
    return $configarray;
}

function RootLicense_DirectAdmin_CreateAccount(array $params)
{
    $result = mysql_query("SELECT `value` FROM `tbladdonmodules` WHERE `module` = 'rootlicense_addons' && `setting`='Email'");
    $email = mysql_fetch_array($result);

    $result2 = mysql_query("SELECT `value` FROM `tbladdonmodules` WHERE `module` = 'rootlicense_addons' && `setting`='Password'");
    $password = mysql_fetch_array($result2);

    if (empty($email)) {
        return "Please Active Your RootLicense Addon!";
    }
    $postfield['email'] = $email['value'];
    $postfield['password'] = $password['value'];
    $postfield['serviceid'] = $params['serviceid'];
    $serviceid = $params['serviceid'];
    $ip = $params["customfields"]["IP"];
    $os = $params["customfields"]["Operating System"];

    if (empty($ip)) {
        return "Ip address is not filled OR there is no customfield named (IP)";
    }
    else if(empty($os)){
        return "Operating system is not set OR there is no customfield named (Operating System)";
    }
    $postfield['IP'] = $params['customfields']['IP'];
    $postfield['os'] = $params["customfields"]["Operating System"];
    $result3 = mysql_query("SELECT `billingcycle` FROM `tblhosting` WHERE `id` = $serviceid ");
    $billingcycle = mysql_fetch_array($result3);
    $postfield['biling'] = $billingcycle['billingcycle'];

// send to the rootlicense severe

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://apinew.rootlicense.com/api/v1/directadmin/add');
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
    if ($jsonData["result"] == "success") {
        $message = $jsonData["message"];
        $exploded = explode(",", $message);
        $username = $exploded[0];
        $lid = $exploded[1];

        update_query('tblhosting', array('domain' => $params['customfields']['IP'], 'username' => $lid, 'dedicatedip' => $username, 'ns1' => $username,),
            array('id' => $params["serviceid"],));

        return 'success';
    } else {

        return $jsonData["message"];
    }
}

function RootLicense_DirectAdmin_SuspendAccount(array $params)
{
    $result = mysql_query("SELECT `value` FROM `tbladdonmodules` WHERE `module` = 'rootlicense_addons' && `setting`='Email'");
    $email = mysql_fetch_array($result);

    $result2 = mysql_query("SELECT `value` FROM `tbladdonmodules` WHERE `module` = 'rootlicense_addons' && `setting`='Password'");
    $password = mysql_fetch_array($result2);

    if (empty($email)) {
        return "Please Active Your RootLicense Addon!";
    }
    $postfield['email'] = $email['value'];
    $postfield['password'] = $password['value'];
    $postfield['serviceid'] = $params['serviceid'];
    $ip = $params["customfields"]["IP"];

    if (empty($ip)) {
        return "Ip address is not filled OR there is no customfield named (IP)";
    }
    $postfield['IP'] = $params['customfields']['IP'];
// send to the rootlicense severe

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://apinew.rootlicense.com/api/v1/directadmin/suspend');
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
    if ($jsonData["result"] == "success") {
        return 'success';
    } else {

        return $jsonData["message"];
    }

}

function RootLicense_DirectAdmin_UnsuspendAccount(array $params)
{
    $result = mysql_query("SELECT `value` FROM `tbladdonmodules` WHERE `module` = 'rootlicense_addons' && `setting`='Email'");
    $email = mysql_fetch_array($result);

    $result2 = mysql_query("SELECT `value` FROM `tbladdonmodules` WHERE `module` = 'rootlicense_addons' && `setting`='Password'");
    $password = mysql_fetch_array($result2);

    if (empty($email)) {
        return "Please Active Your RootLicense Addon!";
    }
    $postfield['email'] = $email['value'];
    $postfield['password'] = $password['value'];
    $postfield['serviceid'] = $params['serviceid'];
    $ip = $params["customfields"]["IP"];

    if (empty($ip)) {
        return "Ip address is not filled OR there is no customfield named (IP)";
    }
    $postfield['IP'] = $params['customfields']['IP'];

// send to the rootlicense severe

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://apinew.rootlicense.com/api/v1/directadmin/unsuspend');
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
    if ($jsonData["result"] == "success") {
        return 'success';
    } else {

        return $jsonData["message"];
    }

}

function RootLicense_DirectAdmin_TerminateAccount(array $params)
{
    $result = mysql_query("SELECT `value` FROM `tbladdonmodules` WHERE `module` = 'rootlicense_addons' && `setting`='Email'");
    $email = mysql_fetch_array($result);

    $result2 = mysql_query("SELECT `value` FROM `tbladdonmodules` WHERE `module` = 'rootlicense_addons' && `setting`='Password'");
    $password = mysql_fetch_array($result2);

    if (empty($email)) {
        return "Please Active Your RootLicense Addon!";
    }
    $postfield['email'] = $email['value'];
    $postfield['password'] = $password['value'];
    $postfield['serviceid'] = $params['serviceid'];
    $ip = $params["customfields"]["IP"];

    if (empty($ip)) {
        return "Ip address is not filled OR there is no customfield named (IP)";
    }
    $postfield['IP'] = $params['customfields']['IP'];

// send to the rootlicense severe

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://apinew.rootlicense.com/api/v1/directadmin/terminate');
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
    if ($jsonData["result"] == "success") {
        return 'success';
    } else {

        return $jsonData["message"];
    }
}

function RootLicense_DirectAdmin_Renew(array $params)
{
    $result = mysql_query("SELECT `value` FROM `tbladdonmodules` WHERE `module` = 'rootlicense_addons' && `setting`='Email'");
    $email = mysql_fetch_array($result);

    $result2 = mysql_query("SELECT `value` FROM `tbladdonmodules` WHERE `module` = 'rootlicense_addons' && `setting`='Password'");
    $password = mysql_fetch_array($result2);

    if (empty($email)) {
        return "Please Active Your RootLicense Addon!";
    }
    $postfield['email'] = $email['value'];
    $postfield['password'] = $password['value'];
    $postfield['serviceid'] = $params['serviceid'];
    $ip = $params["customfields"]["IP"];

    if (empty($ip)) {
        return "Ip address is not filled OR there is no customfield named (IP)";
    }
    $postfield['IP'] = $params['customfields']['IP'];

// send to the rootlicense severe

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://apinew.rootlicense.com/api/v1/directadmin/renew');
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
    if ($jsonData["result"] == "success") {
        return 'success';
    } else {

        return $jsonData["message"];
    }
}

function RootLicense_DirectAdmin_ClientArea($params){
    $result = mysql_query("SELECT `value` FROM `tbladdonmodules` WHERE `module` = 'rootlicense_addons' && `setting`='Email'");
    $email = mysql_fetch_array($result);

    $result2 = mysql_query("SELECT `value` FROM `tbladdonmodules` WHERE `module` = 'rootlicense_addons' && `setting`='Password'");
    $password = mysql_fetch_array($result2);

    if (empty($email)) {
        return "Please Active Your RootLicense Addon!";
    }
    $postfield['email'] = $email['value'];
    $postfield['password'] = $password['value'];
    $postfield['serviceid'] = $params['serviceid'];
    $ip = $params["customfields"]["IP"];

    if (empty($ip)) {
        return "Ip address is not filled OR there is no customfield named (IP)";
    }
    $postfield['IP'] = $params['customfields']['IP'];

// send to the rootlicense severe

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://apinew.rootlicense.com/api/v1/directadmin/licensedetails');
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
    if ($jsonData["result"] == "success") {
        $info  = '<div class="table-responsive">';
        $info  .= '<table class="table">';
        $info  .= '<tr><strong>License Details</strong></tr>';
        $info .= '<tr><td>License Name: </td><td>' . $jsonData['nameinlicense'] . '</td></tr>';
        $info .= '<tr><td>Avtive IP: </td><td>' . $jsonData['ip'] . '</td></tr>';
        $info .= '<tr><td>LID : </td><td>' . $params['username'] . '</td></tr>';
        $info .= '<tr><td>CID : </td><td>' . $params['ns1'] . '</td></tr>';
        $info .= '<tr><td>Operating System: </td><td>' . $jsonData['os'] . '</td></tr>';
        $info .= '</table>';
        $info .= '</div>';
    } else {

        $info = '<div class="alert alert-danger" role="alert">License is not active. Contact site administrator.'.$jsonData["message"].'</div>';
    }
    return $info;
}
