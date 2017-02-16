<?php

require(TPT_ROOT_DIR."/live/includes/define.php");
    function debug($obj)
    {
        print('<pre>');
            print_r($obj);
        print('</pre>');
    }

    if (!empty($_POST))
    {
        if (!isset ($_POST['ship']))
        {
            if ($_POST['s_street'] == '')
                $_POST['s_street'] = $_POST['street'];
            if ($_POST['select_cities_s'] == '0')
                $_POST['select_cities_s'] = $_POST['select_cities'];             
            if ($_POST['select_state_s'] == '0')
                $_POST['select_state_s'] = $_POST['select_state'];
            if ($_POST['s_country'] == '')
                $_POST['s_country'] = $_POST['country'];              
            if ($_POST['s_zip'] == '')
                $_POST['s_zip'] = $_POST['zip'];
        }

        //debug($_POST);die();     
        $q = "INSERT INTO temp_customers 
                (first_name,
                 last_name,
                 company_name,
                 email_id,
                 password,
                 phone_number,
                 fax_number,
                 street,
                 id_city,
                 s_state,
                 s_city,
                 country,
                 zip,
                 s_firstname,
                 s_lastname,
                 s_street,
                 id_state,
                 s_country,
                 s_zip) 
              VALUES ('".htmlspecialchars($_POST['f_name'])."', 
                      '".htmlspecialchars($_POST['l_name'])."', 
                      '".htmlspecialchars($_POST['c_name'])."', 
                      '".htmlspecialchars($_POST['email'])."', 
                      '".md5($_POST['password'])."', 
                      '".htmlspecialchars($_POST['p_number'])."', 
                      '".htmlspecialchars($_POST['f_number'])."', 
                      '".htmlspecialchars($_POST['street'])."', 
                      '".htmlspecialchars($_POST['select_cities'])."', 
                      '".htmlspecialchars($_POST['select_state_s'])."',  
                      '".htmlspecialchars($_POST['select_cities_s'])."', 
                      '".htmlspecialchars($_POST['country'])."',  
                      '".htmlspecialchars($_POST['zip'])."', 
                      '".htmlspecialchars($_POST['f_name'])."', 
                      '".htmlspecialchars($_POST['l_name'])."', 
                      '".htmlspecialchars($_POST['s_street'])."', 
                      '".htmlspecialchars($_POST['select_state'])."', 
                      '".htmlspecialchars($_POST['s_country'])."', 
                      '".htmlspecialchars($_POST['s_zip'])."')";
        $res = mysql_query($q);  
        if (!$res) 
            echo mysql_error();
        else 
            header("Location: http://www.amazingwristbands.com/live/customer-login?&reg=successful");  
    }
?>