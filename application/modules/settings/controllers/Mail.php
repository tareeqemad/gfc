<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 21/05/15
 * Time: 09:35 ص
 */

class Mail extends MY_Controller{

    var $MODEL_NAME= 'mail_model';

    function  __construct(){
        parent::__construct();
        //$this->load->model($this->MODEL_NAME);

        $this->to = $this->input->post('to');
        $this->subject = $this->input->post('subject');
        $this->send_text = $this->input->post('send_text');
        $this->html = $this->input->post('html');
        $this->cnt = $this->input->post('cnt');
        $this->chk_hash = $this->input->post('chk_hash');
    }

    function public_send($to= null, $subject='', $send_text='', $html=1, $cnt=1, $chk_hash=1){

        $to= ($this->to)? $this->to:$to;
        $subject= ($this->subject)? $this->subject:$subject;
        $send_text= ($this->send_text)? $this->send_text:$send_text;
        $html= ($this->html)? $this->html:$html;
        $cnt= ($this->cnt)? $this->cnt:$cnt;
		$chk_hash= ($this->chk_hash)? $this->chk_hash:$chk_hash;

        $hash= (date('Ymdi'));

        if($to==null or $chk_hash==null)
            die('-1');

        if( $chk_hash!=($hash*3) and $chk_hash!=(($hash+1)*3) and $chk_hash!=(($hash-1)*3) ){
            die('-2');
        }

        if(strtolower($_SERVER['HTTP_HOST'])== 'gs2.gedco.ps'){  //OLD: web87
            $html= 0;
        }
		$html= 0; // 202103 - cancle html 

        $this->load->library('email');
        if($html){
            $config['mailtype'] = 'html';
            $this->email->set_newline('\r\n');
            $this->email->set_crlf('\r\n');
            $this->email->initialize($config);

            $text= "
            <!DOCTYPE html>
            <html>
            <head>
                <meta http-equiv='content-type' content='text/html' charset='UTF-8' />
                <title>[Webinar] GS</title>
            </head>
            <body>
            <table style=\"width: 100%; direction: rtl; color: royalblue; font-size: 16px; font-weight: bold; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;\">
                <tr>
                    <td>
                     ".urldecode($send_text)."
                    </td>
                </tr>
            </table>
            <br/><br/>
                <div>SID".($this->user->emp_no)."</div>
            </body>
            </html>
        ";
        }else{
            $text= urldecode(strip_tags( str_replace("<br>"," ",$send_text) ).'
            SID'.$this->user->emp_no);
        }
        $this->email->from('admin@gedco.ps', 'النظام الموحد');
        $this->email->to($to); // can be an array as: array('m@m','A@a') or m@m,A@a
        // $this->email->subject(urldecode($subject));
        $this->email->message($text);
        if($cnt>1){
            for($i=1;$i<$cnt;$i++){
                $this->email->subject(urldecode($subject));
                $this->email->send();
            }
        }
        $this->email->subject(urldecode($subject));
        echo $this->email->send();
        //return @$this->email->send();
    }

    /*
    function public_send2($to= null, $subject='', $send_text='', $html=0){
        $to= ($this->to)? $this->to:$to;
        $subject= ($this->subject)? $this->subject:$subject;
        $send_text= ($this->send_text)? $this->send_text:$send_text;
        $html= ($this->html)? $this->html:$html;

        if($to==null)
            die(0);

        $this->load->helper('email');
        if($html){

            $text= "
            <!DOCTYPE html>
            <html>
            <head>
                <meta http-equiv='content-type' content='text/html' charset='UTF-8' />
                <title>[Webinar] GS</title>
            </head>
            <body>
            <table style=\"width: 100%; direction: rtl; color: royalblue; font-size: 20px; font-weight: bold; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;\">
                <tr>
                    <td>
                     ".urldecode($send_text)."
                    </td>
                </tr>
            </table>
            </body>
            </html>
        ";
        }else{
            $text= urldecode($send_text);
        }
        echo send_email($to,urldecode($subject),$text);
    }
*/

}
