<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ContactList extends Model
{
    use HasFactory;
    protected $table = "contact_list";

    protected $fillable = [
        "resourcesName",
        "etag",
        "contact_name",
        "total_sip",
        "family_org_name",
        "Pan_card",
        "investment",
        "total_investment",
        "kyc_status",
        "email",
        "phone",
        "Aadhar_card",
        "Rms",
        "gender",
        "birthdate",
        "relation",
        "address",
    ];
}
