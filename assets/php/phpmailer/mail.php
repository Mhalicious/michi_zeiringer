<?php namespace phpmailer;
class mail extends phpmailer {
    // Set default variables for all new objects
    public $From     = '';
    public $FromName = '';
    public $Host     = 'smtp.fhstp.ac.at';
    public $Mailer   = 'smtp';
    public $SMTPAuth = true;                         
    public $Username = 'mt131024';                         
    public $Password = 'MatT#1988a';                         
    public $SMTPSecure = 'starttls';                         
    public $WordWrap = 75;

    public function subject($subject) {
        $this->Subject = $subject;
    }

    public function body($body) {
        $this->Body = $body;
    }
                         
    public function send() {
        $this->AltBody = strip_tags(stripslashes($this->Body))."\n\n";
        $this->AltBody = str_replace("&nbsp;","\n\n",$this->AltBody);
        return parent::send();
    }


}