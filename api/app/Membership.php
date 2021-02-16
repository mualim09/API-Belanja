<?php

namespace PondokCoder;

use Firebase\JWT\JWT;
use PondokCoder\Utility as Utility;

class Membership extends Utility
{
    static $pdo, $query;

    protected static function getConn()
    {
        return self::$pdo;
    }

    public function __construct($connection)
    {
        self::$pdo = $connection;
        self::$query = new Query(self::$pdo);
    }

    public function __GET__($parameter = array())
    {
        //
    }

    public function __POST__($parameter = array())
    {
        switch ($parameter['request']) {
            case 'login':
                return self::login($parameter);
                break;
            case 'register':
                return self::register($parameter);
                break;
            default:
                return array();
                break;
        }
    }

    private function register($parameter) {
        $uid = parent::gen_uuid();
        //Check Email
        $Mailer = new Mailer();
        $Email = $Mailer->send(array(
            'server' => 'mail.pondokcoder.com',
            'secure_type' => false,
            'port' => 587,
            'username' => 'belanja_sukses@pondokcoder.com',
            //'username' => 'belanja_sukses',
            'password' => __MAIL_PASSWORD__,
            'fromMail' => 'belanja_sukses@pondokcoder.com',
            'fromName' => 'Belanja Sukses',
            'replyMail' => 'belanja_sukses@pondokcoder.com',
            'replyName' => 'Belanja Sukses',
            'template' => 'miscellaneous/email_template/register.phtml'
        ), array(
            '__HOSTNAME__' => __HOSTNAME__,
            '__PC_CUSTOMER__' => __PC_CUSTOMER__,
            '__NAMA__' => 'Uji',
            '__UID__' => $uid
        ), 'Registrasi ' . __PC_CUSTOMER__,'Uji html','text mail:uji html',array(
            'tanaka@pondokcoder.com' => 'Hendry Tanaka'
        ));

        return array(
            'email_result' => $Email
        );
    }


    private function login($parameter) {
        $responseBuilder = array();
        $query = self::$query->select('membership', array(
            'uid',
            'nik',
            'nama',
            'tempat_lahir',
            'tanggal_lahir',
            'email',
            'kontak_telp',
            'kontak_whatsapp',
            'npwp',
            'alamat_ktp',
            'kelurahan',
            'kecamatan',
            'kabupaten',
            'provinsi',
            'kode_pos',
            'alamat_domisili',
            'rt',
            'rw',
            'patokan',
            'kelurahan_domisili',
            'kecamatan_domisili',
            'kabupaten_domisili',
            'provinsi_domisili',
            'kode_pos_domisili',
            'nomor_rekening',
            'nama_bank',
            'nama_pemilik_rekening',
            'nama_ahli_waris',
            'hubungan_ahli_waris',
            'kontak_telp_ahli_waris',
            'kontak_whatsapp_ahli_waris',
            'saldo',
            'password',
            'jenis_member',
            'status_member'
        ))
            ->where(array(), array())
            ->execute();
        //$query->execute(array($parameter['email']));

        if(count($query['response_data']) > 0) {
            //$read = $query->fetchAll(\PDO::FETCH_ASSOC);
            $read = $query['response_data'];
            if(password_verify($parameter['password'], $read[0]['password'])) {

                $log = parent::log(array(
                    'type' => 'login',
                    'column' => array('user_uid','login_meta','logged_at'),
                    'value' => array($read[0]['uid'],'[' . $read[0]['uid'] . '][' . $read[0]['email'] . '] Success Logged In.', parent::format_date()),
                    'class' => 'User'
                ));

                //Register JWT
                $iss = __HOSTNAME__;
                $iat = time();
                $nbf = $iat + 10;
                $exp = $iat + 30;
                $aud = 'users_library';
                $user_arr_data = array(
                    'uid' => $read[0]['uid'],
                    'email' => $read[0]['email'],
                    'nama' => $read[0]['nama'],
                    'log_id' => $log
                );
                //$secret_key = bin2hex(random_bytes(32));
                $secret_key = file_get_contents('taknakal.pub');
                $payload_info = array(
                    'iss' => $iss,
                    'iat' => $iat,
                    'nbf' => $nbf,
                    'exp' => $exp,
                    'aud' => $aud,
                    'data' => $user_arr_data,
                );
                $jwt = JWT::encode($payload_info, $secret_key);

                $_SESSION['token'] = $jwt;
                $_SESSION['uid'] = $read[0]['uid'];
                $_SESSION['email'] = $read[0]['email'];
                $_SESSION['nama'] = $read[0]['nama'];
                $_SESSION['password'] = $read[0]['password'];
                $responseBuilder['response_result'] = count($query['response_data']);
                $responseBuilder['response_message'] = 'Login berhasil';
                $responseBuilder['response_token'] = $jwt;

                $responseBuilder['response_access'] = array();
                $responseBuilder['response_data'] = $query['response_data'];

            } else {
                $responseBuilder['response_result'] = 0;
                $responseBuilder['response_message'] = 'Email / password salah';
            }
        } else {
            $responseBuilder['response_result'] = count($query['response_data']);
            $responseBuilder['response_message'] = 'Email / password salah';
        }

        return $responseBuilder;
    }
}
?>