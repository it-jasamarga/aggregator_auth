<?php

namespace App\Http\Controllers;

use Log;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class LDAPController extends Controller
{
	public function store() {
        request()->validate([
            'username' => 'required|max:200',
            'password' => 'required|max:200'
        ]);

        request()['servers'] = 'ldap://wdc02.jasamarga.co.id';
        $data = $this->checkLDAP(request()->all());
        
        return response([
            'data' => $data
        ]);
    }

    public function checkLDAP($data){
        try {
            $adServer = $data['servers'];
            $ldap = ldap_connect($adServer);
            $ldaprdn = 'jasamarga'.'\\'.$data['username'];
            ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
            $bind = ldap_bind($ldap, $ldaprdn, $data['password']);
            if ($bind) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
