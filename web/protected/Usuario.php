<?php
// Include TDbUserManager.php file which defines TDbUser
Prado::using('System.Security.TDbUserManager');
 
/**
 * BlogUser Class.
 * BlogUser represents the user data that needs to be kept in session.
 * Default implementation keeps username and role information.
 */
class Usuario extends TDbUser
{
    /**
     * Creates a BlogUser object based on the specified username.
     * This method is required by TDbUser. It checks the database
     * to see if the specified username is there. If so, a BlogUser
     * object is created and initialized.
     * @param string the specified username
     * @return BlogUser the user object, null if username is invalid.
     */
    public function createUser($username)
    {
        // use UserRecord Active Record to look for the specified username
        $userRecord=UsuarioRecord::finder()->findBy_apelido($username);
        if($userRecord instanceof UsuarioRecord) // if found
        {
            $user = new Usuario($this->Manager);
            $user->Name = $userRecord->nome;  // set username
            $user->Roles = $userRecord->grupo; // set role
            $user->IsGuest=false;   // the user is not a guest
            
            $_SESSION['usuario'] = $userRecord;
            return $user;
        }
        else
            return null;
    }
 
    /**
     * Checks if the specified (username, password) is valid.
     * This method is required by TDbUser.
     * @param string username
     * @param string password
     * @return boolean whether the username and password are valid.
     */
    public function validateUser($username,$password)
    {
        // use UserRecord Active Record to look for the (username, password) pair.
        $user = UsuarioRecord::finder()->findBy_apelido_AND_senha($username,md5($password));
        return $user!==null;
    }
 
    /**
     * @return boolean whether this user is an administrator.
     */
    public function getIsAdmin()
    {
        return $this->isInRole('admin');
    }
}
?>