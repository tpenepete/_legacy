<?php

defined('TPT_INIT') or die('access denied');

class tpt_Messages {
    
    static function getMessage(&$vars, $content, $type='message') {
        $message = '';
        $messageClass = 'tpt_message';
        $icon = TPT_IMAGES_URL.'/icons/messageIcon.png';
        $themeColor = '#17ed84';
        switch(strtolower($type)) {
            case 'notice' :
                $messageClass = 'tpt_notice';
                $icon = TPT_IMAGES_URL.'/icons/noticeIcon.png';
				$alt = 'Notification Icon';
                $themeColor = '#AAAA33';
                break;
            case 'warning' :
                $messageClass = 'tpt_warning';
                $icon = TPT_IMAGES_URL.'/icons/warningIcon.png';
				$alt = 'Warning Icon';
                $themeColor = '#ffcc66';
                break;
            case 'error' :
                $messageClass = 'tpt_error';
                $icon = TPT_IMAGES_URL.'/icons/errorIcon.png';
				$alt = 'Error Icon';
                $themeColor = '#d94d4d';
                break;
            case 'tip' :
                $messageClass = 'tpt_tip';
                $icon = TPT_IMAGES_URL.'/icons/tipIcon.png';
				$alt = 'Tips Icon';
                $themeColor = '#cccccc';
                break;
            case 'message' :
            default :
                $messageClass = 'tpt_message';
                $icon = TPT_IMAGES_URL.'/icons/messageIcon.png';
				$alt = 'Message Icon';
                $themeColor = '#17ed84';
                break;
        }
        /*
$message .= <<< EOT
        <div class="$messageClass position-relative height-48 line-height-48">
            <div class="position-relative" style="z-index: 2;">
                <div class="messageIcon float-left text-align-center width-100 background-position-CC background-repeat-no-repeat" style="">
                    <img src="$icon" />
                </div>
                <div class="overflow-hidden line-height-48 font-size-18 font-weight-bold color-white text-align-center">
                    $content
                </div>
            </div>
            <div class="position-absolute top-0 right-0 bottom-0 left-0 opacity-80" style="z-index: 1; background-color: $themeColor"></div>
        </div>
EOT;
        */
        
    $timerBackground = TPT_IMAGES_URL.'/icons/countdown-10.gif?'.time();
            $message .= <<< EOT
        <div class="tpt_msg padding-bottom-8 position-relative" >
            <div class="close-message" style="z-index: 127090;" onclick="javascript:addClass(this.parentNode.parentNode, 'display-none');"></div>
            <div class="$messageClass tpt_msgwrap position-relative line-height-48" style="">
                <div class="position-relative tpt_messagebox" style="z-index: 2; background-color: $themeColor">
                    <div class="messageIcon float-left text-align-center width-100 background-position-CC background-repeat-no-repeat" style="/*background-image: url($icon);*/">
                        <img src="$icon" alt="$alt" />
                    </div>
                    <div class="overflow-hidden line-height-48 font-size-18 font-weight-bold color-white text-align-center">
                        <div style="display: inline;">$content</div>
                        <div class="close-message-timer" style="display: inline; z-index: 127089; background-image: url($timerBackground);"></div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">setTimeout(function() {document.getElementById('tpt_messages').style.display = "none";}, 10000);</script>
EOT;

        return $message;
    }
    
    static function getMessages(&$vars) {
        //var_dump($vars['environment']['ajax_result']['messages']);
        foreach($vars['environment']['ajax_result']['messages'] as $message) {
            if(is_string($message)) {
                $vars['template_data']['messages'][] = self::getMessage($vars, $message);
            } else if(is_array($message)) {
                if(count($message) === 1) {
                    $msg = reset($message);
                    if(!empty($msg))
                    $vars['template_data']['messages'][] = self::getMessage($vars, $msg);
                } else {
                    if(isset($message['type'])) {
                        preg_match('#(message|notice|warning|tip|error)#i', $message['type'], $mtype);
                        $mtype = $mtype[1]?$mtype[1]:'';
                        $vars['template_data']['messages'][] = self::getMessage($vars, $message['text'], $mtype);
                    } else {
                        preg_match('#(message|notice|warning|tip|error)#i', $message[1], $mtype);
                        $mtype = $mtype[1]?$mtype[1]:'';
                        $msg = reset($message);
                        if(!empty($msg))
                        $vars['template_data']['messages'][] = self::getMessage($vars, $msg, $mtype);
                    }
                }
            }
        }
    }
}