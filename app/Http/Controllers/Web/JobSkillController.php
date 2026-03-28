<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobSkill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class JobSkillController extends Controller
{
    public function store(Request $request, Job $job): RedirectResponse
    {
        $request->validate([
            'skill_name' => 'required|string|max:255',
            'level' => 'required|in:required,nice_to_have',
            'matched' => 'boolean',
        ]);

        $job->skills()->create([
            'skill_name' => $request->skill_name,
            'level' => $request->level,
            'matched' => $request->boolean('matched'),
        ]);

        return back()->with('success', 'Habilidade adicionada!');
    }

    public function update(Request $request, Job $job, JobSkill $skill): RedirectResponse
    {
        $request->validate(['matched' => 'required|boolean']);

        $skill->update(['matched' => $request->boolean('matched')]);

        return back()->with('success', 'Habilidade atualizada!');
    }

    public function destroy(Job $job, JobSkill $skill): RedirectResponse
    {
        $skill->delete();

        return back()->with('success', 'Habilidade removida!');
    }
}
