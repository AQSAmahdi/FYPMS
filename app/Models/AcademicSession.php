<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
 
class AcademicSession extends Model
{
    protected $fillable = ['name','start_year','end_year','is_active'];
}