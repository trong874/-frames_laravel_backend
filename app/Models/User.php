<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

/**
 * App\Models\User
 *
 * @property int $id
 * @property int $shop_id
 * @property string|null $username
 * @property int|null $account_type
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_confirmed_at
 * @property string|null $password2
 * @property int|null $is_change_password2
 * @property int|null $google2fa_enable
 * @property string|null $google2fa_secret
 * @property string|null $remember_token
 * @property string|null $balance
 * @property string $balance_in
 * @property string|null $balance_out
 * @property string|null $image
 * @property string|null $cover
 * @property string|null $firstname
 * @property string|null $lastname
 * @property string|null $fullname
 * @property string|null $url_display
 * @property string|null $fullname_display
 * @property int|null $is_kyc
 * @property int|null $is_idol
 * @property string|null $phone
 * @property string|null $birtday
 * @property int|null $gender
 * @property string|null $address
 * @property int|null $status
 * @property string|null $verify_code
 * @property string|null $verify_code_expired_at
 * @property int|null $is_verify
 * @property string|null $odp_code
 * @property string|null $odp_expired_at
 * @property int|null $odp_active
 * @property int|null $odp_fail
 * @property string|null $last_add_balance
 * @property string|null $last_minus_balance
 * @property int|null $is_agency_card
 * @property int|null $is_agency_charge
 * @property string|null $lastlogin_at
 * @property string|null $lastlogout_at
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $social_id
 * @property string|null $social_type
 * @property-read mixed $profile_photo_url
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAccountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBalanceIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBalanceOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBirtday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFullname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFullnameDisplay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGoogle2faEnable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGoogle2faSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsAgencyCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsAgencyCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsChangePassword2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsIdol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsKyc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsVerify($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastAddBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastMinusBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastloginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastlogoutAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOdpActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOdpCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOdpExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOdpFail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSocialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSocialType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUrlDisplay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereVerifyCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereVerifyCodeExpiredAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    const IS_ADMIN = 1;
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    public function isAdmin()
    {
        return $this->account_type === self::IS_ADMIN;
    }
    public function updateProfilePhoto(UploadedFile $photo)
    {
        tap($this->image, function ($previous) use ($photo) {
            $this->forceFill([
                'image' => $photo->storePublicly(
                    'profile-photos', ['disk' => $this->profilePhotoDisk()]
                ),
            ])->save();

            if ($previous) {
                Storage::disk($this->profilePhotoDisk())->delete($previous);
            }
        });
    }
    public function getProfilePhotoUrlAttribute()
    {
        return $this->image
            ? Storage::disk($this->profilePhotoDisk())->url($this->image)
            : $this->defaultProfilePhotoUrl();
    }
    public function deleteProfilePhoto()
    {
        if (! Features::managesProfilePhotos()) {
            return;
        }

        if (is_null($this->image)) {
            return;
        }

        Storage::disk($this->profilePhotoDisk())->delete($this->image);

        $this->forceFill([
            'image' => null,
        ])->save();
    }
    protected function defaultProfilePhotoUrl()
    {
        $name = trim(collect(explode(' ', $this->username))->map(function ($segment) {
            return mb_substr($segment, 0, 1);
        })->join(' '));

        return 'https://ui-avatars.com/api/?name='.urlencode($name).'&color=7F9CF5&background=EBF4FF';
    }
}
