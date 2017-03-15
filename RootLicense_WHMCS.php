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
function RootLicense_WHMCS_ConfigOptions()
{
    $configarray = array(
        "LicenseType" => array(
            "FriendlyName" => "TypeLicense",
            "Type" => "dropdown",
            "Options" => "Branded WHMCS license,Not branded WHMCS license",
            "Description" => "Please select one of the options"
        ),
    );
    return $configarray;
}
function RootLicense_WHMCS_CreateAccount(array $params)
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
    $postfield['type']=$params['configoption1'];

    $result3 = mysql_query("SELECT `billingcycle` FROM `tblhosting` WHERE `id` = $serviceid ");
    $billingcycle = mysql_fetch_array($result3);
    $postfield['billing'] = $billingcycle['billingcycle'];

// send to the rootlicense severe

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://apinew.rootlicense.com/api/v1/whmcs/add');
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

        update_query('tblhosting', array('domain' => $jsonData['message']),
            array('id' => $params["serviceid"],));

        return 'success';
    } else {

        return $jsonData["message"];
    }
}

function RootLicense_WHMCS_SuspendAccount(array $params)
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


// send to the rootlicense severe

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://apinew.rootlicense.com/api/v1/whmcs/suspend');
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

function RootLicense_WHMCS_UnsuspendAccount(array $params)
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


// send to the rootlicense severe

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://apinew.rootlicense.com/api/v1/whmcs/unsuspend');
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
        update_query('tblhosting', array('domain' => $jsonData['message']),
            array('id' => $params["serviceid"],));
        return 'success';
    } else {

        return $jsonData["message"];
    }

}

function RootLicense_WHMCS_TerminateAccount(array $params)
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


// send to the rootlicense severe

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://apinew.rootlicense.com/api/v1/whmcs/terminate');
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

function RootLicense_WHMCS_Renew(array $params)
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

// send to the rootlicense severe

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://apinew.rootlicense.com/api/v1/whmcs/renew');
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

function RootLicense_WHMCS_ClientArea($params){
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
    $postfield['license'] = $params['domain'];

// send to the rootlicense severe

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://apinew.rootlicense.com/api/v1/whmcs/licensedetails');
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
        $info .= '<tr><td>License: </td><td>' . $jsonData['key'][0] . '</td></tr>';
        $info .= '<tr><td>Active Domain: </td><td>' . $jsonData['validdomain'][0] . '</td></tr>';
        $info .= '<tr><td>Active IP: </td><td>' . $jsonData['validip'][0] . '</td></tr>';
        $info .= '<tr><td>Installed Directory: </td><td>' . $jsonData['validdirectory'][0] . '</td></tr>';
        $info .= '<tr><td>Type: </td><td>' . $jsonData['type'] . '</td></tr>';
        $info .= '<tr><td>Status: </td><td>' . $jsonData['status'][0] . '</td></tr>';
        $info .= '</table>';
        $info .= '</div>';
        if($_GET['reissue'] == '1'){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://apinew.rootlicense.com/api/v1/whmcs/reissue');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postfield));
            $response = curl_exec($ch);
            if (curl_error($ch)) {
                die('Unable to connect: ' . curl_errno($ch) . ' - ' . curl_error($ch));
            }
            curl_close($ch);
            $res = json_decode($response);
            if($res->result == "success"){
                $info .= '<div class="alert alert-success"><strong>Operation has been completed successfully.</strong></div>';
                $info .= '<a href="' . $params["systemurl"] . '/clientarea.php?action=productdetails&id=' . $params["serviceid"].'" class="btn btn-success" >Continue</a>';
            }
            else {
                $info .= '<div class="alert alert-danger"><strong>Operation has failed. Please contact our support team.</strong></div>';
            }

        }
        else {
            $info .= '<div class="jumbotron">';
            $info .= '<p>Reissue the license if you would like to use it on another domain.</p>';
            $info .= '<a href="' . $params["systemurl"] . '/clientarea.php?action=productdetails&id=' . $params["serviceid"] .'&reissue=1" class="btn btn-success">Reissue</a>';
            $info .= '</div>';
        }
    } else {

        $info = '<div class="alert alert-danger" role="alert">License is not active. Please contact our support team.</div>';
    }
    return $info;
}
