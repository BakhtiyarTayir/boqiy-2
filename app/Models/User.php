<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Cache;
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
	
	public const QORAQALPOQ_RES = 1;
	public const ANDIJON_VIL = 2;
	public const BUXORO_VIL = 3;
	public const FARGONA_VIL = 4;
	public const JIZZAX_VIL = 5;
	public const XORAZM_VIL = 6;
	public const NAMANGAN_VIL = 7;
	public const NAVOIY_VIL = 8;
	public const QASHQARADARYO_VIL = 10;
	public const SAMARQAND_VIL = 11;
	public const SURXONDARYO_VIL = 12;
	public const SIRDARYO8_VIL = 12;
	public const TOSHKENT_VIL = 13;
	public const TOSHKENT_SH = 14;

	
	public const REGIONS = [
	    self::QORAQALPOQ_RES  => 'Qoraqalpogʻiston Respublikasi',
	    self::ANDIJON_VIL  => 'Andijon viloyati',
	    self::BUXORO_VIL  => 'Buxoro viloyati',
	    self::FARGONA_VIL  => 'Fargʻona viloyati',
	    self::JIZZAX_VIL  => 'Jizzax viloyati',
	    self::XORAZM_VIL  => 'Xorazm viloyati',
	    self::NAMANGAN_VIL  => 'Namangan viloyati',
	    self::NAVOIY_VIL  => 'Navoiy viloyati',
	    self::QASHQARADARYO_VIL  => 'Qashqadaryo viloyati',
	    self::SAMARQAND_VIL => 'Samarqand viloyati',
	    self::SIRDARYO8_VIL => 'Sirdaryo viloyati',
	    self::SURXONDARYO_VIL => 'Surxondaryo viloyati',
	    self::TOSHKENT_VIL => 'Toshkent viloyati',
	    self::TOSHKENT_SH => 'Toshkent shahri',
	];

	public const DISTRICTS = [
	self::QORAQALPOQ_RES => [
		1 => 'Amudaryo',
		2 => 'Beruniy',
		3 => 'Boʻzatov',
		4 => 'Chimboy',
		5 => 'Ellikqalʼa',
		6 => 'Kegeyli',
		7 => 'Moʻynoq',
		8 => 'Nukus',
		9 => 'Qanlikoʻl',
		10 => 'Qoraoʻzak',
		11 => 'Qoʻngʻirot',
		12 => 'Shumanay',
		13 => 'Taxtakoʻpir',
		14 => 'Toʻrtkoʻl',
		15 => 'Xoʻjayli',
		16 => 'Taxiatosh',
	],
	self::ANDIJON_VIL => [
		1 => 'Andijon',
		2 => 'Asaka',
		3 => 'Baliqchi',
		4 => 'Boʻston',
		5 => 'Buloqboshi',
		6 => 'Izboskan',
		7 => 'Jalaquduq',
		8 => 'Xoʻjaobod',
		9 => 'Qoʻrgʻontepa',
		10 => 'Marhamat',
		11 => 'Oltinkoʻl',
		12 => 'Paxtaobod',
		13 => 'Shahrixon',
		14 => 'Ulugʻnor',
	],
	self::BUXORO_VIL => [
		1 => 'Buxoro',
		2 => 'Gʻijduvon',
		3 => 'Jondor',
		4 => 'Kogon',
		5 => 'Olot',
		6 => 'Peshku',
		7 => 'Qorakoʻl',
		8 => 'Qorovulbozor',
		9 => 'Romitan',
		10 => 'Shofirkon',
		11 => 'Vobkent',
	],
	self::FARGONA_VIL => [
		1 => 'Beshariq',
		2 => 'Bogʻdod',
		3 => 'Buvayda',
		4 => 'Dangʻara',
		5 => 'Fargʻona',
		6 => 'Furqat',
		7 => 'Oltiariq',
		8 => 'Oʻzbekiston',
		9 => 'Qoʻshtepa',
		10 => 'Quva',
		11 => 'Rishton',
		12 => 'Soʻx',
		13 => 'Toshloq',
		14 => 'Uchkoʻprik',
		15 => 'Yozyovon',
	],
	self::JIZZAX_VIL => [
		1 => 'Arnasoy',
		2 => 'Baxmal',
		3 => 'Doʻstlik',
		4 => 'Forish',
		5 => 'Gʻallaorol',
		6 => 'Jizzax',
		7 => 'Mirzachoʻl',
		8 => 'Paxtakor',
		9 => 'Sharof Rashidov',
		10 => 'Yangiobod',
		11 => 'Zarbdor',
		12 => 'Zafarobod',
		13 => 'Zomin',
	],
	self::NAMANGAN_VIL => [
		1 => 'Chortoq',
		2 => 'Chust',
		3 => 'Kosonsoy',
		4 => 'Mingbuloq',
		5 => 'Namangan',
		6 => 'Norin',
		7 => 'Pop',
		8 => 'Toʻraqoʻrgʻon',
		9 => 'Uychi',
		10 => 'Uchqoʻrgʻon',
		11 => 'Yangiqoʻrgʻon',
	],
	self::NAVOIY_VIL => [
		1 => 'Karmana',
		2 => 'Konimex',
		3 => 'Navbahor',
		4 => 'Nurota',
		5 => 'Qiziltepa',
		6 => 'Tomdi',
		7 => 'Uchquduq',
		8 => 'Xatirchi',
	],
	self::QASHQARADARYO_VIL => [
		1 => 'Chiroqchi',
		2 => 'Dehqonobod',
		3 => 'Gʻuzor',
		4 => 'Kasbi',
		5 => 'Kitob',
		6 => 'Koson',
		7 => 'Mirishkor',
		8 => 'Muborak',
		9 => 'Nishon',
		10 => 'Qamashi',
		11 => 'Qarshi',
		12 => 'Shahrisabz',
		13 => 'Yakkabogʻ',
	],
	self::SAMARQAND_VIL => [
		1 => 'Bulungʻur',
		2 => 'Ishtixon',
		3 => 'Jomboy',
		4 => 'Kattaqoʻrgʻon',
		5 => 'Narpay',
		6 => 'Nurobod',
		7 => 'Oqdaryo',
		8 => 'Paxtachi',
		9 => 'Payariq',
		10 => 'Pastdargʻom',
		11 => 'Samarqand',
		12 => 'Toyloq',
		13 => 'Urgut',
	],
	self::SIRDARYO8_VIL => [
		1 => 'Boyovut',
		2 => 'Guliston',
		3 => 'Mirzaobod',
		4 => 'Oqoltin',
		5 => 'Sardoba',
		6 => 'Sayxunobod',
		7 => 'Sirdaryo',
		8 => 'Xovos',
	],
	self::SURXONDARYO_VIL => [
		1 => 'Angor',
		2 => 'Bandixon',
		3 => 'Boysun',
		4 => 'Denov',
		5 => 'Jarqoʻrgʻon',
		6 => 'Muzrabot',
		7 => 'Oltinsoy',
		8 => 'Qiziriq',
		9 => 'Sariosiyo',
		10 => 'Sherobod',
		11 => 'Shoʻrchi',
		12 => 'Termiz',
		13 => 'Uzun',
	],
	self::TOSHKENT_VIL => [
		1 => 'Bekobod',
		2 => 'Boʻka',
		3 => 'Boʻstonliq',
		4 => 'Chinoz',
		5 => 'Ohangaron',
		6 => 'Oqqoʻrgʻon',
		7 => 'Parkent',
		8 => 'Piskent',
		9 => 'Qibray',
		10 => 'Quyichirchiq',
		11 => 'Oʻrtachirchiq',
		12 => 'Yangiyoʻl',
		13 => 'Yuqorichirchiq',
		14 => 'Zangiota',
		15 => 'Angren',
	],
	self::TOSHKENT_SH => [
		1 => 'Bektemir',
		2 => 'Chilonzor',
		3 => 'Mirobod',
		4 => 'Mirzo Ulugʻbek',
		5 => 'Sergeli',
		6 => 'Shayxontohur',
		7 => 'Olmazor',
		8 => 'Uchtepa',
		9 => 'Yakkasaroy',
		10 => 'Yashnobod',
		11 => 'Yunusobod',
		12 => 'Yangi Hayot'
	],
	self::XORAZM_VIL => [
		1 => 'Bogʻot',
		2 => 'Gurlan',
		3 => 'Hazorasp',
		4 => 'Xiva',
		5 => 'Qoʻshkoʻpir',
		6 => 'Shovot',
		7 => 'Urganch',
		8 => 'Xonqa',
		9 => 'Yangiariq',
		10 => 'Yangibozor',
		11 => 'Tuproqqalʼa',
		],
	];
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_role',
        'user_name',
        'nickname',
        'username',
        'gender',
        'friends',
        'followers',
        'studied_at',
        'profession',
        'job',
        'marital_status',
        'date_of_birth',
        'photo',
        'about',
        'phone',
        'address',
        'regionId',
        'districtId',
        'cover_photo',
        'status',
        'timezone',
        'lastActive',
        'email_verified_at',
        'created_at',
        'updated_at',
    ];

	// @todo Asilbek change
	public function getAuthIdentifierName()
	{
		return 'phone';
	}

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // /**
    //  * The attributes that should be cast.
    //  *
    //  * @var array<string, string>
    //  */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];



    public function isOnline(){
        return Cache::has('user-is-online-'.$this->id);
    }

    public static function get_user_image($file_name = "", $optimized = ""){
        $optimized = $optimized.'/';
        if(base_path('public/storage/userimage/'.$optimized.$file_name) && is_file('public/storage/userimage/'.$optimized.$file_name)){
            return asset('storage/userimage/'.$optimized.$file_name);
        }else{
            return asset('storage/userimage/default.png');
        }
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }
    
    /**
     * Получить баланс лайков пользователя
     */
    public function likeBalance()
    {
        return $this->hasOne(LikeBalance::class);
    }
    
    /**
     * Получить все лайки, поставленные пользователем
     */
    public function postLikes()
    {
        return $this->hasMany(PostLike::class);
    }
}
