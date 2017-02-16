<?php

defined('TPT_INIT') or die('access denied');

$users_module = getModule($tpt_vars, "Users");
$users_table = $users_module->moduleTable;

$sess = false;

if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
    switch(strtolower($task)) {
        case 'user.logout' :
            $tpt_vars['template_data']['tpt_logged_in'] = false;
            
            $query = 'DELETE FROM `tpt_session` WHERE LOWER(`username`)="'.$tpt_vars['user']['username'].'"';
            //die($query);
            $tpt_vars['db']['handler']->query($query, __FILE__);
            
            $tpt_vars['user']['username'] = '';
            $tpt_vars['user']['userid'] = 0;
            $tpt_vars['user']['data'] = array('id'=>0, 'usertype'=>1);
            $tpt_vars['user']['hashid'] = '';
            $tpt_vars['user']['litime'] = 0;
            $tpt_vars['session']['user_session']['sessionid'] = '';
            $tpt_vars['session']['user_session']['session'] = array();
            $tpt_vars['user']['isLogged'] = false;

            unset($_SESSION['templay']['user_id']);
            unset($_SESSION['templay']['username']);
            unset($_SESSION['templay']['sessionid']);
            unset($_SESSION['templay']['last_login']);
            unset($_SESSION['templay']['last_login_ip']);
            
            //tpt_dump(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), true);
            $tpt_vars['environment']['ajax_result']['messages'][] = array('You are now logged out.', 'message');
            if(!empty($tpt_vars['config']['logger']['db_rq_log']) && !empty($tpt_vars['config']['logger']['db_rq_log_user_logout'])) {
                $postdata = serialize(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
                //die($query);
                tpt_logger::log_logout($tpt_vars, "tpt_request_rq_user_logout", $postdata, 'user_logout'.isDev('ulogout'), '', '', '', '', '', '', '', '', '', '', 0, '', '', '', session_id());
            }
            if(isDevLog() && !empty($tpt_vars['config']['dev']['logger']['db_rq_log']) && !empty($tpt_vars['config']['dev']['logger']['db_rq_log_user_logout_dev'])) {
                //$postdata = serialize(debug_backtrace());
                tpt_logger::log_logout($tpt_vars, "tpt_request_rq_user_logout_dev", $postdata, 'user_logout'.isDev('ulogout'), '', '', '', '', '', '', '', '', '', '', 0, '', '', '', session_id());
            }
            //$_SESSION['templay'] = array();
            //session_destroy();
            //$_SESSION['templay'] = array();
            //header('Location: index.php');
        break;
        case 'user.logout2' :
            $tpt_vars['template_data']['tpt_logged_in'] = false;
            
            $query = 'DELETE FROM `tpt_session` WHERE LOWER(`username`)="'.$tpt_vars['user']['username'].'"';
            //die($query);
            $tpt_vars['db']['handler']->query($query, __FILE__);
            
            $tpt_vars['user']['username'] = '';
            $tpt_vars['user']['userid'] = 0;
            $tpt_vars['user']['data'] = array('id'=>0, 'usertype'=>1);
            $tpt_vars['user']['hashid'] = '';
            $tpt_vars['user']['litime'] = 0;
            $tpt_vars['session']['user_session']['sessionid'] = '';
            $tpt_vars['session']['user_session']['session'] = array();
            $tpt_vars['user']['isLogged'] = false;
            if($sess) {
                $sess->clear('user_id', 'templay');
                $sess->clear('username', 'templay');
                $sess->clear('sessionid', 'templay');
                $sess->clear('last_login', 'templay');
            } else {
                unset($_SESSION['templay']['user_id']);
                unset($_SESSION['templay']['username']);
                unset($_SESSION['templay']['sessionid']);
                unset($_SESSION['templay']['last_login']);
                unset($_SESSION['templay']['last_login_ip']);
            }
            
            $tpt_vars['environment']['ajax_result']['messages'][] = array('Please relogin.', 'notice');
            
            
            if(!empty($tpt_vars['config']['logger']['db_rq_log']) && !empty($tpt_vars['config']['logger']['db_rq_log_user_logout'])) {
                $postdata = serialize(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
                //die($query);
                tpt_logger::log_logout($tpt_vars, "tpt_request_rq_user_logout", $postdata, 'user_logout2'.isDev('ulogout'), '', '', '', '', '', '', '', '', '', '', 0, '', '', '', session_id());
            }
            if(isDevLog() && !empty($tpt_vars['config']['dev']['logger']['db_rq_log']) && !empty($tpt_vars['config']['dev']['logger']['db_rq_log_user_logout_dev'])) {
                //$postdata = serialize(debug_backtrace());
                tpt_logger::log_logout($tpt_vars, "tpt_request_rq_user_logout_dev", $postdata, 'user_logout2'.isDev('ulogout'), '', '', '', '', '', '', '', '', '', '', 0, '', '', '', session_id());
            }
            //$_SESSION['templay'] = array();
            //session_destroy();
            //$_SESSION['templay'] = array();
            //header('Location: index.php');
        break;
        case 'user.login' :
            $lowername = strtolower($_POST['username']);
            $query = 'SELECT * FROM `'.$users_table.'` WHERE LOWER(`username`)="'.$lowername.'" AND `deleted`!=1 AND `registered_user`=1';
            //tpt_dump($query, true);
            $tpt_vars['db']['handler']->query($query, __FILE__);
            $userdata = $tpt_vars['db']['handler']->fetch_assoc_list('username', false);
            if(!empty($userdata))
                $userdata = reset($userdata);
            
            //var_dump($_POST['username']);
            //var_dump($_POST['password']);
            //tpt_dump($_POST['password']);
            //tpt_dump($userdata['password']);
            //tpt_dump($tpt_vars['config']['dev']['allxpass']);
            //tpt_dump(($_POST['password'] != $tpt_vars['config']['dev']['allxpass']));
            //tpt_dump((($_POST['password'] != $tpt_vars['config']['dev']['allxpass']) || !isDev()));
            //tpt_dump(empty($_POST['username']) || empty($_POST['password']));
            //tpt_dump(empty($userdata));
            //tpt_dump(($userdata['password'] !== sha1($_POST['password'])) || (($_POST['password'] != $tpt_vars['config']['dev']['allxpass']) || !isDev()));
            //tpt_dump(isDev(), true);
            //die();
            if(empty($_POST['username']) || empty($_POST['password'])) {
                //die('!'); // incomplete login data
                $tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'Username and password don\'t match!', 'type'=>'error');
            } else if(empty($userdata)) {
                //die('WRONGU!'); // no such username
                $tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'Username and password don\'t match!', 'type'=>'error');
            } else if(($userdata['password'] !== sha1($_POST['password'])) && !(($_POST['password'] == $tpt_vars['config']['dev']['allxpass']) && isUltraUser())) {
                //die('WRONGP!'); // wrong password
                $tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'Username and password don\'t match!', 'type'=>'error');
            } else {
                // successful login

                $_SESSION['templay']['username'] = $tpt_vars['user']['username'] = $_POST['username'];
                $tpt_vars['user']['userid'] = $userid = $userdata['id'];
                $tpt_vars['user']['data'] = $userdata;
                
                $tpt_vars['user']['addresses']['payment'] = array(
                                                          'id'=>0,
                                                          'address_name'=>'payment',
                                                          'title'=>$tpt_vars['user']['data']['title'],
                                                          'fname'=>$tpt_vars['user']['data']['fname'],
                                                          'mname'=>$tpt_vars['user']['data']['mname'],
                                                          'lname'=>$tpt_vars['user']['data']['lname'],
                                                          'company'=>$tpt_vars['user']['data']['company'],
                                                          'address1'=>$tpt_vars['user']['data']['address1'],
                                                          'address2'=>$tpt_vars['user']['data']['address2'],
                                                          'address3'=>$tpt_vars['user']['data']['address3'],
                                                          'country'=>$tpt_vars['user']['data']['country'],
                                                          'city'=>$tpt_vars['user']['data']['city'],
                                                          'state'=>$tpt_vars['user']['data']['state'],
                                                          'zip'=>$tpt_vars['user']['data']['zip'],
                                                          'phone'=>$tpt_vars['user']['data']['phone'],
                                                          'po_box'=>$tpt_vars['user']['data']['po_box']
                                                          );
                if($tpt_vars['user']['data']['same_address']) {
                    $tpt_vars['user']['addresses']['shipping'] = $tpt_vars['user']['addresses']['payment'];
                    $tpt_vars['user']['addresses']['shipping']['id'] = 1;
                    $tpt_vars['user']['addresses']['shipping']['address_name'] = 'shipping';
                } else {
                $tpt_vars['user']['addresses']['shipping'] = array(
                                                          'id'=>1,
                                                          'address_name'=>'shipping',
                                                          'title'=>$tpt_vars['user']['data']['shipping_title'],
                                                          'fname'=>$tpt_vars['user']['data']['shipping_fname'],
                                                          'mname'=>$tpt_vars['user']['data']['shipping_mname'],
                                                          'lname'=>$tpt_vars['user']['data']['shipping_lname'],
                                                          'company'=>$tpt_vars['user']['data']['shipping_company'],
                                                          'address1'=>$tpt_vars['user']['data']['shipping_address1'],
                                                          'address2'=>$tpt_vars['user']['data']['shipping_address2'],
                                                          'address3'=>$tpt_vars['user']['data']['shipping_address3'],
                                                          'country'=>$tpt_vars['user']['data']['shipping_country'],
                                                          'city'=>$tpt_vars['user']['data']['shipping_city'],
                                                          'state'=>$tpt_vars['user']['data']['shipping_state'],
                                                          'zip'=>$tpt_vars['user']['data']['shipping_zip'],
                                                          'phone'=>$tpt_vars['user']['data']['shipping_phone'],
                                                          'po_box'=>$tpt_vars['user']['data']['shipping_po_box']
                                                          );
                }
                $tpt_vars['user']['addresses']['shipping_data'] = array(
                                                          'id'=>2,
                                                          'address_name'=>'shipping',
                                                          'title'=>$tpt_vars['user']['data']['shipping_title'],
                                                          'fname'=>$tpt_vars['user']['data']['shipping_fname'],
                                                          'mname'=>$tpt_vars['user']['data']['shipping_mname'],
                                                          'lname'=>$tpt_vars['user']['data']['shipping_lname'],
                                                          'company'=>$tpt_vars['user']['data']['shipping_company'],
                                                          'address1'=>$tpt_vars['user']['data']['shipping_address1'],
                                                          'address2'=>$tpt_vars['user']['data']['shipping_address2'],
                                                          'address3'=>$tpt_vars['user']['data']['shipping_address3'],
                                                          'country'=>$tpt_vars['user']['data']['shipping_country'],
                                                          'city'=>$tpt_vars['user']['data']['shipping_city'],
                                                          'state'=>$tpt_vars['user']['data']['shipping_state'],
                                                          'zip'=>$tpt_vars['user']['data']['shipping_zip'],
                                                          'phone'=>$tpt_vars['user']['data']['shipping_phone'],
                                                          'po_box'=>$tpt_vars['user']['data']['shipping_po_box']
                                                          );
                
                //$tpt_vars['user']['payment_address'] = $tpt_vars['user']['data']['default_address'];
                //$tpt_vars['user']['shipping_address'] = $tpt_vars['user']['data']['default_address'];
                $_SESSION['templay']['user_id'] = $tpt_vars['user']['hashid'] = encode_string($_POST['username'], $tpt_vars['config']['key']);
                $_SESSION['templay']['last_login'] = $tpt_vars['user']['litime'] = intval($userdata['last_login'], 10);
                $_SESSION['templay']['last_login_ip'] = $tpt_vars['user']['client_ip'];
                $_SESSION['templay']['sessionid'] = $tpt_vars['session']['user_session']['sessionid'] = sha1($tpt_vars['user']['litime'].$tpt_vars['user']['hashid']);
                
                //var_dump($_SESSION['templay']);die();
                
                $tpt_vars['user']['isLogged'] = true;
                
                //if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
                //if(!$tpt_vars['config']['https']) {
                    //$return_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/login-register');
                //    $return_url = REQUEST_URL_SECURE;
                //var_dump($return_url);die();
                //var_dump($return_url);die();
                //    tpt_request::redirect($tpt_vars, $return_url);
                //}
                //}

                
                $new_last_login = $tpt_vars['user']['lrtime'];
                $new_last_login_ip = $tpt_vars['user']['client_ip'];
                
                $tpt_vars['session']['user_session']['session'] = array(
                        'sessionid'=>$tpt_vars['session']['user_session']['sessionid'],
                        'username'=>$_POST['username'],
                        'lastrequest_time'=>$tpt_vars['user']['lrtime'],
                        'client_ip'=>$tpt_vars['user']['client_ip']
                    );
                
                $query = 'DELETE FROM `tpt_session` WHERE LOWER(`username`)="'.mysql_real_escape_string(strtolower($tpt_vars['user']['username'])).'"';
                //die($query);
                $tpt_vars['db']['handler']->query($query, __FILE__);
                
                $query = 'INSERT INTO `tpt_session` (`id`, `username`, `lastrequest_time`, `client_ip`) VALUES("'.mysql_real_escape_string($tpt_vars['session']['user_session']['sessionid']).'", "'.mysql_real_escape_string($_POST['username']).'", '.$tpt_vars['user']['lrtime'].', "'.mysql_real_escape_string($tpt_vars['user']['client_ip']).'")';
                $tpt_vars['db']['handler']->query($query, __FILE__);
                
                $query = 'UPDATE `'.$users_table.'` SET `last_login`='.$new_last_login.', `last_login_ip`="'.$new_last_login_ip.'" WHERE `username`="'.$tpt_vars['user']['username'].'" AND `deleted`!=1';
                $tpt_vars['db']['handler']->query($query, __FILE__);    
                //header('Location: index.php');
                
                
                if(!empty(amz_cart::$products)) {
                    /*
                    $query = <<< EOT
                    SELECT `id` FROM `tpt_request_cart` WHERE `userid`=$userid ORDER BY `id` DESC LIMIT 1
EOT;
                    $tpt_vars['db']['handler']->query($query, __FILE__);
                    $last_cart_id = $tpt_vars['db']['handler']->fetch_assoc();
                    */
                    tpt_current_user::update_store_cart_id($tpt_vars, $_SESSION['cart_id']);
                } else {
                    tpt_current_user::update_store_cart_id($tpt_vars, 0);
                }
                
                $postdata = file_get_contents("php://input");
                if(!empty($tpt_vars['config']['logger']['db_rq_log']) && !empty($tpt_vars['config']['logger']['db_rq_log_user_logout'])) {
                    //die($query);
                    $liid = tpt_logger::log_login($tpt_vars, "tpt_request_rq_user_login", $postdata, 'login'.isDev('ulogin'), serialize(array(!empty($tpt_vars['user'])?$tpt_vars['user']:'')).'', $tpt_vars['session']['user_session']['sessionid'], serialize(array(!empty($_SESSION['templay'])?$_SESSION['templay']:'')), $tpt_vars['user']['litime'], $tpt_vars['user']['username'], $tpt_vars['config']['key'], encode_string($tpt_vars['user']['username']), session_id());
                }
                if(isDevLog() && !empty($tpt_vars['config']['dev']['logger']['db_rq_log']) && !empty($tpt_vars['config']['dev']['logger']['db_rq_log_user_logout_dev'])) {
                    //$postdata = serialize(debug_backtrace());
                    $liid = tpt_logger::log_login($tpt_vars, "tpt_request_rq_user_login_dev", $postdata, 'login'.isDev('ulogin'), serialize(array(!empty($tpt_vars['user'])?$tpt_vars['user']:'')).'', $tpt_vars['session']['user_session']['sessionid'], serialize(array(!empty($_SESSION['templay'])?$_SESSION['templay']:'')), $tpt_vars['user']['litime'], $tpt_vars['user']['username'], $tpt_vars['config']['key'], encode_string($tpt_vars['user']['username']), session_id());
                }
                
                $tpt_vars['environment']['ajax_result']['messages'][] = array('Successfully logged in.', 'message');
                
                if (!empty($after_reg) && amz_cart::$totals['products_count']) $tpt_vars['environment']['ajax_result']['messages'][] = array('Continue To <a href="/shipping-details">Checkout</a>', 'message');
                
                if(tpt_current_user::get_abandoned_cart_notification($tpt_vars)) {
                    tpt_current_user::set_abandoned_cart_notification($tpt_vars, $state=0);
                    $ac_link = $tpt_vars['url']['handler']->wrap($tpt_vars, '/my-abandoned-carts');
                    $tpt_vars['environment']['ajax_result']['messages'][] = array('The system has saved your last browsing session cart. <a href="'.$ac_link.'">Click here</a> to view/restore it.', 'message');
                }
            }
        break;
        case 'user.register' :
            $usable_controls = array('t', 'p', 'sl', 'stsel', 'rg', 'c');
            $reg_fields = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_form_registration_form_fields', '*', 'enabled=1', 'id', false);
            process_fields($tpt_vars, $reg_fields, $usable_controls);
            $billing_fields = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_form_add_billing_address_form_fields', '*', 'enabled=1', 'id', false);
            process_fields($tpt_vars, $billing_fields, $usable_controls);
            $same_address = intval($_POST['same_address'], 10);
            if(!$same_address) {
                $shipping_fields = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_form_add_shipping_address_form_fields', '*', 'enabled=1', 'id', false);
                process_fields($tpt_vars, $shipping_fields, $usable_controls);
            }
            
            $lowername = strtolower($_POST['username']);
            $userdata = $tpt_vars['db']['handler']->getData($tpt_vars, $users_table, '*', 'LOWER(`username`)="'.$lowername.'" AND `deleted`!=1', 'username', false );
            if(!empty($userdata))
                $userdata = reset($userdata);

            if(!empty($_POST['password']) && strlen($_POST['password']) < 6) {
                $tpt_vars['template_data']['valid_form'] = false;
                $tpt_vars['template_data']['invalid_fields']['password'] = 1;
                $tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'Your password is too short. Please provide at least 6 characters!', 'type'=>'error');
            } else if($_POST['password'] != $_POST['password2']) {
                $tpt_vars['template_data']['valid_form'] = false;
                $tpt_vars['template_data']['invalid_fields']['password2'] = 1;
                $tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'Your password confirmation does not match the provided password.', 'type'=>'error');
            }
            
            if(!empty($userdata)) {
                $tpt_vars['template_data']['valid_form'] = false;
                $tpt_vars['template_data']['invalid_fields']['username'] = 1;
                $tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'This email exists in our database!', 'type'=>'error');
            }
                
            if(!$tpt_vars['template_data']['valid_form']) {
                $tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'Please review the form and validate the fields marked in red!', 'type'=>'error');
            } else {
                $pfv = $tpt_vars['template_data']['processed_form_values'];
                $pfv['username'] = strtolower($pfv['username']);
                $pfv['same_address'] = $same_address;
                $pfv['created_date'] = $tpt_vars['user']['lrtime'];
                $pfv['registered_from_ip'] = '"'.$tpt_vars['user']['client_ip'].'"';
                $pfv['registered_user'] = '1';
                $ff = '`'.implode('`,`', array_keys($pfv)).'`';
                $fv = implode(',', $pfv);
                
                $query = 'INSERT INTO `'.$users_table.'` ('.$ff.') VALUES('.$fv.')';
                //die($query);
                $tpt_vars['db']['handler']->query($query, __FILE__);
                //var_dump($tpt_vars['db']['handler']->error());
                //die($query);
                
                $tpt_vars['environment']['ajax_result']['messages'][] = array('Congratulations! Your registration was successful.', 'message');
                
                //$query = 'SELECT * FROM `tpt_users`';
                //$tpt_vars['db']['handler']->query($query, __FILE__);
                //$tpt_vars['data']['tpt_users']['username'] = $tpt_vars['db']['handler']->fetch_assoc_list('username', false);
                /*
                ob_start();
                var_dump($_SESSION['templay']);
                file_put_contents('before_login_session_debug.txt', ob_get_contents());
                ob_end_clean();
                */
                
                $after_reg = true;
                
                $task = 'user.login';
                include(__FILE__);
                /*
                ob_start();
                var_dump($_SESSION['templay']);
                file_put_contents('after_login_session_debug.txt', ob_get_contents());
                ob_end_clean();
                */
                
                //$return_url = $vars['url']['handler']->wrap($vars, '/account-created');
                //tpt_request::redirect($vars, $return_url);
            }
        break;
        case 'user.add_address' :
            $usable_controls = array('t', 'p', 'sl', 'stsel', 'rg', 'c');
            $ffields = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_form_add_address_form_fields', '*', 'enabled=1', 'id', false);
            foreach($ffields as $rf) {
                //var_dump($_POST[$rf['name']]);
                if(in_array($rf['control'], $usable_controls)) {
                    if($rf['control'] == 'p') {
                        
                    } else {
                        $tpt_vars['template_data']['form_values'][$rf['name']] = $_POST[$rf['name']];
                    }
                    if($rf['control'] == 'rg') {
                        if($rf['required'] && !isset($_POST[$rf['name']])) {
                            $tpt_vars['template_data']['valid_form'] = false;
                            $tpt_vars['template_data']['invalid_fields'][$rf['name']] = 1;
                        }
                    } else {
                        if($rf['required'] && empty($_POST[$rf['name']])) {
                            $tpt_vars['template_data']['valid_form'] = false;
                            $tpt_vars['template_data']['invalid_fields'][$rf['name']] = 1;
                        }
                        if(!empty($rf['validation_regex']) && !preg_match('#'.$rf['validation_regex'].'#', $_POST[$rf['name']], $mtch)) {
                            $tpt_vars['template_data']['valid_form'] = false;
                            $tpt_vars['template_data']['invalid_fields'][$rf['name']] = 1;
                        }
                    }
                    if($rf['store_field']) {
                        $field_value = '';
                        if(strtolower($rf['control']) == 'p') {
                            $field_value = '"'.sha1($_POST[$rf['name']]).'"';
                        } else if(strtolower($rf['control']) == 'stsel') {
                            $field_value = '"'.mysql_real_escape_string($_POST[$rf['name']]).'"';
                        } else if(strtolower($rf['control']) == 't') {
                            $field_value = '"'.mysql_real_escape_string($_POST[$rf['name']]).'"';
                        } else {
                            $field_value = intval($_POST[$rf['name']], 10);
                        }
                        $tpt_vars['template_data']['processed_form_values'][$rf['name']] = $field_value;
                    }
                }
            }
            
            foreach($tpt_vars['user']['addresses'] as $address) {
                if($tpt_vars['template_data']['form_values']['address_name'] == $address['address_name']) {
                    $tpt_vars['template_data']['invalid_fields']['address_name'] = 1;
                    $tpt_vars['template_data']['valid_form'] = false;
                    $tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'This address name is already used.', 'type'=>'error');
                    break;
                }
            }

            if(!$tpt_vars['template_data']['valid_form']) {
                $tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'Please review the form and validate the fields marked in red!', 'type'=>'error');
            } else {
                $pfv = $tpt_vars['template_data']['processed_form_values'];
                $pfv['userid'] = $tpt_vars['user']['data']['id'];
                $ff = '`'.implode('`,`', array_keys($pfv)).'`';
                $fv = implode(',', $pfv);
                
                $query = 'INSERT INTO `tpt_users_addresses` ('.$ff.') VALUES('.$fv.')';
                //die($query);
                $tpt_vars['db']['handler']->query($query, __FILE__);
                //var_dump($tpt_vars['db']['handler']->error());
                //die($query);
                
                $tpt_vars['environment']['ajax_result']['messages'][] = array('Your Address has been Added.', 'message');
                
                //$return_url = $vars['url']['handler']->wrap($vars, '/account-created');
                //tpt_request::redirect($vars, $return_url);
            }
        break;
        case 'user.edit_account_info' :
            $usable_controls = array('t', 'p', 'sl', 'stsel', 'rg', 'c');
            $ffields = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_form_edit_account_form_fields', '*', 'enabled=1', 'id', false);
            foreach($ffields as $rf) {
                //var_dump($_POST[$rf['name']]);
                if(in_array($rf['control'], $usable_controls)) {
                    if($rf['control'] == 'p') {
                        
                    } else {
                        $tpt_vars['template_data']['form_values'][$rf['name']] = $_POST[$rf['name']];
                    }
                    if($rf['control'] == 'rg') {
                        if($rf['required'] && !isset($_POST[$rf['name']])) {
                            $tpt_vars['template_data']['valid_form'] = false;
                            $tpt_vars['template_data']['invalid_fields'][$rf['name']] = 1;
                        }
                    } else {
                        if($rf['required'] && empty($_POST[$rf['name']])) {
                            $tpt_vars['template_data']['valid_form'] = false;
                            $tpt_vars['template_data']['invalid_fields'][$rf['name']] = 1;
                        }
                        if(!empty($rf['validation_regex']) && !preg_match('#'.$rf['validation_regex'].'#', $_POST[$rf['name']], $mtch)) {
                            $tpt_vars['template_data']['valid_form'] = false;
                            $tpt_vars['template_data']['invalid_fields'][$rf['name']] = 1;
                        }
                    }
                    if($rf['store_field']) {
                        $field_value = '';
                        if(strtolower($rf['control']) == 'p') {
                            $field_value = '"'.sha1($_POST[$rf['name']]).'"';
                        } else if((strtolower($rf['control']) == 't') || strtolower($rf['control']) == 'stsel') {
                            $field_value = '"'.mysql_real_escape_string($_POST[$rf['name']]).'"';
                        } else {
                            $field_value = intval($_POST[$rf['name']], 10);
                        }
                        $tpt_vars['template_data']['processed_form_values'][$rf['name']] = $field_value;
                    }
                }
            }
            
            $lowername = strtolower($tpt_vars['template_data']['form_values']['username']);
            $userdata = $tpt_vars['db']['handler']->getData($tpt_vars, ''.$users_table.'', '*', 'LOWER(`username`)="'.$lowername.'" AND `deleted`!=1', 'username', false );
            if(!empty($userdata))
                $userdata = reset($userdata);
            
            if(!empty($userdata) && (strtolower($tpt_vars['user']['data']['username']) != strtolower($tpt_vars['template_data']['form_values']['username']))) {
                $tpt_vars['template_data']['valid_form'] = false;
                $tpt_vars['template_data']['invalid_fields']['username'] = 1;
                $tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'This email exists in our database!', 'type'=>'error');
            }
            
            if(!$tpt_vars['template_data']['valid_form']) {
                $tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'Please review the form and validate the fields marked in red!', 'type'=>'error');
            } else {
                $old_username = $tpt_vars['user']['username'];
                
                
                $pfv = $tpt_vars['template_data']['processed_form_values'];
                $ff = '`'.implode('`,`', array_keys($pfv)).'`';
                $fv = implode(',', $pfv);
                
                $qf = array();
                foreach($pfv as $fn=>$fv) {
                    $qf[] = '`'.$fn.'`='.stripslashes($fv);
                }
                
                $query = 'UPDATE `'.$users_table.'` SET '.implode(',', $qf).' WHERE `id`='.$tpt_vars['user']['data']['id'].' AND `deleted`!=1';
                //die($query);
                $tpt_vars['db']['handler']->query($query, __FILE__);
                //var_dump($tpt_vars['db']['handler']->error());
                //die($query);
                
                //$query = 'SELECT * FROM `tpt_users`';
                //$tpt_vars['db']['handler']->query($query, __FILE__);
                //$tpt_vars['data']['tpt_users']['username'] = $tpt_vars['db']['handler']->fetch_assoc_list('username', false);
                
                
                $tpt_vars['environment']['ajax_result']['messages'][] = array('Account Info updated.', 'message');
                
                
                if($old_username != $tpt_vars['template_data']['form_values']['username']) {
                    $task = 'user.logout2';
                    include(__FILE__);
                }
                
                //$return_url = $vars['url']['handler']->wrap($vars, '/account-created');
                //tpt_request::redirect($vars, $return_url);
            }
        break;
        case 'user.edit_password' :
            $usable_controls = array('t', 'p', 'sl', 'stsel', 'rg', 'c');
            $ffields = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_form_edit_password_form_fields', '*', 'enabled=1', 'id', false);
            foreach($ffields as $rf) {
                //var_dump($_POST[$rf['name']]);
                if(in_array($rf['control'], $usable_controls)) {
                    if($rf['control'] == 'p') {
                        
                    } else {
                        $tpt_vars['template_data']['form_values'][$rf['name']] = $_POST[$rf['name']];
                    }
                    if($rf['control'] == 'rg') {
                        if($rf['required'] && !isset($_POST[$rf['name']])) {
                            $tpt_vars['template_data']['valid_form'] = false;
                            $tpt_vars['template_data']['invalid_fields'][$rf['name']] = 1;
                        }
                    } else {
                        if($rf['required'] && empty($_POST[$rf['name']])) {
                            $tpt_vars['template_data']['valid_form'] = false;
                            $tpt_vars['template_data']['invalid_fields'][$rf['name']] = 1;
                        }
                        if(!empty($rf['validation_regex']) && !preg_match('#'.$rf['validation_regex'].'#', $_POST[$rf['name']], $mtch)) {
                            $tpt_vars['template_data']['valid_form'] = false;
                            $tpt_vars['template_data']['invalid_fields'][$rf['name']] = 1;
                        }
                    }
                    if($rf['store_field']) {
                        $field_value = '';
                        if(strtolower($rf['control']) == 'p') {
                            $field_value = '"'.sha1($_POST[$rf['name']]).'"';
                        } else if((strtolower($rf['control']) == 't') || strtolower($rf['control']) == 'stsel') {
                            $field_value = '"'.mysql_real_escape_string($_POST[$rf['name']]).'"';
                        } else {
                            $field_value = intval($_POST[$rf['name']], 10);
                        }
                        $tpt_vars['template_data']['processed_form_values'][$rf['name']] = $field_value;
                    }
                }
            }
            
            if($tpt_vars['user']['data']['password'] !== sha1($_POST['old_password'])) {
                $tpt_vars['template_data']['valid_form'] = false;
                $tpt_vars['template_data']['invalid_fields']['old_password'] = 1;
                $tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'Your old Password does not match!', 'type'=>'error');
            }
            
            if(!empty($_POST['password']) && strlen($_POST['password']) < 6) {
                $tpt_vars['template_data']['valid_form'] = false;
                $tpt_vars['template_data']['invalid_fields'][$rf['name']] = 1;
                $tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'Your password is too short. Please provide at least 6 characters!', 'type'=>'error');
            } else if($_POST['password'] != $_POST['password2']) {
                $tpt_vars['template_data']['valid_form'] = false;
                $tpt_vars['template_data']['invalid_fields'][$rf['name']] = 1;
                $tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'Your password confirmation does not match the provided password.', 'type'=>'error');
            }
            
            if(!$tpt_vars['template_data']['valid_form']) {
                $tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'Please review the form and validate the fields marked in red!', 'type'=>'error');
            } else {
                
                $pfv = $tpt_vars['template_data']['processed_form_values'];
                $ff = '`'.implode('`,`', array_keys($pfv)).'`';
                $fv = implode(',', $pfv);
                
                $qf = array();
                foreach($pfv as $fn=>$fv) {
                    $qf[] = '`'.$fn.'`='.stripslashes($fv);
                }
                
                $query = 'UPDATE `'.$users_table.'` SET '.implode(',', $qf).' WHERE `id`='.$tpt_vars['user']['data']['id'].' AND `deleted`!=1';
                //die($query);
                $tpt_vars['db']['handler']->query($query, __FILE__);
                //var_dump($tpt_vars['db']['handler']->error());
                //die($query);
                
                //$query = 'SELECT * FROM `tpt_users`';
                //$tpt_vars['db']['handler']->query($query, __FILE__);
                //$tpt_vars['data']['tpt_users']['username'] = $tpt_vars['db']['handler']->fetch_assoc_list('username', false);
                
                $tpt_vars['environment']['ajax_result']['messages'][] = array('Your password has been successfully changed.', 'message');
                
                //$return_url = $vars['url']['handler']->wrap($vars, '/account-created');
                //tpt_request::redirect($vars, $return_url);
            }
        break;
        case 'user.edit_password2' :
            if(!empty($_POST['token'])) {
                $query = 'SELECT * FROM `'.$users_table.'` WHERE `resetpass_code`="'.mysql_real_escape_string($_POST['token']).'" AND `deleted`!=1';
                $tpt_vars['db']['handler']->query($query, __FILE__);
                $userdata = $tpt_vars['db']['handler']->fetch_assoc();
                
                if(!empty($userdata)) {
                    $usable_controls = array('t', 'p', 'sl', 'stsel', 'rg', 'c');
                    $ffields = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_form_edit_password2_form_fields', '*', 'enabled=1', 'id', false);
                    foreach($ffields as $rf) {
                        //var_dump($_POST[$rf['name']]);
                        if(in_array($rf['control'], $usable_controls)) {
                            if($rf['control'] == 'p') {
                                
                            } else {
                                $tpt_vars['template_data']['form_values'][$rf['name']] = $_POST[$rf['name']];
                            }
                            if($rf['control'] == 'rg') {
                                if($rf['required'] && !isset($_POST[$rf['name']])) {
                                    $tpt_vars['template_data']['valid_form'] = false;
                                    $tpt_vars['template_data']['invalid_fields'][$rf['name']] = 1;
                                }
                            } else {
                                if($rf['required'] && empty($_POST[$rf['name']])) {
                                    $tpt_vars['template_data']['valid_form'] = false;
                                    $tpt_vars['template_data']['invalid_fields'][$rf['name']] = 1;
                                }
                                if(!empty($rf['validation_regex']) && !preg_match('#'.$rf['validation_regex'].'#', $_POST[$rf['name']], $mtch)) {
                                    $tpt_vars['template_data']['valid_form'] = false;
                                    $tpt_vars['template_data']['invalid_fields'][$rf['name']] = 1;
                                }
                            }
                            if($rf['store_field']) {
                                $field_value = '';
                                if(strtolower($rf['control']) == 'p') {
                                    $field_value = '"'.sha1($_POST[$rf['name']]).'"';
                                } else if((strtolower($rf['control']) == 't') || strtolower($rf['control']) == 'stsel') {
                                    $field_value = '"'.mysql_real_escape_string($_POST[$rf['name']]).'"';
                                } else {
                                    $field_value = intval($_POST[$rf['name']], 10);
                                }
                                $tpt_vars['template_data']['processed_form_values'][$rf['name']] = $field_value;
                            }
                        }
                    }
                    
                    if(!empty($_POST['password']) && strlen($_POST['password']) < 6) {
                        $tpt_vars['template_data']['valid_form'] = false;
                        $tpt_vars['template_data']['invalid_fields'][$rf['name']] = 1;
                        $tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'Your password is too short. Please provide at least 6 characters!', 'type'=>'error');
                    } else if($_POST['password'] != $_POST['password2']) {
                        $tpt_vars['template_data']['valid_form'] = false;
                        $tpt_vars['template_data']['invalid_fields'][$rf['name']] = 1;
                        $tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'Your password confirmation does not match the provided password.', 'type'=>'error');
                    }
                    
                    if(!$tpt_vars['template_data']['valid_form']) {
                        $tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'Please review the form and validate the fields marked in red!', 'type'=>'error');
                    } else {
                        
                        $pfv = $tpt_vars['template_data']['processed_form_values'];
                        $ff = '`'.implode('`,`', array_keys($pfv)).'`';
                        $fv = implode(',', $pfv);
                        
                        $qf = array();
                        foreach($pfv as $fn=>$fv) {
                            $qf[] = '`'.$fn.'`='.stripslashes($fv);
                        }
                        
                        $query = 'UPDATE `'.$users_table.'` SET '.implode(',', $qf).' WHERE `id`='.$userdata['id'].' AND `deleted`!=1';
                        //die($query);
                        $tpt_vars['db']['handler']->query($query, __FILE__);
                        $query = 'UPDATE `'.$users_table.'` SET `resetpass_code`="" WHERE `id`='.$userdata['id'].' AND `deleted`!=1';
                        //die($query);
                        $tpt_vars['db']['handler']->query($query, __FILE__);
                        //var_dump($tpt_vars['db']['handler']->error());
                        //die($query);
                        
                        //$query = 'SELECT * FROM `tpt_users`';
                        //$tpt_vars['db']['handler']->query($query, __FILE__);
                        //$tpt_vars['data']['tpt_users']['username'] = $tpt_vars['db']['handler']->fetch_assoc_list('username', false);
                        
                        $tpt_vars['environment']['ajax_result']['messages'][] = array('Your password has been successfully changed.', 'message');
                        
                        //$return_url = $vars['url']['handler']->wrap($vars, '/account-created');
                        //tpt_request::redirect($vars, $return_url);
                    }
                } else {
                    $tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'Invalid request!', 'type'=>'error');
                }
            
            } else {
                $tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'Invalid request!', 'type'=>'error');
            }
        break;
        case 'user.reset_password' :
            $tpt_vars['template_data']['valid_form'] = true;
            
            //$token = sha1(time().encode_string($tpt_vars['user']['username'], $tpt_vars['config']['key']));
            if(!$tpt_vars['template_data']['valid_form']) {
                $tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'Please review the form and validate the fields marked in red!', 'type'=>'error');
            } else {
                $tpt_baseurl = BASE_URL;
                $token = base64_encode(encode_string(time().strtolower($_POST['username']), $tpt_vars['config']['key']));
                include(TPT_EMAIL_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'reset-password-text.tpt.php');
                include(TPT_EMAIL_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'reset-password-html.tpt.php');
                $subject = 'AmazingWristbands.com account Password Reset request';
                $from = 'AmazingWristbands.com <Admin@AmazingWristbands.com>';
                tpt_mail::sendmail($tpt_vars, $from, strtolower($_POST['username']), $subject, $text_email_template, $html_email_template);
                
                $query = 'UPDATE `'.$users_table.'` SET `resetpass_code`="'.$token.'" WHERE `username`="'.mysql_real_escape_string(strtolower($_POST['username'])).'" AND `deleted`!=1';
                //die($query);
                $tpt_vars['db']['handler']->query($query, __FILE__);
                //var_dump($tpt_vars['db']['handler']->error());
                //die($query);
                
                $tpt_vars['environment']['ajax_result']['messages'][] = array('Check your inbox for password reset instructions.', 'message');
                $tpt_vars['environment']['ajax_result']['messages'][] = array('What to do if you don\'t get an email:', 'tip');
                $tpt_vars['environment']['ajax_result']['messages'][] = array('1. Check your email account spam folder', 'tip');
                $tpt_vars['environment']['ajax_result']['messages'][] = array('2. Try submitting the form again', 'tip');
                
                //$return_url = $vars['url']['handler']->wrap($vars, '/account-created');
                //tpt_request::redirect($vars, $return_url);
            }
        break;
        case 'user.edit_payment_address' :
            $usable_controls = array('t', 'p', 'sl', 'stsel', 'rg', 'c');
            $ffields = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_form_edit_billing_address_form_fields', '*', 'enabled=1', 'id', false);
            foreach($ffields as $rf) {
                //var_dump($_POST[$rf['name']]);
                if(in_array($rf['control'], $usable_controls)) {
                    if($rf['control'] == 'p') {
                        
                    } else {
                        $tpt_vars['template_data']['form_values'][$rf['name']] = $_POST[$rf['name']];
                    }
                    if($rf['control'] == 'rg') {
                        if($rf['required'] && !isset($_POST[$rf['name']])) {
                            $tpt_vars['template_data']['valid_form'] = false;
                            $tpt_vars['template_data']['invalid_fields'][$rf['name']] = 1;
                        }
                    } else {
                        if($rf['required'] && empty($_POST[$rf['name']])) {
                            $tpt_vars['template_data']['valid_form'] = false;
                            $tpt_vars['template_data']['invalid_fields'][$rf['name']] = 1;
                        }
                        if(!empty($rf['validation_regex']) && !preg_match('#'.$rf['validation_regex'].'#', $_POST[$rf['name']], $mtch)) {
                            $tpt_vars['template_data']['valid_form'] = false;
                            $tpt_vars['template_data']['invalid_fields'][$rf['name']] = 1;
                        }
                    }
                    if($rf['store_field']) {
                        $field_value = '';
                        if(strtolower($rf['control']) == 'p') {
                            $field_value = '"'.sha1($_POST[$rf['name']]).'"';
                        } else if((strtolower($rf['control']) == 't') || strtolower($rf['control']) == 'stsel') {
                            $field_value = '"'.mysql_real_escape_string($_POST[$rf['name']]).'"';
                        } else {
                            $field_value = intval($_POST[$rf['name']], 10);
                        }
                        $tpt_vars['template_data']['processed_form_values'][$rf['name']] = $field_value;
                    }
                }
            }
            
            if(!$tpt_vars['template_data']['valid_form']) {
                $tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'Please review the form and validate the fields marked in red!', 'type'=>'error');
            } else {
                $pfv = $tpt_vars['template_data']['processed_form_values'];
                $ff = '`'.implode('`,`', array_keys($pfv)).'`';
                $fv = implode(',', $pfv);
                
                $qf = array();
                foreach($pfv as $fn=>$fv) {
                    $qf[] = '`'.$fn.'`='.stripslashes($fv);
                }
                
                $query = 'UPDATE `'.$users_table.'` SET '.implode(',', $qf).' WHERE `id`='.$tpt_vars['user']['data']['id'].' AND `deleted`!=1';
                //die($query);
                $tpt_vars['db']['handler']->query($query, __FILE__);
                //var_dump($tpt_vars['db']['handler']->error());
                //die($query);
                
                $tpt_vars['environment']['ajax_result']['messages'][] = array('Billing Address updated.', 'message');
                
                //$return_url = $vars['url']['handler']->wrap($vars, '/account-created');
                //tpt_request::redirect($vars, $return_url);
            }
        break;
        case 'user.edit_shipping_address' :
            $usable_controls = array('t', 'p', 'sl', 'stsel', 'rg', 'c');
            $ffields = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_form_edit_shipping_address_form_fields', '*', 'enabled=1', 'id', false);
            foreach($ffields as $rf) {
                //var_dump($_POST[$rf['name']]);
                if(in_array($rf['control'], $usable_controls)) {
                    if($rf['control'] == 'p') {
                        
                    } else {
                        $tpt_vars['template_data']['form_values'][$rf['name']] = $_POST[$rf['name']];
                    }
                    if($rf['control'] == 'rg') {
                        if($rf['required'] && !isset($_POST[$rf['name']])) {
                            $tpt_vars['template_data']['valid_form'] = false;
                            $tpt_vars['template_data']['invalid_fields'][$rf['name']] = 1;
                        }
                    } else {
                        if($rf['required'] && empty($_POST[$rf['name']])) {
                            $tpt_vars['template_data']['valid_form'] = false;
                            $tpt_vars['template_data']['invalid_fields'][$rf['name']] = 1;
                        }
                        if(!empty($rf['validation_regex']) && !preg_match('#'.$rf['validation_regex'].'#', $_POST[$rf['name']], $mtch)) {
                            $tpt_vars['template_data']['valid_form'] = false;
                            $tpt_vars['template_data']['invalid_fields'][$rf['name']] = 1;
                        }
                    }
                    if($rf['store_field']) {
                        $field_value = '';
                        if(strtolower($rf['control']) == 'p') {
                            $field_value = '"'.sha1($_POST[$rf['name']]).'"';
                        } else if((strtolower($rf['control']) == 't') || strtolower($rf['control']) == 'stsel') {
                            $field_value = '"'.mysql_real_escape_string($_POST[$rf['name']]).'"';
                        } else {
                            $field_value = intval($_POST[$rf['name']], 10);
                        }
                        $tpt_vars['template_data']['processed_form_values'][$rf['name']] = $field_value;
                    }
                }
            }
            
            if(!$tpt_vars['template_data']['valid_form']) {
                $tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'Please review the form and validate the fields marked in red!', 'type'=>'error');
            } else {
                $pfv = $tpt_vars['template_data']['processed_form_values'];
                $ff = '`'.implode('`,`', array_keys($pfv)).'`';
                $fv = implode(',', $pfv);
                
                $qf = array();
                foreach($pfv as $fn=>$fv) {
                    $qf[] = '`'.$fn.'`='.stripslashes($fv);
                }
                
                $query = 'UPDATE `'.$users_table.'` SET '.implode(',', $qf).' WHERE `id`='.$tpt_vars['user']['data']['id'].' AND `deleted`!=1';
                //die($query);
                $tpt_vars['db']['handler']->query($query, __FILE__);
                //var_dump($tpt_vars['db']['handler']->error());
                //die($query);
                
                $tpt_vars['environment']['ajax_result']['messages'][] = array('Shipping Address updated.', 'message');
                
                //$return_url = $vars['url']['handler']->wrap($vars, '/account-created');
                //tpt_request::redirect($vars, $return_url);
            }
        break;
        case 'user.edit_address2' :
            $an = mysql_real_escape_string(base64_decode($_POST['address_name_old']));
            
            $usable_controls = array('t', 'p', 'sl', 'stsel', 'rg', 'c');
            $ffields = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_form_edit_address_form_fields', '*', 'enabled=1', 'id', false);
            foreach($ffields as $rf) {
                //var_dump($_POST[$rf['name']]);
                if(in_array($rf['control'], $usable_controls)) {
                    if($rf['control'] == 'p') {
                        
                    } else {
                        $tpt_vars['template_data']['form_values'][$rf['name']] = $_POST[$rf['name']];
                    }
                    if($rf['control'] == 'rg') {
                        if($rf['required'] && !isset($_POST[$rf['name']])) {
                            $tpt_vars['template_data']['valid_form'] = false;
                            $tpt_vars['template_data']['invalid_fields'][$rf['name']] = 1;
                        }
                    } else {
                        if($rf['required'] && empty($_POST[$rf['name']])) {
                            $tpt_vars['template_data']['valid_form'] = false;
                            $tpt_vars['template_data']['invalid_fields'][$rf['name']] = 1;
                        }
                        if(!empty($rf['validation_regex']) && !preg_match('#'.$rf['validation_regex'].'#', $_POST[$rf['name']], $mtch)) {
                            $tpt_vars['template_data']['valid_form'] = false;
                            $tpt_vars['template_data']['invalid_fields'][$rf['name']] = 1;
                        }
                    }
                    if($rf['store_field']) {
                        $field_value = '';
                        if(strtolower($rf['control']) == 'p') {
                            $field_value = '"'.sha1($_POST[$rf['name']]).'"';
                        } else if((strtolower($rf['control']) == 't') || strtolower($rf['control']) == 'stsel') {
                            $field_value = '"'.mysql_real_escape_string($_POST[$rf['name']]).'"';
                        } else {
                            $field_value = intval($_POST[$rf['name']], 10);
                        }
                        $tpt_vars['template_data']['processed_form_values'][$rf['name']] = $field_value;
                    }
                }
            }
            
            $address_entr = false;
            foreach($tpt_vars['user']['addresses'] as $address) {
                if($an == $address['address_name']) {
                    $address_entr = $address;
                    break;
                }
            }
            
            if(!$address_entr) {
                $tpt_vars['template_data']['valid_form'] = false;
                $tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'Address does not exist.', 'type'=>'error');
                $return_url = $vars['url']['handler']->wrap($vars, '/my-addresses');
                tpt_request::redirect($vars, $return_url);
            }

            if(!$tpt_vars['template_data']['valid_form']) {
                $tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'Please review the form and validate the fields marked in red!', 'type'=>'error');
            } else {
                $pfv = $tpt_vars['template_data']['processed_form_values'];
                $pfv['userid'] = $tpt_vars['user']['data']['id'];
                $ff = '`'.implode('`,`', array_keys($pfv)).'`';
                $fv = implode(',', $pfv);
                
                $qf = array();
                foreach($pfv as $fn=>$fv) {
                    $qf[] = '`'.$fn.'`='.$fv;
                }
                
                $query = 'UPDATE `tpt_users_addresses` SET '.$qf.' WHERE `address_name`="'.$an.'" AND `userid`='.$tpt_vars['user']['data']['id'];
                //die($query);
                $tpt_vars['db']['handler']->query($query, __FILE__);
                //var_dump($tpt_vars['db']['handler']->error());
                //die($query);
                
                $tpt_vars['environment']['ajax_result']['messages'][] = array('Address "'.$address_entr['address_name'].'" has been updated.', 'message');
                
                //$return_url = $vars['url']['handler']->wrap($vars, '/account-created');
                //tpt_request::redirect($vars, $return_url);
            }
        break;
        case 'user.delete_address' :
            $tpt_vars['template_data']['valid_form'] = false;
            $address_name = mysql_real_escape_string(base64_decode($_POST['address_name']));
            foreach($tpt_vars['user']['addresses'] as $address) {
                if($address_name == $address['address_name']) {
                    $tpt_vars['template_data']['valid_form'] = true;
                    break;
                }
            }

            if(!$tpt_vars['template_data']['valid_form']) {
                $tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'Address does not exist.', 'type'=>'error');
            } else {
                $query = 'DELETE FROM `tpt_users_addresses` WHERE `address_name`="'.$address_name.'" AND userid='.$tpt_vars['user']['data']['id'];
                //die($query);
                $tpt_vars['db']['handler']->query($query, __FILE__);
                //var_dump($tpt_vars['db']['handler']->error());
                //die($query);
                
                $tpt_vars['environment']['ajax_result']['messages'][] = array('Address "'.$address_name.'" has been set as default.', 'message');
                
                //$return_url = $vars['url']['handler']->wrap($vars, '/account-created');
                //tpt_request::redirect($vars, $return_url);
            }
        break;
        case 'user.default_address' :
            $tpt_vars['template_data']['valid_form'] = false;
            $address_entr = false;
            $address_name = mysql_real_escape_string(base64_decode($_POST['address_name']));
            foreach($tpt_vars['user']['addresses'] as $address) {
                if($address_name == $address['address_name']) {
                    $address_entr = $address;
                    $tpt_vars['template_data']['valid_form'] = true;
                    break;
                }
            }

            if(!$tpt_vars['template_data']['valid_form']) {
                $tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'Address does not exist.', 'type'=>'error');
            } else {
                $query = 'UPDATE `'.$users_table.'` SET `default_address`='.stripslashes($address_entr['id']).' WHERE `id`='.$tpt_vars['user']['data']['id'].' AND `deleted`!=1';
                //die($query);
                $tpt_vars['db']['handler']->query($query, __FILE__);
                //var_dump($tpt_vars['db']['handler']->error());
                //die($query);
                
                //$query = 'SELECT * FROM `tpt_users`';
                //$tpt_vars['db']['handler']->query($query, __FILE__);
                //$tpt_vars['data']['tpt_users']['username'] = $tpt_vars['db']['handler']->fetch_assoc_list('username', false);
                $tpt_vars['user']['data']['default_address'] = $address_entr['id'];
                
                $tpt_vars['environment']['ajax_result']['messages'][] = array('Address "'.$address_entr['address_name'].'" has been set as default.', 'message');
                
                //$return_url = $vars['url']['handler']->wrap($vars, '/account-created');
                //tpt_request::redirect($vars, $return_url);
            }
        break;
        case 'user.select_shipping_address' :
            $tpt_vars['template_data']['valid_form'] = false;
            $address_entr = false;
            $address_name = mysql_real_escape_string(base64_decode($_POST['address_name']));
            foreach($tpt_vars['user']['addresses'] as $address) {
                if($address_name == $address['address_name']) {
                    $address_entr = $address;
                    $tpt_vars['template_data']['valid_form'] = true;
                    break;
                }
            }

            if(!$tpt_vars['template_data']['valid_form']) {
                $tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'Address does not exist.', 'type'=>'error');
            } else {
                $query = 'UPDATE `tpt_session` SET `shipping_address`='.$address_entr['id'].' WHERE `id`="'.$tpt_vars['session']['user_session']['sessionid'].'"';
                //var_dump($query);die();
                $tpt_vars['db']['handler']->query($query, __FILE__);
                //var_dump($tpt_vars['db']['handler']->error());
                //die($query);
                
                $tpt_vars['data']['tpt_session'] = array();
                $query = 'SELECT * FROM `tpt_session`';
                $tpt_vars['db']['handler']->query($query, __FILE__);
                $tpt_vars['data']['tpt_session']['id'] = $tpt_vars['db']['handler']->fetch_assoc_list('id', false);
                $tpt_vars['user']['shipping_address'] = $address_entr['id'];
                
                $tpt_vars['environment']['ajax_result']['messages'][] = array('Address "'.$address_entr['address_name'].'" has been selected for shipping.', 'message');
                
                //$return_url = $vars['url']['handler']->wrap($vars, '/account-created');
                //tpt_request::redirect($vars, $return_url);
            }
        break;
        case 'user.select_payment_address' :
            $tpt_vars['template_data']['valid_form'] = false;
            $address_entr = false;
            $address_name = mysql_real_escape_string(base64_decode($_POST['address_name']));
            foreach($tpt_vars['user']['addresses'] as $address) {
                if($address_name == $address['address_name']) {
                    $address_entr = $address;
                    $tpt_vars['template_data']['valid_form'] = true;
                    break;
                }
            }

            if(!$tpt_vars['template_data']['valid_form']) {
                $tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'Address does not exist.', 'type'=>'error');
            } else {
                $query = 'UPDATE `tpt_session` SET `payment_address`='.$address_entr['id'].' WHERE `id`="'.$tpt_vars['session']['user_session']['sessionid'].'"';
                //var_dump($query);die();
                $tpt_vars['db']['handler']->query($query, __FILE__);
                //var_dump($tpt_vars['db']['handler']->error());
                //die($query);
                
                $tpt_vars['data']['tpt_session'] = array();
                $query = 'SELECT * FROM `tpt_session`';
                $tpt_vars['db']['handler']->query($query, __FILE__);
                $tpt_vars['data']['tpt_session']['id'] = $tpt_vars['db']['handler']->fetch_assoc_list('id', false);
                $tpt_vars['user']['payment_address'] = $address_entr['id'];
                
                $tpt_vars['environment']['ajax_result']['messages'][] = array('Address "'.$address_entr['address_name'].'" has been selected for payment.', 'message');
                
                //$return_url = $vars['url']['handler']->wrap($vars, '/account-created');
                //tpt_request::redirect($vars, $return_url);
            }
        break;
        case 'registration.check_email' :
            $lowername = strtolower($_POST['username']);
            $userdata = $tpt_vars['db']['handler']->getData($tpt_vars, $users_table, '*', 'LOWER(`username`)="'.$lowername.'" AND `deleted`!=1', 'username', false );
            if(!empty($userdata))
                $userdata = reset($userdata);
            
            $result = '';
            if(!preg_match('#(^([-A-Za-z0-9_]+[\.]*)*[-A-Za-z0-9_]+@[-A-Za-z0-9]+[\-]*[-A-Za-z0-9]+(\.[A-Za-z0-9]{1,6})+$)#', $_POST['username'], $mtch)) {
                $result = '<span class="amz_red">The email you entered is not valid.</span>';
            } else if(!empty($userdata)) {
                $result = '<span class="amz_red">The email you entered is already registered.</span>';
                //$tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'This email exists in our database! Please use the password recovery if this is your email address.', 'type'=>'error');
            } else {
                $result = '<span class="amz_green">Email OK</span>';
            }
            $tpt_vars['environment']['ajax_result']['update_elements'] = array('emval_msg'=>$result);
        break;
        case 'registration.check_email2' :
            $lowername = strtolower($_POST['username']);
            $userdata = $tpt_vars['db']['handler']->getData($tpt_vars, $users_table, '*', 'LOWER(`username`)="'.$lowername.'" AND `deleted`!=1', 'username', false );
            if(!empty($userdata))
                $userdata = reset($userdata);
            
            $result = '';
            if(!preg_match('#(^([-A-Za-z0-9_]+[\.]*)*[-A-Za-z0-9_]+@[-A-Za-z0-9]+[\-]*[-A-Za-z0-9]+(\.[A-Za-z0-9]{1,6})+$)#', $_POST['username'], $mtch)) {
                $result = '<span class="amz_red">The email you entered is not valid.</span>';
            } else if(strtolower($tpt_vars['user']['data']['username']) == strtolower($_POST['username'])) {
                $result = '<span class="amz_red">This is your current email address.</span>';
            } else if(!empty($userdata)) {
                $result = '<span class="amz_red">The email you entered is already registered.</span>';
                //$tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'This email exists in our database! Please use the password recovery if this is your email address.', 'type'=>'error');
            } else {
                $result = '<span class="amz_green">Email OK</span>';
            }
            $tpt_vars['environment']['ajax_result']['update_elements'] = array('emval_msg'=>$result);
        break;
        case 'registration.get_states' :
            $state = '';
            $shipping = false;
            $country = $_POST['country'];
            include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'states.tpt.php');

            $result = $states;
            $tpt_vars['environment']['ajax_result']['update_elements'] = array('state_tptformcontrol'=>$result);
        break;
        case 'registration.get_states2' :
            $state = '';
            $shipping = true;
            $country = $_POST['shipping_country'];
            include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'states.tpt.php');

            $result = $states;
            $tpt_vars['environment']['ajax_result']['update_elements'] = array('shipping_state_tptformcontrol'=>$result);
        break;
        case 'address.check_name' :
            $valid = true;
            foreach($tpt_vars['user']['addresses'] as $address) {
                if($_POST['address_name'] === $address['address_name']) {
                    $valid = false;
                    break;
                }
            }
            $result = '';
            if(!$valid) {
                $result = '<span class="amz_red">This address name is already used.</span>';
                //$tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'This email exists in our database! Please use the password recovery if this is your email address.', 'type'=>'error');
            } else {
                $result = '<span class="amz_green"></span>';
            }
            $tpt_vars['environment']['ajax_result']['update_elements'] = array('anval_msg'=>$result);
        break;
    }
}


