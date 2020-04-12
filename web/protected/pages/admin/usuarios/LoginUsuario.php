<?php
class LoginUsuario extends TPage
{
    /**
     * Validates whether the username and password are correct.
     * This method responds to the TCustomValidator's OnServerValidate event.
     * @param mixed event sender
     * @param mixed event parameter
     */
    public function validateUser($sender,$param)
    {
    	$password = $this->Password->Text;
        $authManager=$this->Application->getModule('auth');
        if(!$authManager->login($this->Username->Text, $password) || empty($password))
            $param->IsValid=false;
    }
 
    /**
     * Redirects the user's browser to appropriate URL if login succeeds.
     * This method responds to the login button's OnClick event.
     * @param mixed event sender
     * @param mixed event parameter
     */
    public function loginButtonClicked($sender,$param)
    {
        if($this->Page->IsValid)
        {
            $auth = $this->Application->getModule('auth');
            $url = $auth->ReturnUrl;
            if(empty($url))
                $url = $this->Service->constructUrl('admin.Home');

            if($auth->User->Roles[0] != "1")
                $this->Response->redirect($this->Service->constructUrl('Processos'));
            else
                $this->Response->redirect($url);
        }
    }
}
?>