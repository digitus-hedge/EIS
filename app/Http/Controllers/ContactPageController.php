<?php

namespace App\Http\Controllers;

use App\Models\Contact;

class ContactPageController extends Controller
{
    public function index()
    {
        $contact = Contact::first();

        return view('web.contact', compact('contact'));
    }
}