<?php

namespace PondokCoder;

use Firebase\JWT\JWT;
use PondokCoder\Authorization as Authorization;
use PondokCoder\QueryException as QueryException;
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
        try {
            switch ($parameter[1]) {
                case 'detail':
                    return self::customer_detail($parameter[2]);
                    break;
                case 'get_customer_select2':
                    return self::get_customer_select2($parameter);
                    break;
                default:
                    return array();
                    break;
            }
        } catch (QueryException $e) {
            return 'Error => ' . $e;
        }
    }

    public function __DELETE__($parameter = array())
    {
        return self::delete($parameter);
    }

    public function __POST__($parameter = array())
    {
        switch ($parameter['request']) {
            case 'login':
                return self::login($parameter);
                break;
            case 'check_nik':
                return self::check_nik($parameter);
                break;
            case 'get_customer':
                return self::get_customer($parameter);
                break;
            case 'tambah_customer':
                return self::tambah_customer($parameter);
                break;
            case 'edit_customer':
                return self::edit_customer($parameter);
                break;
            case 'register':
                return self::register($parameter);
                break;
            case 'register_android':
                return self::register_android($parameter);
                break;
            default:
                return array();
                break;
        }
    }

    private function get_customer_select2($parameter) {
        $data = self::$query
            ->select('membership', array(
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
                'bank',
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
            ->where(array(
                'membership.deleted_at' => 'IS NULL',
                'AND',
                '(membership.nik' => 'ILIKE ' . '\'%' . $_GET['search'] . '%\'',
                'OR',
                'membership.nama' => 'ILIKE ' . '\'%' . $_GET['search'] . '%\')'
            ))
            ->limit(10)
            ->execute();

        $autonum = 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;

            $autonum++;
        }
        return $data;
    }

    public function customer_detail($parameter) {
        $data = self::$query->select('membership', array(
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
            'bank',
            'mentor',
            'patokan',
            'kelurahan_domisili',
            'kecamatan_domisili',
            'kabupaten_domisili',
            'provinsi_domisili',
            'kode_pos_domisili',
            'nomor_rekening',
            'bank',
            'nama_pemilik_rekening',
            'nama_ahli_waris',
            'hubungan_ahli_waris',
            'kontak_telp_ahli_waris',
            'kontak_whatsapp_ahli_waris',
            'saldo',
            'password',
            'jenis_member',
            'status_member',
            'created_at',
            'updated_at'
        ))
            ->where(array(
                'membership.deleted_at' => 'IS NULL',
                'AND',
                'membership.uid' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();
        $Bank = new Bank(self::$pdo);
        $Pegawai = new Pegawai(self::$pdo);
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['bank'] = $Bank->bank_detail($value['bank'])['response_data'][0];
            $data['response_data'][$key]['mentor'] = $Pegawai->get_detail($value['mentor'])['response_data'][0];
        }
        return $data;
    }

    public function check_nik($parameter) {
        $data = self::$query->select('membership', array(
            'uid'
        ))
            ->where(array(
                'membership.nik' => '= ?'
            ), array(
                $parameter['nik']
            ))
            ->execute();
        return $data;
    }

    private function edit_customer($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $uid = parent::gen_uuid();
        $password = parent::generatePassword(6);
        $process = self::$query->update('membership', array(
            'nik' => $parameter['nik'],
            'nama' => strtoupper($parameter['nama']),
            'tempat_lahir' => $parameter['tempat_lahir'],
            'tanggal_lahir' => $parameter['tanggal_lahir'],
            'email' => $parameter['email'],
            'kontak_telp' => $parameter['kontak_telp'],
            'kontak_whatsapp' => $parameter['kontak_whatsapp'],
            'npwp' => $parameter['npwp'],
            'alamat_ktp' => $parameter['alamat_ktp'],
            'kelurahan' => intval($parameter['kelurahan']),
            'kecamatan' => intval($parameter['kecamatan']),
            'kabupaten' => intval($parameter['kabupaten']),
            'provinsi' => intval($parameter['provinsi']),
            'kode_pos' => $parameter['kode_pos'],
            'alamat_domisili' => $parameter['alamat_domisili'],
            'rt' => $parameter['rt'],
            'rw' => $parameter['rw'],
            'patokan' => $parameter['patokan'],
            'kelurahan_domisili' => intval($parameter['kelurahan_domisili']),
            'kecamatan_domisili' => intval($parameter['kecamatan_domisili']),
            'kabupaten_domisili' => intval($parameter['kabupaten_domisili']),
            'provinsi_domisili' => intval($parameter['provinsi_domisili']),
            'kode_pos_domisili' => $parameter['kode_pos_domisili'],
            'nomor_rekening' => $parameter['nomor_rekening'],
            'bank' => $parameter['bank'],
            'nama_pemilik_rekening' => $parameter['nama_pemilik_rekening'],
            'nama_ahli_waris' => $parameter['nama_ahli_waris'],
            'hubungan_ahli_waris' => $parameter['hubungan_ahli_waris'],
            'kontak_telp_ahli_waris' => $parameter['kontak_telp_ahli_waris'],
            'kontak_whatsapp_ahli_waris' => $parameter['kontak_whatsapp_ahli_waris'],
            'saldo' => 0,
            'mentor' => $parameter['mentor'],
            'password' => password_hash('123456', PASSWORD_DEFAULT),
            'jenis_member' => $parameter['jenis_member'],
            'status_member' => 'N',
            'creator' => $UserData['data']->uid,
            'created_at' => parent::format_date(),
            'updated_at' => parent::format_date()
        ))
            ->where(array(
                'membership.deleted_at' => 'IS NULL',
                'AND',
                'membership.uid' => '= ?'
            ), array(
                $parameter['uid']
            ))
            ->execute();
        return $process;
    }

    private function tambah_customer($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $uid = parent::gen_uuid();
        $password = parent::generatePassword(6);
        $process = self::$query->insert('membership', array(
            'uid' => $uid,
            'nik' => $parameter['nik'],
            'nama' => strtoupper($parameter['nama']),
            'tempat_lahir' => $parameter['tempat_lahir'],
            'tanggal_lahir' => $parameter['tanggal_lahir'],
            'email' => $parameter['email'],
            'kontak_telp' => $parameter['kontak_telp'],
            'kontak_whatsapp' => $parameter['kontak_whatsapp'],
            'npwp' => $parameter['npwp'],
            'alamat_ktp' => $parameter['alamat_ktp'],
            'kelurahan' => intval($parameter['kelurahan']),
            'kecamatan' => intval($parameter['kecamatan']),
            'kabupaten' => intval($parameter['kabupaten']),
            'provinsi' => intval($parameter['provinsi']),
            'kode_pos' => $parameter['kode_pos'],
            'alamat_domisili' => $parameter['alamat_domisili'],
            'rt' => $parameter['rt'],
            'rw' => $parameter['rw'],
            'patokan' => $parameter['patokan'],
            'kelurahan_domisili' => intval($parameter['kelurahan_domisili']),
            'kecamatan_domisili' => intval($parameter['kecamatan_domisili']),
            'kabupaten_domisili' => intval($parameter['kabupaten_domisili']),
            'provinsi_domisili' => intval($parameter['provinsi_domisili']),
            'kode_pos_domisili' => $parameter['kode_pos_domisili'],
            'nomor_rekening' => $parameter['nomor_rekening'],
            'bank' => $parameter['bank'],
            'nama_pemilik_rekening' => $parameter['nama_pemilik_rekening'],
            'nama_ahli_waris' => $parameter['nama_ahli_waris'],
            'hubungan_ahli_waris' => $parameter['hubungan_ahli_waris'],
            'kontak_telp_ahli_waris' => $parameter['kontak_telp_ahli_waris'],
            'kontak_whatsapp_ahli_waris' => $parameter['kontak_whatsapp_ahli_waris'],
            'saldo' => 0,
            'mentor' => $parameter['mentor'],
            'password' => password_hash('123456', PASSWORD_DEFAULT),
            'jenis_member' => $parameter['jenis_member'],
            'status_member' => 'N',
            'creator' => $UserData['data']->uid,
            'created_at' => parent::format_date(),
            'updated_at' => parent::format_date()
        ))
            ->execute();
        return $process;
    }

    private function delete($parameter)
    {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $worker = self::$query
            ->delete($parameter[6])
            ->where(array(
                $parameter[6] . '.uid' => '= ?'
            ), array(
                $parameter[7]
            ))
            ->execute();
        if ($worker['response_result'] > 0) {
            $log = parent::log(array(
                'type' => 'activity',
                'column' => array(
                    'unique_target',
                    'user_uid',
                    'table_name',
                    'action',
                    'logged_at',
                    'status',
                    'login_id'
                ),
                'value' => array(
                    $parameter[7],
                    $UserData['data']->uid,
                    $parameter[6],
                    'D',
                    parent::format_date(),
                    'N',
                    $UserData['data']->log_id
                ),
                'class' => __CLASS__
            ));
        }
        return $worker;
    }

    private function register_android($parameter) {
        $builder = $parameter;
        $data = json_decode($parameter['data'], true);
        foreach ($data as $key => $value) {
            $builder[$key] = $value;
        }

        return self::register($builder);
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
            /*return array(
                'check' => $data,
                'status' => 403,
                'keterangan' => 'Email / NIK / Kontak telp / Kontak whatsapp sudah digunakan. Silahkan gunakan data lain'
            );*/
            return array(
                'response_package' => $parameter,
                'response_result' => 0,
                'response_message' => 'Email / NIK sudah pernah di daftarkan',
                'response_access' => array()
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

            /*return array(
                'check' => $data,
                'status' => 200,
                'verif_result' => $Verif,
                'query_result' => $new,
                'message' => (intval($new['response_result']) > 0) ? 'Berhasil didaftarkan' : ((count($data['response_data']) > 0) ? 'Email sudah pernah di daftarkan' : 'Gagal daftar')
            );*/

            return array(
                'response_package' => $parameter,
                'response_result' => (isset($new['response_result']) ? $new['response_result'] : 0),
                'response_message' => (intval($new['response_result']) > 0) ? 'Berhasil didaftarkan' : ((count($data['response_data']) > 0) ? 'Email sudah pernah di daftarkan' : 'Gagal daftar'),
                'response_access' => array()
            );
        }
    }

    private function get_customer($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            if($parameter['jenis'] === 'A') {
                $paramData = array(
                    'membership.deleted_at' => 'IS NULL',
                    'AND',
                    '(membership.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                    'OR',
                    'membership.nik' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')'
                );

                $paramValue = array();
            } else {
                $paramData = array(
                    'membership.deleted_at' => 'IS NULL',
                    'AND',
                    'membership.jenis_member' => '= ?',
                    'AND',
                    '(membership.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                    'OR',
                    'membership.nik' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')'
                );

                $paramValue = array($parameter['jenis']);
            }

        } else {
            if($parameter['jenis'] === 'A') {
                $paramData = array(
                    'membership.deleted_at' => 'IS NULL'
                );

                $paramValue = array();
            } else {
                $paramData = array(
                    'membership.deleted_at' => 'IS NULL',
                    'AND',
                    'membership.jenis_member' => '= ?'
                );

                $paramValue = array($parameter['jenis']);
            }
        }


        if ($parameter['length'] < 0) {
            $data = self::$query->select('membership', array(
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
                'bank',
                'nama_pemilik_rekening',
                'nama_ahli_waris',
                'hubungan_ahli_waris',
                'kontak_telp_ahli_waris',
                'kontak_whatsapp_ahli_waris',
                'saldo',
                'password',
                'jenis_member',
                'status_member',
                'created_at',
                'updated_at'
            ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('membership', array(
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
                'bank',
                'nama_pemilik_rekening',
                'nama_ahli_waris',
                'hubungan_ahli_waris',
                'kontak_telp_ahli_waris',
                'kontak_whatsapp_ahli_waris',
                'saldo',
                'password',
                'jenis_member',
                'status_member',
                'created_at',
                'updated_at'
            ))
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }

        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {


            $data['response_data'][$key]['autonum'] = $autonum;
            $data['response_data'][$key]['created_at_parsed'] = date('d F Y', strtotime($value['created_at']));
            /*if(file_exists('../images/produk/' . $value['uid'] . '.png')) {
                $data['response_data'][$key]['image'] = 'images/produk/' . $value['uid'] . '.png';
            } else {
                $data['response_data'][$key]['image'] = 'images/product.png';
            }*/

            $autonum++;
        }

        $itemTotal = self::$query->select('membership', array(
            'uid'
        ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($itemTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
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
            'bank',
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