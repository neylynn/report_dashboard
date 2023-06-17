<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortalOneCompanyList extends Model
{
    use HasFactory;

    protected $connection = "mysql_portal_one";

    protected $table = "companies";
}
