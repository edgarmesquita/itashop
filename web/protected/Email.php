<?php
class Email
{
    private $mailto;
    private $sendername;
    private $sendermail;
    private $subject;
    private $mailcontent;
    private $attachment;
    private $servername;
    private $nohtml;

    function __construct($mailto="", $sendername="Sender", $sendermail="no@reply.error", $subject="", $mailcontent="no.file", $attachment="no.file", $servername="PHPMAILSERVER", $nohtml="[ This message should be viewed in HTML. This is a degraded version! ]")
    {
        $this->mailto=$mailto;
        $this->sendername=$sendername;
        $this->sendermail=$sendermail;
        $this->subject=$subject;
        $this->mailcontent=$mailcontent;
        $this->attachment=$attachment;
        $this->servername=$servername;
        $this->nohtml=$nohtml;
    }

    public function getMailTo()
    {
        return $this->mailto;
    }

    public function setMailTo($mailto)
    {
        $this->mailto=$mailto;
    }

    public function getSenderName()
    {
        return $this->sendername;
    }

    public function setSenderName($sendername)
    {
        $this->sendername=$sendername;
    }

    public function getSenderMail()
    {
        return $this->sendermail;
    }

    public function setSenderMail($sendermail)
    {
        $this->sendermail=$sendermail;
    }

    public function send()
    {
        if(strtoupper(substr(PHP_OS,0,3)=='WIN'))
        {
            $eol="\r\n";
            $sol="\n";
        }
        elseif(strtoupper(substr(PHP_OS,0,3)=='MAC')) $eol="\r";
        else $eol="\n";

        if(!isset($sol)) $sol = $eol;

        $Momentn = mktime().".".md5(rand(1000,9999));

        $f_name = $this->attachment;
        if($f_name == "no.file") $handle = false;
        else $handle = fopen($f_name, 'rb');

        $f_contents = @fread($handle, filesize($f_name));
        $f_contents = @base64_encode($f_contents);

        if($handle)
        {
            $sendfile = true;
            if(ini_get('mime_magic.debug')) $Bestype = @mime_content_type($this->attachment);
            else $Bestype = 'application/octet-stream';

            if(!$Bestype) $Bestype = 'application/octet-stream';

            $file_realname = explode("/", $this->attachment);
            $file_realname = $file_realname[count($file_realname)-1];
            $file_realname = explode("\\", $file_realname);
            $file_realname = $file_realname[count($file_realname)-1];
        }
        @fclose($handle);
        $Mailcontentstri  = explode($sol, $this->mailcontent);
        $Mailcontentstrip = strip_tags($Mailcontentstri[0]);

        if(@file_exists($Mailcontentstrip))
        {
            ob_start();
            if(require($this->mailcontent)) $body=ob_get_contents();
            ob_end_clean();
        }
        else
        {
            if(count($Mailcontentstri) < 1)
            {
                $body  = "Error loading file!";
                $error = true;
            }
            else $body  = $this->mailcontent;
        }

        $Textmsg = eregi_replace("<br(.{0,2})>", $eol, $body);
        $Textmsg = eregi_replace("</p>", $eol, $Textmsg);
        $Textmsg = strip_tags($Textmsg);
        $Textmsg = $this->nohtml.$eol.$eol.$Textmsg;

        $headers       = '';//'To: '.$this->mailto.' <'.$this->mailto.'>'.$eol;
        $headers      .= 'From: '.$this->sendername.' <'.trim($this->sendermail).'>'.$eol;
        $headers      .= "Message-ID: <".$Momentn."@".$this->servername.">".$eol;
        $headers      .= 'Date: '.date("r").$eol;
        $headers      .= 'Sender-IP: '.$_SERVER["REMOTE_ADDR"].$eol;
        $headers      .= 'X-Mailser: iPublications Adv.PHP Mailer 1.6'.$eol;
        $headers      .= 'MIME-Version: 1.0'.$eol;

        $bndp          = md5(time()).rand(1000,9999);
        $headers      .= "Content-Type: multipart/mixed; $eol       boundary=\"".$bndp."\"".$eol.$eol;
        $msg           = "This is a multi-part message in MIME format.".$eol.$eol;
        $msg          .= "--".$bndp.$eol;
        $bnd           = md5(time()).rand(1000,9999);
        $msg          .= "Content-Type: multipart/alternative; $eol       boundary=\"".$bnd."\"".$eol.$eol;
        $msg          .= "--".$bnd.$eol;
        $msg          .= "Content-Type: text/plain; charset=utf-8".$eol;
        $msg          .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
        $msg          .= $Textmsg.$eol;
        $msg          .= "--".$bnd.$eol;
        $msg          .= "Content-Type: text/html; charset=utf-8".$eol;
        $msg          .= "Content-Transfer-Encoding: 8-bit".$eol.$eol;
        $msg          .= $body.$eol;
        $msg          .= "--".$bnd."--".$eol.$eol;

        if(isset($sendfile))
        {
            $msg          .= "--".$bndp.$eol;
            $msg          .= "Content-Type: $Bestype; name=\"".$file_realname."\"".$eol;
            $msg          .= "Content-Transfer-Encoding: base64".$eol;
            $msg          .= "Content-Disposition: attachment;".$eol;
            $msg          .= "       filename=\"".$file_realname."\"".$eol.$eol;
            $f_contents    = chunk_split($f_contents);
            $msg          .= $f_contents.$eol;
        }
        $msg          .= "--".$bndp."--";
        if(!isset($error))
        {
            if(@mail(trim($this->mailto), $this->subject, $msg, $headers)) return true;
            else return false;
        }
        else return false;
    }
}
?>