<?php
namespace clonelin\email;
use PHPMailer;
class Email {
    protected $options;
    protected $data;
    private $error;
    public function __construct($options)
    {
        $this->options = $options;
    }
    public function send($data){
        //
        $this->data = $data;
        //
        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = 0;                                 // Enable verbose debug output
            $mail->Mailer = "SMTP";
            $mail->CharSet = "UTF-8";
            $mail->Host = $this->options['host'];  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = $this->options['username'];                 // SMTP username
            $mail->Password = $this->options['password'];                           // SMTP password
            $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = $this->options['port'];                                    // TCP port to connect to
            //Recipients
            $mail->setFrom($this->options['mail_from'], '');
            $mail->addAddress($this->data['mail_to']);     // Add a recipient
            $html = false;
            if(isset($this->options['is_html']) && $this->options['is_html']){
                $html = true;
            }
            //Content
            $mail->isHTML($html);                                  // Set email format to HTML
            $mail->Subject = $this->data['subject'];
            $mail->Body   = $this->data['body'];
            $mail->AltBody = $this->data['no_html_body'];
            //
            $result = $mail->send();
            return $result;
        } catch (Exception $e) {
            $error = 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
            $this->setError( $error);
        }
    }
    //
    public function setError($error){
        $this->error = $error;
    }
    public function getError(){
        return $this->error;
    }
}