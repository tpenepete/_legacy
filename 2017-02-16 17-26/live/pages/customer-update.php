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

        

        //debug($_POST);
		
		if(isset($_POST['hid_customer_info']) && $_POST['hid_customer_info'] == 'yes') {
			
		 $open = 'cus_info';	
		
		 $q = "UPDATE temp_customers

              SET first_name = '".htmlspecialchars($_POST['f_name'])."',

                  last_name = '".htmlspecialchars($_POST['l_name'])."',

                  company_name = '".htmlspecialchars($_POST['c_name'])."',

                  email_id = '".htmlspecialchars($_POST['email'])."',
				  
				  phone_number = '".htmlspecialchars($_POST['p_number'])."',

                 fax_number = '".htmlspecialchars($_POST['f_number'])."'
				 
				 WHERE id = '".$_POST['id']."'";
			
		}
		
		if(isset($_POST['hid_change_pass']) && $_POST['hid_change_pass'] == 'yes') {
			
		  $open = 'change_password';	
		
		  $q .= "UPDATE temp_customers

              SET password = '".md5($_POST['new_password'])."' WHERE id = '".$_POST['id']."'";
			  
		}
		
		if(isset($_POST['hid_change_billing_address']) && $_POST['hid_change_billing_address'] == 'yes') {
			
		 $open = 'billing_shipping';	
		
		 $q = "UPDATE temp_customers

              SET  street = '".htmlspecialchars($_POST['street'])."',

				   id_city = '".htmlspecialchars($_POST['select_cities'])."',
				   
				   id_state = '".htmlspecialchars($_POST['select_state'])."',
	
				   country = '".htmlspecialchars($_POST['country'])."',
	
				   zip = '".htmlspecialchars($_POST['zip'])."'
				   
				   WHERE id = '".$_POST['id']."'";
			
		}
		
		if(isset($_POST['hid_change_shipping_address']) && $_POST['hid_change_shipping_address'] == 'yes') {
			
		 $open = 'billing_shipping';		
		
		 $q = " UPDATE temp_customers

              SET s_firstname = '".htmlspecialchars($_POST['f_name'])."',

                 s_lastname = '".htmlspecialchars($_POST['l_name'])."',

                 s_street = '".htmlspecialchars($_POST['s_street'])."',
				 
				 s_city = '".htmlspecialchars($_POST['select_cities_s'])."',
				 
				 s_state = '".htmlspecialchars($_POST['select_state_s'])."',
				 
				 s_country = '".htmlspecialchars($_POST['s_country'])."',
				 
				 s_zip = '".htmlspecialchars($_POST['s_zip'])."'
				 
				 WHERE id = '".$_POST['id']."'";
				 
		}

        $res = mysql_query($q);  
        
        if (!$res) 
        echo mysql_error();
        else 
        header("Location: http://www.amazingwristbands.com/customer-account?&update=successful&open=".$open);  

    }



    

?>