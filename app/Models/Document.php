<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "document";
    protected $fillable = [
        "name",
        "file",
        "status",
        "type",
        "publish_date",
        "expiration_date",
        "created_by_id",
        "modified_by_id",
        "assigned_user_id"
    ];
    public $timestamps = true;

    /**
     * get created by user object
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /**
     * get modified by user object
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function modifiedBy() {
        return $this->belongsTo(User::class, 'modified_by_id');
    }

    /**
     * get assigned to user object
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assignedTo() {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    /**
     * get type object for this document
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getType() {
        return $this->belongsTo(DocumentType::class, 'type');
    }
}
