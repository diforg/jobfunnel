<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = $this->seedUser();

        $this->seedSkills($user);
        $this->seedJobs($user);
    }

    private function seedUser(): User
    {
        return User::updateOrCreate(
            ['email' => 'user@user.com.br'],
            [
                'name' => 'User',
                'password' => 'senha123',
            ]
        );
    }

    private function seedSkills(User $user): void
    {
        $skills = [
            ['name' => 'PHP', 'category' => 'Backend', 'proficiency' => 'expert'],
            ['name' => 'Laravel', 'category' => 'Backend', 'proficiency' => 'expert'],
            ['name' => 'Python', 'category' => 'Backend', 'proficiency' => 'intermediate'],
            ['name' => 'Node.js', 'category' => 'Backend', 'proficiency' => 'intermediate'],
            ['name' => 'Go', 'category' => 'Backend', 'proficiency' => 'beginner'],
            ['name' => 'Vue.js', 'category' => 'Frontend', 'proficiency' => 'expert'],
            ['name' => 'React', 'category' => 'Frontend', 'proficiency' => 'intermediate'],
            ['name' => 'TypeScript', 'category' => 'Frontend', 'proficiency' => 'intermediate'],
            ['name' => 'Tailwind CSS', 'category' => 'Frontend', 'proficiency' => 'expert'],
            ['name' => 'PostgreSQL', 'category' => 'Database', 'proficiency' => 'expert'],
            ['name' => 'MySQL', 'category' => 'Database', 'proficiency' => 'intermediate'],
            ['name' => 'Redis', 'category' => 'Database', 'proficiency' => 'intermediate'],
            ['name' => 'Docker', 'category' => 'DevOps', 'proficiency' => 'intermediate'],
            ['name' => 'AWS', 'category' => 'DevOps', 'proficiency' => 'beginner'],
            ['name' => 'Git', 'category' => 'DevOps', 'proficiency' => 'expert'],
        ];

        foreach ($skills as $skill) {
            $user->skills()->updateOrCreate(
                ['name' => $skill['name']],
                $skill,
            );
        }
    }

    private function seedJobs(User $user): void
    {
        $jobs = [
            [
                'job' => [
                    'title' => 'Desenvolvedor PHP Sênior',
                    'company' => 'Nubank',
                    'source_name' => 'LinkedIn',
                    'source_url' => 'https://linkedin.com/jobs/1',
                    'apply_url' => 'https://nubank.com.br/careers/1',
                    'description' => 'Vaga para desenvolvedor PHP Sênior com experiência em microserviços e APIs REST.',
                    'salary_expectation' => 18000.00,
                    'status' => 'hired',
                    'notes' => 'Empresa referência no mercado fintech.',
                    'applied_at' => '2025-01-10',
                ],
                'contacts' => [
                    ['name' => 'Ana Souza', 'role' => 'Tech Recruiter', 'email' => 'ana.souza@nubank.com.br', 'phone' => '11987654321', 'linkedin_url' => 'https://linkedin.com/in/anasouza', 'notes' => 'Muito atenciosa durante o processo.'],
                ],
                'skills' => [
                    ['skill_name' => 'PHP', 'level' => 'required', 'matched' => true],
                    ['skill_name' => 'Laravel', 'level' => 'required', 'matched' => true],
                    ['skill_name' => 'PostgreSQL', 'level' => 'required', 'matched' => true],
                    ['skill_name' => 'Docker', 'level' => 'nice_to_have', 'matched' => true],
                ],
                'timelines' => [
                    ['stage' => 'identified', 'happened_at' => '2025-01-08', 'notes' => 'Vaga encontrada no LinkedIn.'],
                    ['stage' => 'applied', 'happened_at' => '2025-01-10', 'notes' => 'Currículo enviado pelo portal.'],
                    ['stage' => 'recruiter_interview', 'happened_at' => '2025-01-15', 'notes' => 'Entrevista de 30 min com a Ana.'],
                    ['stage' => 'technical_interview', 'happened_at' => '2025-01-22', 'notes' => 'Entrevista técnica com o time de backend.'],
                    ['stage' => 'offer', 'happened_at' => '2025-01-30', 'notes' => 'Oferta recebida: R$18.000 + benefícios.'],
                    ['stage' => 'hired', 'happened_at' => '2025-02-03', 'notes' => 'Oferta aceita! Início em 15/02.'],
                ],
            ],
            [
                'job' => [
                    'title' => 'Engenheiro de Software Backend',
                    'company' => 'PicPay',
                    'source_name' => 'Gupy',
                    'source_url' => 'https://gupy.io/jobs/2',
                    'apply_url' => 'https://picpay.com/careers/2',
                    'description' => 'Desenvolvedor backend com foco em alta performance e escalabilidade.',
                    'salary_expectation' => 16000.00,
                    'status' => 'rejected',
                    'notes' => 'Processo muito demorado.',
                    'applied_at' => '2025-01-12',
                ],
                'contacts' => [
                    ['name' => 'Carlos Mendes', 'role' => 'Engineering Manager', 'email' => 'carlos.mendes@picpay.com', 'phone' => '11976543210', 'linkedin_url' => null, 'notes' => 'Contactado via LinkedIn direct message.'],
                ],
                'skills' => [
                    ['skill_name' => 'Node.js', 'level' => 'required', 'matched' => true],
                    ['skill_name' => 'Python', 'level' => 'nice_to_have', 'matched' => true],
                    ['skill_name' => 'Redis', 'level' => 'required', 'matched' => true],
                    ['skill_name' => 'AWS', 'level' => 'required', 'matched' => false],
                ],
                'timelines' => [
                    ['stage' => 'identified', 'happened_at' => '2025-01-11', 'notes' => 'Vaga encontrada no Gupy.'],
                    ['stage' => 'applied', 'happened_at' => '2025-01-12', 'notes' => null],
                    ['stage' => 'recruiter_interview', 'happened_at' => '2025-01-20', 'notes' => 'Entrevista rápida de triagem.'],
                    ['stage' => 'rejected', 'happened_at' => '2025-02-05', 'notes' => 'Reprovado por falta de experiência com AWS.'],
                ],
            ],
            [
                'job' => [
                    'title' => 'Full Stack Developer (Vue + Laravel)',
                    'company' => 'Conta Azul',
                    'source_name' => 'LinkedIn',
                    'source_url' => 'https://linkedin.com/jobs/3',
                    'apply_url' => 'https://contaazul.com/vagas/3',
                    'description' => 'Vaga para full stack com foco em produtos SaaS para PMEs.',
                    'salary_expectation' => 14000.00,
                    'status' => 'technical_interview',
                    'notes' => 'Stack alinhada com minha experiência.',
                    'applied_at' => '2025-02-01',
                ],
                'contacts' => [
                    ['name' => 'Fernanda Lima', 'role' => 'HR Business Partner', 'email' => 'fernanda@contaazul.com', 'phone' => null, 'linkedin_url' => 'https://linkedin.com/in/fernandalima', 'notes' => null],
                ],
                'skills' => [
                    ['skill_name' => 'Vue.js', 'level' => 'required', 'matched' => true],
                    ['skill_name' => 'Laravel', 'level' => 'required', 'matched' => true],
                    ['skill_name' => 'PostgreSQL', 'level' => 'nice_to_have', 'matched' => true],
                    ['skill_name' => 'TypeScript', 'level' => 'nice_to_have', 'matched' => true],
                ],
                'timelines' => [
                    ['stage' => 'identified', 'happened_at' => '2025-01-30', 'notes' => 'Indicação de um amigo.'],
                    ['stage' => 'applied', 'happened_at' => '2025-02-01', 'notes' => null],
                    ['stage' => 'recruiter_interview', 'happened_at' => '2025-02-10', 'notes' => 'Call de 45 min com o RH.'],
                    ['stage' => 'technical_interview', 'happened_at' => '2025-02-20', 'notes' => 'Entrevista técnica agendada com o CTO.'],
                ],
            ],
            [
                'job' => [
                    'title' => 'Software Engineer - Pleno',
                    'company' => 'Totvs',
                    'source_name' => 'Indeed',
                    'source_url' => 'https://indeed.com/jobs/4',
                    'apply_url' => 'https://totvs.com/carreiras/4',
                    'description' => 'Desenvolvimento de sistemas ERP em ambiente corporativo.',
                    'salary_expectation' => 12000.00,
                    'status' => 'ghosted',
                    'notes' => 'Sem retorno após entrevista inicial.',
                    'applied_at' => '2025-01-05',
                ],
                'contacts' => [],
                'skills' => [
                    ['skill_name' => 'PHP', 'level' => 'required', 'matched' => true],
                    ['skill_name' => 'MySQL', 'level' => 'required', 'matched' => true],
                    ['skill_name' => 'Docker', 'level' => 'nice_to_have', 'matched' => true],
                ],
                'timelines' => [
                    ['stage' => 'identified', 'happened_at' => '2025-01-04', 'notes' => null],
                    ['stage' => 'applied', 'happened_at' => '2025-01-05', 'notes' => null],
                    ['stage' => 'recruiter_interview', 'happened_at' => '2025-01-13', 'notes' => 'Entrevista básica de triagem.'],
                    ['stage' => 'ghosted', 'happened_at' => '2025-02-01', 'notes' => 'Nunca mais recebi contato após a entrevista.'],
                ],
            ],
            [
                'job' => [
                    'title' => 'Backend Engineer (Python/FastAPI)',
                    'company' => 'Loft',
                    'source_name' => 'LinkedIn',
                    'source_url' => 'https://linkedin.com/jobs/5',
                    'apply_url' => 'https://loft.com.br/vagas/5',
                    'description' => 'Engenharia de dados e APIs com Python e FastAPI.',
                    'salary_expectation' => 15000.00,
                    'status' => 'applied',
                    'notes' => 'Tenho Python intermediário, vale tentar.',
                    'applied_at' => '2025-02-15',
                ],
                'contacts' => [
                    ['name' => 'Rodrigo Costa', 'role' => 'Tech Lead', 'email' => 'rodrigo@loft.com.br', 'phone' => null, 'linkedin_url' => null, 'notes' => 'Mencionado no post da vaga.'],
                ],
                'skills' => [
                    ['skill_name' => 'Python', 'level' => 'required', 'matched' => true],
                    ['skill_name' => 'PostgreSQL', 'level' => 'required', 'matched' => true],
                    ['skill_name' => 'Redis', 'level' => 'nice_to_have', 'matched' => true],
                    ['skill_name' => 'AWS', 'level' => 'nice_to_have', 'matched' => false],
                ],
                'timelines' => [
                    ['stage' => 'identified', 'happened_at' => '2025-02-14', 'notes' => null],
                    ['stage' => 'applied', 'happened_at' => '2025-02-15', 'notes' => 'Currículo enviado com carta de apresentação personalizada.'],
                ],
            ],
            [
                'job' => [
                    'title' => 'Desenvolvedor Laravel Sênior',
                    'company' => 'Vindi',
                    'source_name' => 'Programathor',
                    'source_url' => 'https://programathor.com.br/jobs/6',
                    'apply_url' => 'https://vindi.com.br/vagas/6',
                    'description' => 'Plataforma de pagamentos recorrentes, foco em qualidade de código e testes.',
                    'salary_expectation' => 17000.00,
                    'status' => 'technical_test',
                    'notes' => 'Empresa menor, mas produto muito interessante.',
                    'applied_at' => '2025-02-05',
                ],
                'contacts' => [
                    ['name' => 'Juliana Rocha', 'role' => 'Recruiter', 'email' => 'juliana@vindi.com.br', 'phone' => '11912345678', 'linkedin_url' => 'https://linkedin.com/in/juliana', 'notes' => null],
                ],
                'skills' => [
                    ['skill_name' => 'Laravel', 'level' => 'required', 'matched' => true],
                    ['skill_name' => 'PHP', 'level' => 'required', 'matched' => true],
                    ['skill_name' => 'PostgreSQL', 'level' => 'required', 'matched' => true],
                    ['skill_name' => 'Docker', 'level' => 'required', 'matched' => true],
                    ['skill_name' => 'Redis', 'level' => 'nice_to_have', 'matched' => true],
                ],
                'timelines' => [
                    ['stage' => 'identified', 'happened_at' => '2025-02-04', 'notes' => null],
                    ['stage' => 'applied', 'happened_at' => '2025-02-05', 'notes' => null],
                    ['stage' => 'recruiter_interview', 'happened_at' => '2025-02-12', 'notes' => 'Ótima conversa com a Juliana.'],
                    ['stage' => 'technical_test', 'happened_at' => '2025-02-19', 'notes' => 'Teste técnico recebido: API REST + testes em Pest.'],
                ],
            ],
            [
                'job' => [
                    'title' => 'Engenheiro de Software Sênior - Remote',
                    'company' => 'Hotmart',
                    'source_name' => 'LinkedIn',
                    'source_url' => 'https://linkedin.com/jobs/7',
                    'apply_url' => 'https://hotmart.com/vagas/7',
                    'description' => 'Plataforma de cursos online, foco em escalabilidade e experiência do usuário.',
                    'salary_expectation' => 20000.00,
                    'status' => 'identified',
                    'notes' => 'Salário acima da expectativa. Remoto 100%.',
                    'applied_at' => null,
                ],
                'contacts' => [],
                'skills' => [
                    ['skill_name' => 'PHP', 'level' => 'required', 'matched' => true],
                    ['skill_name' => 'Vue.js', 'level' => 'required', 'matched' => true],
                    ['skill_name' => 'AWS', 'level' => 'required', 'matched' => false],
                    ['skill_name' => 'Docker', 'level' => 'required', 'matched' => true],
                ],
                'timelines' => [
                    ['stage' => 'identified', 'happened_at' => '2025-02-20', 'notes' => 'Encontrada via busca ativa no LinkedIn.'],
                ],
            ],
            [
                'job' => [
                    'title' => 'Desenvolvedor Full Stack React/Node',
                    'company' => 'QuintoAndar',
                    'source_name' => 'Glassdoor',
                    'source_url' => 'https://glassdoor.com/jobs/8',
                    'apply_url' => 'https://quintoandar.com.br/vagas/8',
                    'description' => 'PropTech líder, desenvolvimento de plataforma de aluguel.',
                    'salary_expectation' => 16500.00,
                    'status' => 'recruiter_interview',
                    'notes' => 'Stack diferente da habitual (React/Node), mas empresa incrível.',
                    'applied_at' => '2025-02-18',
                ],
                'contacts' => [
                    ['name' => 'Beatriz Santos', 'role' => 'People & Culture', 'email' => 'beatriz@quintoandar.com', 'phone' => null, 'linkedin_url' => 'https://linkedin.com/in/beatriz', 'notes' => 'Muito proativa no processo.'],
                ],
                'skills' => [
                    ['skill_name' => 'React', 'level' => 'required', 'matched' => true],
                    ['skill_name' => 'Node.js', 'level' => 'required', 'matched' => true],
                    ['skill_name' => 'TypeScript', 'level' => 'required', 'matched' => true],
                    ['skill_name' => 'PostgreSQL', 'level' => 'nice_to_have', 'matched' => true],
                ],
                'timelines' => [
                    ['stage' => 'identified', 'happened_at' => '2025-02-17', 'notes' => null],
                    ['stage' => 'applied', 'happened_at' => '2025-02-18', 'notes' => null],
                    ['stage' => 'recruiter_interview', 'happened_at' => '2025-02-25', 'notes' => 'Entrevista marcada para 25/02 às 14h.'],
                ],
            ],
            [
                'job' => [
                    'title' => 'Backend Developer PHP - PJ',
                    'company' => 'Creditas',
                    'source_name' => 'Trampos',
                    'source_url' => 'https://trampos.co/jobs/9',
                    'apply_url' => 'https://creditas.com/vagas/9',
                    'description' => 'Fintech de crédito com garantia, squad de onboarding.',
                    'salary_expectation' => 15000.00,
                    'status' => 'offer',
                    'notes' => 'PJ, valor bruto ainda precisa de cálculo.',
                    'applied_at' => '2025-01-20',
                ],
                'contacts' => [
                    ['name' => 'Marcos Oliveira', 'role' => 'Engineering Manager', 'email' => 'marcos@creditas.com', 'phone' => '11999887766', 'linkedin_url' => null, 'notes' => 'Ótimo líder, equipe parece madura.'],
                    ['name' => 'Paula Vieira', 'role' => 'Recruiter', 'email' => 'paula@creditas.com', 'phone' => null, 'linkedin_url' => 'https://linkedin.com/in/paulavieira', 'notes' => null],
                ],
                'skills' => [
                    ['skill_name' => 'PHP', 'level' => 'required', 'matched' => true],
                    ['skill_name' => 'Laravel', 'level' => 'required', 'matched' => true],
                    ['skill_name' => 'MySQL', 'level' => 'required', 'matched' => true],
                    ['skill_name' => 'Docker', 'level' => 'nice_to_have', 'matched' => true],
                    ['skill_name' => 'Redis', 'level' => 'nice_to_have', 'matched' => true],
                ],
                'timelines' => [
                    ['stage' => 'identified', 'happened_at' => '2025-01-19', 'notes' => null],
                    ['stage' => 'applied', 'happened_at' => '2025-01-20', 'notes' => null],
                    ['stage' => 'recruiter_interview', 'happened_at' => '2025-01-27', 'notes' => 'Call de triagem com a Paula.'],
                    ['stage' => 'technical_interview', 'happened_at' => '2025-02-03', 'notes' => 'Entrevista com o Marcos e mais 2 devs do squad.'],
                    ['stage' => 'technical_test', 'happened_at' => '2025-02-07', 'notes' => 'Teste enviado e devolvido em 3 dias.'],
                    ['stage' => 'offer', 'happened_at' => '2025-02-17', 'notes' => 'Oferta: R$15.000 PJ. Aguardando negociação.'],
                ],
            ],
            [
                'job' => [
                    'title' => 'Tech Lead Backend',
                    'company' => 'Stone Pagamentos',
                    'source_name' => 'LinkedIn',
                    'source_url' => 'https://linkedin.com/jobs/10',
                    'apply_url' => 'https://stone.com.br/vagas/10',
                    'description' => 'Liderança técnica de squad de pagamentos instantâneos (PIX).',
                    'salary_expectation' => 22000.00,
                    'status' => 'identified',
                    'notes' => 'Cargo de liderança, boa oportunidade de crescimento.',
                    'applied_at' => null,
                ],
                'contacts' => [],
                'skills' => [
                    ['skill_name' => 'Go', 'level' => 'required', 'matched' => false],
                    ['skill_name' => 'PHP', 'level' => 'nice_to_have', 'matched' => true],
                    ['skill_name' => 'PostgreSQL', 'level' => 'required', 'matched' => true],
                    ['skill_name' => 'Redis', 'level' => 'required', 'matched' => true],
                    ['skill_name' => 'Docker', 'level' => 'required', 'matched' => true],
                    ['skill_name' => 'AWS', 'level' => 'required', 'matched' => false],
                ],
                'timelines' => [
                    ['stage' => 'identified', 'happened_at' => '2025-02-22', 'notes' => 'Vaga de liderança com Go — preciso estudar mais.'],
                ],
            ],
            [
                'job' => [
                    'title' => 'Desenvolvedor PHP Pleno',
                    'company' => 'Resultados Digitais',
                    'source_name' => 'LinkedIn',
                    'source_url' => 'https://linkedin.com/jobs/11',
                    'apply_url' => 'https://rd.com.br/vagas/11',
                    'description' => 'Desenvolvimento da plataforma RD Station, produto de marketing digital.',
                    'salary_expectation' => 13000.00,
                    'status' => 'applied',
                    'notes' => 'Produto maduro, empresa sólida em Florianópolis (remoto).',
                    'applied_at' => '2025-02-22',
                ],
                'contacts' => [
                    ['name' => 'Thiago Martins', 'role' => 'CTO', 'email' => null, 'phone' => null, 'linkedin_url' => 'https://linkedin.com/in/thiagomartins', 'notes' => 'Referência de alguém da comunidade PHP.'],
                ],
                'skills' => [
                    ['skill_name' => 'PHP', 'level' => 'required', 'matched' => true],
                    ['skill_name' => 'Laravel', 'level' => 'nice_to_have', 'matched' => true],
                    ['skill_name' => 'Vue.js', 'level' => 'required', 'matched' => true],
                    ['skill_name' => 'MySQL', 'level' => 'required', 'matched' => true],
                ],
                'timelines' => [
                    ['stage' => 'identified', 'happened_at' => '2025-02-21', 'notes' => null],
                    ['stage' => 'applied', 'happened_at' => '2025-02-22', 'notes' => 'Currículo com portfólio enviado.'],
                ],
            ],
        ];

        foreach ($jobs as $jobData) {
            $job = $user->jobs()->updateOrCreate(
                [
                    'title' => $jobData['job']['title'],
                    'company' => $jobData['job']['company'],
                ],
                $jobData['job'],
            );

            $job->contacts()->delete();
            $job->skills()->delete();
            $job->timelines()->delete();

            foreach ($jobData['contacts'] as $contact) {
                $job->contacts()->create($contact);
            }

            foreach ($jobData['skills'] as $skill) {
                $job->skills()->create($skill);
            }

            foreach ($jobData['timelines'] as $timeline) {
                $job->timelines()->create($timeline);
            }
        }
    }
}
