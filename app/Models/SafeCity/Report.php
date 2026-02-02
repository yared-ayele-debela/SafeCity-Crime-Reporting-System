<?php

namespace App\Models\SafeCity;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

     protected $fillable = [
        'user_id', 'title', 'description', 'category_id', 'location_text',
        'latitude', 'longitude','country','state','city','sub_city','street', 'status', 'is_anonymous', 'evidence_file', 'submitted_at','tracking_code'
    ];

    // Relationships

    public function user()
    {
        return $this->belongsTo(related: User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(ReportComment::class);
    }

    public function assignment()
    {
        return $this->hasOne(Assignment::class);
    }

    // app/Models/Report.php

public function files()
{
    return $this->hasMany(related: ReportFile::class);
}

}
