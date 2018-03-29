<?php

namespace App\Models\Auth;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'TBUSUARIO';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['usuario', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    
    protected $primaryKey = 'CODIGO';
    
    //protected $connection = 'firebird2';
    
    public $timestamps = false;
    
    
    public function getAuthPassword() {
    	return $this->PASSWORD;
    }
	
	/* DESABILITAR REMEMBER */
    public function getRememberToken()
    {
    	return null;
    }
    
    public function setRememberToken($value)
    {
    	// 
    }
    
    public function getRememberTokenName()
    {
    	return null;
    }
    
    /**
     * Overrides the method to ignore the remember token.
     */
    public function setAttribute($key, $value)
    {
        $isRememberTokenAttribute = $key == $this->getRememberTokenName();
        if (!$isRememberTokenAttribute)
        {
            parent::setAttribute($key, $value);
        }
    }

    /**
     * Overrides the method to ignore the remember token.
     */
    public function setNome($value)
    {
        $this->USUARIO = $value;
    }
    
}
