<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'document',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->document = self::cleanDocument($user->document);
        });

        static::updating(function ($user) {
            if ($user->isDirty('document')) {
                $user->document = self::cleanDocument($user->document);
            }
        });
    }

    /**
     * Get the wallet associated with the user.
     */
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    /**
     * Get the transactions where the user is the sender.
     */
    public function sentTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'sender_id');
    }

    /**
     * Get the transactions where the user is the payee.
     */
    public function receivedTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'payee_id');
    }

    /**
     * Get all transactions where the user is either sender or payee.
     */
    public function transactions()
    {
        return Transaction::where('sender_id', $this->id)
            ->orWhere('payee_id', $this->id)
            ->orderBy('created_at', 'desc');
    }

    /**
     * Check if the user is a common user.
     */
    public function isCommonUser(): bool
    {
        return $this->hasRole('common-user');
    }

    /**
     * Check if the user is a merchant.
     */
    public function isMerchant(): bool
    {
        return $this->hasRole('merchant');
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if the user is support.
     */
    public function isSupport(): bool
    {
        return $this->hasRole('support');
    }

    /**
     * Check if the user is a basic user.
     */
    public function isBasicUser(): bool
    {
        return $this->hasRole('user');
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    /**
     * Get the user's type based on their primary role.
     */
    public function getTypeAttribute(): string
    {
        if ($this->hasRole('admin')) {
            return 'admin';
        }
        if ($this->hasRole('support')) {
            return 'support';
        }
        if ($this->hasRole('common-user')) {
            return 'common';
        }
        if ($this->hasRole('merchant')) {
            return 'merchant';
        }
        if ($this->hasRole('user')) {
            return 'user';
        }
        
        return 'user'; // Default fallback
    }

    /**
     * Get the formatted document (CPF/CNPJ).
     */
    public function getFormattedDocumentAttribute(): string
    {
        $document = $this->document;
        
        if (strlen($document) === 11) {
            // CPF: 000.000.000-00
            return substr($document, 0, 3) . '.' . 
                   substr($document, 3, 3) . '.' . 
                   substr($document, 6, 3) . '-' . 
                   substr($document, 9, 2);
        } elseif (strlen($document) === 14) {
            // CNPJ: 00.000.000/0000-00
            return substr($document, 0, 2) . '.' . 
                   substr($document, 2, 3) . '.' . 
                   substr($document, 5, 3) . '/' . 
                   substr($document, 8, 4) . '-' . 
                   substr($document, 12, 2);
        }
        
        return $document;
    }

    /**
     * Get the document type (CPF or CNPJ).
     */
    public function getDocumentTypeAttribute(): string
    {
        return strlen($this->document) === 11 ? 'CPF' : 'CNPJ';
    }

    /**
     * Check if the user can send money.
     */
    public function canSendMoney(): bool
    {
        return $this->isCommonUser();
    }

    /**
     * Check if the user can receive money.
     */
    public function canReceiveMoney(): bool
    {
        return true; // Both common users and merchants can receive money
    }

    /**
     * Get the user's current balance.
     */
    public function getBalanceAttribute(): float
    {
        return $this->wallet?->balance ?? 0.0;
    }

    /**
     * Clean document by removing non-numeric characters.
     */
    public static function cleanDocument(string $document): string
    {
        return preg_replace('/[^0-9]/', '', $document);
    }

    /**
     * Validate CPF.
     */
    public static function isValidCpf(string $cpf): bool
    {
        $cpf = self::cleanDocument($cpf);
        
        if (strlen($cpf) !== 11 || preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validate CNPJ.
     */
    public static function isValidCnpj(string $cnpj): bool
    {
        $cnpj = self::cleanDocument($cnpj);
        
        if (strlen($cnpj) !== 14 || preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        // Primeiro dígito verificador
        for ($i = 0, $j = 5, $sum = 0; $i < 12; $i++) {
            $sum += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $remainder = $sum % 11;
        $firstDigit = ($remainder < 2) ? 0 : 11 - $remainder;

        // Segundo dígito verificador
        for ($i = 0, $j = 6, $sum = 0; $i < 13; $i++) {
            $sum += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $remainder = $sum % 11;
        $secondDigit = ($remainder < 2) ? 0 : 11 - $remainder;

        return $cnpj[12] == $firstDigit && $cnpj[13] == $secondDigit;
    }

    /**
     * Validate document (CPF or CNPJ).
     */
    public static function isValidDocument(string $document): bool
    {
        $document = self::cleanDocument($document);
        
        if (strlen($document) === 11) {
            return self::isValidCpf($document);
        } elseif (strlen($document) === 14) {
            return self::isValidCnpj($document);
        }
        
        return false;
    }
}
