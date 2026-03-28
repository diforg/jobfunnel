<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobContact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class JobContactController extends Controller
{
    public function store(Request $request, Job $job): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'linkedin_url' => 'nullable|url|max:500',
            'notes' => 'nullable|string',
        ]);

        $job->contacts()->create($request->only(['name', 'role', 'email', 'phone', 'linkedin_url', 'notes']));

        return back()->with('success', 'Contato adicionado!');
    }

    public function destroy(Job $job, JobContact $contact): RedirectResponse
    {
        $contact->delete();

        return back()->with('success', 'Contato removido!');
    }
}
