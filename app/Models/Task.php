<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * @var string[]
     */
    protected $fillable = ['name', 'complete'];

    /**
     * @return bool
     */
    public function getCompleteStatus(): bool
    {
        return $this->complete;
    }

    /**
     * @return void
     */
    public function setComplete(): void
    {
        $this->complete = true;
        $this->save();
    }

    /**
     * @return void
     */
    public function unSetComplete(): void
    {
        $this->complete = false;
        $this->save();
    }

    /**
     * Get the user associated with this task
     * @return HasOne
     */
    function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
