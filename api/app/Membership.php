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
        switch ($parameter[1]) {
            case 'activate':
                self::activate($parameter[2]);
                break;
            case 'decline':
                self::decline($parameter[2]);
                break;
        }
    }

    public function check_status($parameter) {
        $data = self::$query->select('membership', array(
            'status_member'
        ))
            ->where(array(
                'membership.uid' => '= ?',
                'AND',
                'membership.deleted_at' => 'IS NULL'
            ), array(
                $parameter
            ))
            ->execute();
        if(count($data['response_data']) > 0) {
            $status = '';
            switch ($data['response_data'][0]['status_member']) {
                case 'N':
                    $status = 'Member Nonaktif (Belum Verifikasi)';
                    break;
                case 'V':
                    $status = 'Member sudah diverifikasi. Harap melakukan pembayararan';
                    break;
                case 'A':
                    $status = 'Member aktif';
                    break;
                case 'S':
                    $status = 'Member di suspend';
                    break;
                default:
                    $status = 'Status tidak dikenali';
            }

            return array(
                'status' => $data['response_data'][0]['status_member'],
                'keterangan' => $status
            );
        } else {
            return array(
                'status' => 404,
                'keterangan' => 'Tidak ditemukan'
            );
        }
    }

    public function activate($parameter) {
        $status = self::check_status($parameter);
        if($status['status'] !== 404) {
            if($status['status'] === 'N') {
                $update = self::$query->update('membership', array(
                    'status_member' => 'V'
                ))
                    ->where(array(
                        'membership.uid' => '= ?',
                        'AND',
                        'membership.deleted_at' => 'IS NULL'
                    ), array(
                        $parameter
                    ))
                    ->execute();
                /*return array(
                    'status' => $update['response_result'],
                    'keterangan' => ($update['response_result'] > 0) ? 'Berhasil Verifikasi' : 'Gagal Verifikasi'
                );*/

                $format = array(
                    '__HOSTNAME__' => __HOSTNAME__,
                    '__HOSTAPI__' => __HOSTAPI__,
                    '__PC_CUSTOMER__' => __PC_CUSTOMER__,
                    '__PESAN__' => ($update['response_result'] > 0) ? 'Berhasil Verifikasi' : 'Gagal Verifikasi'
                );
            } else {
                $format = array(
                    '__HOSTNAME__' => __HOSTNAME__,
                    '__HOSTAPI__' => __HOSTAPI__,
                    '__PC_CUSTOMER__' => __PC_CUSTOMER__,
                    '__PESAN__' => $status['keterangan']
                );
            }
        } else {
            $format = array(
                '__HOSTNAME__' => __HOSTNAME__,
                '__HOSTAPI__' => __HOSTAPI__,
                '__PC_CUSTOMER__' => __PC_CUSTOMER__,
                '__PESAN__' => $status['keterangan']
            );
        }

        echo parent::parse_template('../miscellaneous/email_template/membership_info.phtml', $format);
    }

    public function decline($parameter) {
        $status = self::check_status($parameter);
        if($status['status'] !== 404) {
            if($status['status'] === 'N') {
                $update = self::$query->update('membership', array(
                    'status_member' => 'S'
                ))
                    ->where(array(
                        'membership.uid' => '= ?',
                        'AND',
                        'membership.deleted_at' => 'IS NULL'
                    ), array(
                        $parameter
                    ))
                    ->execute();
                return array(
                    'status' => $update['response_result'],
                    'keterangan' => ($update['response_result'] > 0) ? 'Berhasil Suspend' : 'Gagal Suspend'
                );
            } else {
                return $status;
            }
        } else {
            return $status;
        }
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
        //Check Email
        $data = self::$query->select('membership', array(
            'uid'
        ))
            ->where(array(
                '(membership.email' => '= ?',
                'OR',
                'membership.nik' => '= ?',
                'OR',
                'membership.kontak_telp' => '= ?',
                'OR',
                'membership.kontak_whatsapp' => '= ?)',
                'AND',
                'membership.deleted_at' => 'IS NULL'
            ), array(
                $parameter['email'],
                $parameter['nik'],
                $parameter['kontak_telp'],
                $parameter['kontak_whatsapp']
            ))
            ->execute();

        if(count($data['response_data']) > 0) {
            return array(
                'check' => $data,
                'status' => 403,
                'keterangan' => 'Email / NIK / Kontak telp / Kontak whatsapp sudah digunakan. Silahkan gunakan data lain'
            );
        } else {
            $uid = parent::gen_uuid();
            $password = parent::generatePassword(6);
            $new = self::$query->insert('membership', array(
                'uid' => $uid,
                'nik' => $parameter['nik'],
                'nama' => $parameter['nama'],
                'tempat_lahir' => $parameter['tempat_lahir'],
                'tanggal_lahir' => $parameter['tanggal_lahir'],
                'email' => $parameter['email'],
                'kontak_telp' => $parameter['kontak_telp'],
                'kontak_whatsapp' => $parameter['kontak_whatsapp'],
                /*'npwp' => $parameter['npwp'],
                'alamat_ktp' => $parameter['alamat_ktp'],
                'kelurahan' => $parameter['kelurahan'],
                'kecamatan' => $parameter['kecamatan'],
                'kabupaten' => $parameter['kabupaten'],
                'provinsi' => $parameter['provinsi'],
                'kode_pos' => $parameter['kode_pos'],
                'alamat_domisili' => $parameter['alamat_domisili'],
                'rt' => $parameter['rt'],
                'rw' => $parameter['rw'],
                'patokan' => $parameter['patokan'],
                'kelurahan_domisili' => $parameter['kelurahan_domisili'],
                'kecamatan_domisili' => $parameter['kecamatan_domisili'],
                'kabupaten_domisili' => $parameter['kabupaten_domisili'],
                'provinsi_domisili' => $parameter['provinsi_domisili'],
                'kode_pos_domisili' => $parameter['kode_pos_domisili'],
                'nomor_rekening' => $parameter['nomor_rekening'],
                'nama_bank' => $parameter['nama_bank'],
                'nama_pemilik_rekening' => $parameter['nama_pemilik_rekening'],
                'nama_ahli_waris' => $parameter['nama_ahli_waris'],
                'hubungan_ahli_waris' => $parameter['hubungan_ahli_waris'],
                'kontak_telp_ahli_waris' => $parameter['kontak_telp_ahli_waris'],
                'kontak_whatsapp_ahli_waris' => $parameter['kontak_whatsapp_ahli_waris'],*/
                'saldo' => 0,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'jenis_member' => $parameter['jenis_member'],
                'status_member' => 'N',
                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date()
            ))
                ->execute();
            if($new['response_result'] > 0) {
                if(intval($parameter['verif_by']) === 1) {
                    $Mailer = new Mailer();
                    $Verif = $Mailer->send(array(
                        'server' => 'mail.pondokcoder.com',
                        'secure_type' => false,
                        'port' => 587,
                        'username' => 'belanja_sukses@pondokcoder.com',
                        'password' => __MAIL_PASSWORD__,
                        'fromMail' => 'belanja_sukses@pondokcoder.com',
                        'fromName' => 'Belanja Sukses',
                        'replyMail' => 'belanja_sukses@pondokcoder.com',
                        'replyName' => 'Belanja Sukses',
                        'template' => 'miscellaneous/email_template/register.phtml'
                    ), array(
                        '__HOSTNAME__' => __HOSTNAME__,
                        '__HOSTAPI__' => __HOSTAPI__,
                        '__PC_CUSTOMER__' => __PC_CUSTOMER__,
                        '__PASSWORD__' => $password,
                        '__NAMA__' => $parameter['nama'],
                        '__UID__' => $uid
                    ), 'Registrasi ' . __PC_CUSTOMER__,'Uji html','
                    Selamat Bergabung, Anda telah terdaftar menjadi member pada ' . __PC_CUSTOMER__ .  '. Untuk menyelesaikan pendaftaran silahkan akses link ' . __HOSTAPI__ . '/Membership/activate/' . $uid, array(
                        'tanaka@pondokcoder.com' => 'Hendry Tanaka'
                    ));
                } else if(intval($parameter['verif_by']) === 2) { //Whatsapp
                    $Verif = parent::postUrl('https://console.zenziva.net/wareguler/api/sendWA/', array(
                        'userkey' => __ZENZIVA_WA_USERKEY__,
                        'passkey' => __ZENZIVA_WA_PASSKEY__,
                        'to' => $parameter['kontak_whatsapp'],
                        //'link' => __HOSTAPI__ . '/Membership/activate/' . $uid,
                        'message' => 'Belanja Sukses! Selamat bergabung dengan kami. Password Akun Anda adalah ' . $password
                    ));
                } else { //SMS
                    $Verif = parent::postUrl('https://console.zenziva.net/reguler/api/sendsms/', array(
                        'userkey' => __ZENZIVA_WA_USERKEY__,
                        'passkey' => __ZENZIVA_WA_PASSKEY__,
                        'to' => $parameter['kontak_telp'],
                        'message' => 'Belanja Sukses! Selamat bergabung dengan kami. Password Akun Anda adalah ' . $password
                    ));
                }
            }

            return array(
                'check' => $data,
                'status' => 200,
                'verif_result' => $Verif,
                'query_result' => $new
            );
        }
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