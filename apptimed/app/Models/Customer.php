<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ["user",
    "country",
    "contact_number",
    "company",
    "is_to_notify",
    "email_verified_at",
    "latest_site_search_id",
   ];


   public function userDetails()
   {
       return $this->hasOne(User::class , 'id' , 'user');
   }
}
