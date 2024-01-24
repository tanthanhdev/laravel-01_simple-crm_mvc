<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
// Models
use App\Models\Contact;
use App\Models\ContactStatus;
use App\Models\Document;
use App\Models\Task;
use App\Models\TaskStatus;

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
        'password',
        'position_title',
        'phone',
        'image',
        'is_admin',
        'is_active',
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
     * The attributes that should be cast / hidden for arrays.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // 'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $guard_name = 'web';

    /**
     * get all contacts assigned to user
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contacts() {
        return $this->hasMany(Contact::class, 'assigned_user_id');
    }

    /**
     * get all leads assigned to user
     */
    public function leads() {
        $INDEX_CONTACT_STATUS__LEAD = 0;

        return $this->hasMany(Contact::class, 'assigned_user_id')
            ->where(
                'status',
                ContactStatus::where(
                    'name',
                    config('seed_data.contact_status')[$INDEX_CONTACT_STATUS__LEAD]
                )->first()->id
            );
    }

    /**
     * get all opportunities assigned to user
     */
    public function opportunities() {
        $INDEX_CONTACT_STATUS__OPPORTUNITY = 1;

        return $this->hasMany(Contact::class, 'assigned_user_id')
            ->where(
                'status',
                ContactStatus::where(
                    'name',
                    config('seed_data.contact_status')[$INDEX_CONTACT_STATUS__OPPORTUNITY]
                )->first()->id
            );
    }

    /**
     *  get all customers assigned to user
     */
    public function customers() {
        $INDEX_CONTACT_STATUS__CUSTOMER = 2;

        return $this->hasMany(Contact::class, 'assigned_user_id')
            ->where(
                'status',
                ContactStatus::where(
                    'name',
                    config('seed_data.contact_status')[$INDEX_CONTACT_STATUS__CUSTOMER]
                )->first()->id
            );
    }

    /**
     * get all closed/archives customers assigned to user
     */
    public function archives() {
        $INDEX_CONTACT_STATUS__CLOSE = 3;

        return $this->hasMany(Contact::class, 'assigned_user_id')
            ->where(
                'status',
                ContactStatus::where(
                    'name',
                    config('seed_data.contact_status')[$INDEX_CONTACT_STATUS__CLOSE]
                )->first()->id
            );
    }

    /**
     * get all documents assigned to user
     */
    public function documents() {
        return $this->hasMany(Document::class, 'assigned_user_id');
    }

    /**
     * get all tasks assigned to user
     */
    public function tasks() {
        return $this->hasMany(Task::class, 'assigned_user_id');
    }

    /**
     * get all completed tasks assigned to user
     */
    public function completedTasks() {
        $INDEX_TASK_STATUSES__COMPLETED = 2;

        return $this->hasMany(Task::class, 'assigned_user_id')
            ->where(
                'status',
                TaskStatus::where(
                    'name',
                    config('seed_data.task_statuses')[$INDEX_TASK_STATUSES__COMPLETED]
                )->first()->id
            );
    }

    /**
     * get all pending tasks assigned to user
     */
    public function pendingTasks() {
        $INDEX_TASK_STATUSES__NOT_STARTED = 0;
        $INDEX_TASK_STATUSES__STARTED = 1;

        return $this->hasMany(Task::class, 'assigned_user_id')
            ->where(
                'status',
                TaskStatus::whereIn(
                    'name',
                    config('seed_data.task_statuses')[$INDEX_TASK_STATUSES__NOT_STARTED],
                    config('seed_data.task_statuses')[$INDEX_TASK_STATUSES__STARTED]
                )->first()->id
            );
    }
}
