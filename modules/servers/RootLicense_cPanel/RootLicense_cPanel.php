<?php

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
if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}
function RootLicense_cPanel_ConfigOptions()
{
    $configarray = array(
        // the dropdown field type renders a select menu of options
        "configoption" => array(
            "FriendlyName" => "cPaneltype",
            'Type' => 'dropdown',
            'Options' => "cPanel for VPS,cPanel for Dedicated Server"
        ),
    );
    return $configarray;
}

function RootLicense_cPanel_CreateAccount(array $params)
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
    $postfield['action'] = 'action';
    $postfield['proudct'] = $params['configoption1'];
    $ip = $params["customfields"]["IP"];

    if (empty($ip)) {
        return "Ip address is not filled OR there is no customfield named (IP)";
    }
    $postfield['IP'] = $params['customfields']['IP'];
    $result3 = mysql_query("SELECT `billingcycle` FROM `tblhosting` WHERE `id` = $serviceid ");
    $billingcycle = mysql_fetch_array($result3);
    $postfield['biling'] = $billingcycle['billingcycle'];

// send to the rootlicense severe

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://apinew.rootlicense.com/api/v1/addcpanel');
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
        update_query('tblhosting', array('domain' => $params['customfields']['IP'],),
            array('id' => $params["serviceid"],));
        return 'success';
    } else {
        return $jsonData["message"];
    }
}

function RootLicense_cPanel_SuspendAccount(array $params)
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
    $postfield['proudct'] = $params['configoption1'];
    $ip = $params["customfields"]["IP"];

    if (empty($ip)) {
        return "Ip address is not filled OR there is no customfield named (IP)";
    }
    $postfield['IP'] = $params['customfields']['IP'];
    $result3 = mysql_query("SELECT `billingcycle` FROM `tblhosting` WHERE `id` = $serviceid ");
    $billingcycle = mysql_fetch_array($result3);
    $postfield['biling'] = $billingcycle['billingcycle'];

// send to the rootlicense severe

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://apinew.rootlicense.com/api/v1/suspend');
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

function RootLicense_cPanel_UnsuspendAccount(array $params)
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
    $postfield['proudct'] = $params['configoption1'];
    $ip = $params["customfields"]["IP"];

    if (empty($ip)) {
        return "Ip address is not filled OR there is no customfield named (IP)";
    }
    $postfield['IP'] = $params['customfields']['IP'];
    $result3 = mysql_query("SELECT `billingcycle` FROM `tblhosting` WHERE `id` = $serviceid ");
    $billingcycle = mysql_fetch_array($result3);
    $postfield['biling'] = $billingcycle['billingcycle'];

// send to the rootlicense severe

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://apinew.rootlicense.com/api/v1/unsuspend');
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

function RootLicense_cPanel_TerminateAccount(array $params)
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
    $postfield['action'] = 'action';
    $postfield['proudct'] = $params['configoption1'];
    $ip = $params["customfields"]["IP"];

    if (empty($ip)) {
        return "Ip address is not filled OR there is no customfield named (IP)";
    }
    $postfield['IP'] = $params['customfields']['IP'];
    $result3 = mysql_query("SELECT `billingcycle` FROM `tblhosting` WHERE `id` = $serviceid ");
    $billingcycle = mysql_fetch_array($result3);
    $postfield['biling'] = $billingcycle['billingcycle'];

// send to the rootlicense severe

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://apinew.rootlicense.com/api/v1/terminate');
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

function RootLicense_cPanel_Renew(array $params)
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
    $postfield['action'] = 'action';
    $postfield['proudct'] = $params['configoption1'];
    $ip = $params["customfields"]["IP"];

    if (empty($ip)) {
        return "Ip address is not filled OR there is no customfield named (IP)";
    }
    $postfield['IP'] = $params['customfields']['IP'];
    $result3 = mysql_query("SELECT `billingcycle` FROM `tblhosting` WHERE `id` = $serviceid ");
    $billingcycle = mysql_fetch_array($result3);
    $postfield['biling'] = $billingcycle['billingcycle'];

// send to the rootlicense severe

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://apinew.rootlicense.com/api/v1/renew');
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

function RootLicense_cPanel_ClientArea($params)
{

    if (!$_POST['ip']) {
        $code = '<form method="post" action="' . $params["systemurl"] . '/clientarea.php?action=productdetails&id=' . $params["serviceid"] . '" class="form-inline" role="form">';
        $code .= '<input type="hidden" name="id" value="' . $params["serviceid"] . '">';
        $code .= "<table><tr><td>New IP: </td><td><input type='text' name='ip' value='" . $params["customfields"]["IP"] . "'></td>";
        $code .= '<td><input type="submit" class="btn btn-default" value="Change IP">';
        $code .= '</td></tr></table></form>';
        return $code;
    } else {
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
        $postfield['action'] = 'action';
        $postfield['proudct'] = $params['configoption1'];
        $postfield['newip'] = $_POST['ip'];
        $ip = $params["customfields"]["IP"];

        if (empty($ip)) {
            return "Ip address is not filled OR there is no customfield named (IP)";
        }
        $postfield['IP'] = $params['customfields']['IP'];
        $result3 = mysql_query("SELECT `billingcycle` FROM `tblhosting` WHERE `id` = $serviceid ");
        $billingcycle = mysql_fetch_array($result3);
        $postfield['biling'] = $billingcycle['billingcycle'];

// send to the rootlicense severe

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://apinew.rootlicense.com/api/v1/cpanel/changeip');
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
            update_query('tblhosting', array('domain' => $_POST['ip'],),
                array('id' => $params["serviceid"],));
            update_query('tblcustomfieldsvalues', array('value' => $_POST['ip'],),
                array('value' => $params['customfields']['IP'], 'relid' => $params["serviceid"],));
            $code = "<a href='" . $params["systemurl"] . "/clientarea.php?action=productdetails&id=" . $params["serviceid"] . "'><span class='badge'>Your license IP has been changed successfully. please click here</span></a>";
            return $code;
        } else {

            return $jsonData["message"];
        }
    }
}

?>