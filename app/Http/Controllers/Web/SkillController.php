<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSkillRequest;
use App\Http\Requests\UpdateSkillRequest;
use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SkillController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Skills/Index', [
            'skills' => Skill::orderBy('name')->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Skills/Create');
    }

    public function store(StoreSkillRequest $request): RedirectResponse
    {
        Skill::create($request->validated());

        return redirect()->route('skills.index')
            ->with('success', 'Habilidade criada com sucesso!');
    }

    public function edit(Skill $skill): Response
    {
        return Inertia::render('Skills/Edit', [
            'skill' => $skill,
        ]);
    }

    public function update(UpdateSkillRequest $request, Skill $skill): RedirectResponse
    {
        $skill->update($request->validated());

        return redirect()->route('skills.index')
            ->with('success', 'Habilidade atualizada com sucesso!');
    }

    public function destroy(Skill $skill): RedirectResponse
    {
        $skill->delete();

        return redirect()->route('skills.index')
            ->with('success', 'Habilidade excluída com sucesso!');
    }
}
