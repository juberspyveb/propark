<?php
#namespace App\Helpers; // Your helpers namespace
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Auth;
#use Illuminate\Support\Facades\Session;


if (!function_exists('_set_flashdata')) {
    /**
    $type = 1 for Toast, 2 for HTML
     */
    function _set_flashdata($msg_type = 'S', $obj, $type = 'FLASH_TOAST'){
        $notification_msg = '';
        if(is_object($obj)){
            if(!empty($obj)){
                foreach($obj as $k=>$v){
                    $notification_msg .= ($notification_msg) ? "<br>".$v : $v;
                }
            }
        }else{
            $notification_msg  = $obj;
        }

        Session::flash('FLASH_TYPE', $type);
        Session::flash('FLASH_STATUS', $msg_type);
        Session::flash('FLASH_MESSAGE', $notification_msg);
    }
}

if (!function_exists('_notify')) {
    function _notify(){
        // dd(session('FLASH_TYPE'));
        if(Session::has('FLASH_MESSAGE')){
            if(session('FLASH_TYPE') == 'FLASH_HTML'){
                if(Session::has('FLASH_STATUS') && Session::has('FLASH_MESSAGE')){
                    echo( _notification( session('FLASH_STATUS'), session('FLASH_MESSAGE') ) );
                }
            }else{
                if(session('FLASH_TYPE') == 'FLASH_TOAST'){
                    _notification_js();
                }
            }
        }

        if(!Session::has('FLASH_MESSAGE')){
            _set_flashdata('E', '');
        }
    }
}

if (!function_exists('_notification_js')) {
    function _notification_js(){
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                <?php
                    if(session('FLASH_STATUS') == 'S'){
                        if(session('FLASH_MESSAGE') != ''){
                ?>
                toastr.success('<?= session('FLASH_MESSAGE') ?>', 'Success');
                <?php
                        }
                ?>
                //toastr.error('<?php //echo session('FLASH_MESSAGE') ?>', 'Error');
                //toastr.warning('<?php //echo session('FLASH_MESSAGE') ?>', 'Warning');
                //toastr.info('<?php //echo session('FLASH_MESSAGE') ?>', 'Info');
                <?php
                    }else if(session('FLASH_STATUS') == 'E'){
                        if(session('FLASH_MESSAGE') != ''){
                ?>
                toastr.error('<?= session('FLASH_MESSAGE') ?>', 'Error');
                <?php
                        }
                    }else if(session('FLASH_STATUS') == 'W'){
                        if(session('FLASH_MESSAGE') != ''){
                ?>
                toastr.warning('<?= session('FLASH_MESSAGE') ?>', 'Warning');
                <?php
                        }
                    }
                    else if(session('FLASH_STATUS') == 'I')
                    {
                        if(session('FLASH_MESSAGE') != '')
                        {
                ?>
                toastr.info('<?= session('FLASH_MESSAGE') ?>', 'Info');
                <?php
                        }
                    }
                ?>
            });
        </script>
    <?php }
}

if (!function_exists('_notification')) {
    function _notification($type='S', $msg='Hello ! This is notification.'){
        $notification_html = '';
        if($type == 'S'){
            $type = 'alert-success';
        }
        if($type == 'E'){
            $type = 'alert-danger';
        }
        if($type == 'SE'){
            $type = 'alert-secondary';
        }
        if($type == 'P'){
            $type = 'alert-primary';
        }
        if($type == 'W'){
            $type = 'alert-warning';
        }
        if($type == 'I'){
            $type = 'alert-info';
        }
        if($type == 'L'){
            $type = 'alert-light';
        }
        if($type == 'D'){
            $type = 'alert-dark';
        }

        $notification_html .= '<div class="alert '.$type.' alert-dismissible" style="margin-top: 55px;">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        '.$msg.'
                                    </div>' ;
        return $notification_html;
    }
}