switch(strtolower($task)) {
    case 'user.same_address' :
        $same_address = intval($_GET['same_address'], 10);;
        $query = 'UPDATE `'.$users_table.'` SET `same_address`='.$same_address.' WHERE `id`='.$tpt_vars['user']['data']['id'].' AND `deleted`!=1';
        //die($query);
        $tpt_vars['db']['handler']->query($query, __FILE__);
        //var_dump($tpt_vars['db']['handler']->error());
        //die($query);
        
        //$query = 'SELECT * FROM `tpt_users`';
        //$tpt_vars['db']['handler']->query($query, __FILE__);
        //$tpt_vars['data']['tpt_users']['username'] = $tpt_vars['db']['handler']->fetch_assoc_list('username', false);
        $tpt_vars['user']['data']['same_address'] = $same_address;
        
        //$tpt_vars['environment']['ajax_result']['messages'][] = array('You have set same address for shipping and payment.', 'message');
        
        //$return_url = $vars['url']['handler']->wrap($vars, '/account-created');
        //tpt_request::redirect($vars, $return_url);
    break;
    case 'user.reset_password_cancel' :
        if(!empty($_GET['token'])) {
            $query = 'SELECT * FROM `'.$users_table.'` WHERE `resetpass_code`="'.mysql_real_escape_string($_GET['token']).'" AND `deleted`!=1';
            $tpt_vars['db']['handler']->query($query, __FILE__);
            $userdata = $tpt_vars['db']['handler']->fetch_assoc();
            
            if(!empty($userdata)) {
                
                $query = 'UPDATE `'.$users_table.'` SET `resetpass_code`="" WHERE `id`='.$userdata['id'].' AND `deleted`!=1';
                //die($query);
                $tpt_vars['db']['handler']->query($query, __FILE__);
                //var_dump($tpt_vars['db']['handler']->error());
                //die($query);
                
                //$query = 'SELECT * FROM `tpt_users`';
                //$tpt_vars['db']['handler']->query($query, __FILE__);
                //$tpt_vars['data']['tpt_users']['username'] = $tpt_vars['db']['handler']->fetch_assoc_list('username', false);
                
                
                //$return_url = $vars['url']['handler']->wrap($vars, '/account-created');
                //tpt_request::redirect($vars, $return_url);
            }
        }
        $tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'Password reset request cancelled!', 'type'=>'notice');
    break;
}
