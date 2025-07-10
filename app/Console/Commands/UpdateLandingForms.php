<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Landing;

class UpdateLandingForms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'landings:update-forms {--dry-run : Show what would be updated without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza todas las landing pages existentes con campos de formulario básicos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        $this->info('Buscando landing pages sin campos de formulario...');
        
        $landings = Landing::all();
        $updated = 0;
        
        foreach ($landings as $landing) {
            $content = $landing->content;
            
            // Verificar si ya tiene campos de formulario
            if (!isset($content['form']) || !isset($content['form']['fields']) || empty($content['form']['fields'])) {
                
                if ($dryRun) {
                    $this->line("Se actualizaría: ID {$landing->id} - {$landing->title}");
                } else {
                    // Agregar formulario básico
                    $content['form'] = [
                        'title' => '¡Contáctanos!',
                        'subtitle' => 'Déjanos tus datos y te contactaremos pronto',
                        'fields' => [
                            [
                                'name' => 'name',
                                'type' => 'text',
                                'label' => 'Nombre completo',
                                'required' => true,
                                'icon' => 'user'
                            ],
                            [
                                'name' => 'email',
                                'type' => 'email',
                                'label' => 'Email',
                                'required' => true,
                                'icon' => 'mail'
                            ],
                            [
                                'name' => 'phone',
                                'type' => 'tel',
                                'label' => 'Teléfono',
                                'required' => false,
                                'icon' => 'phone'
                            ],
                            [
                                'name' => 'message',
                                'type' => 'textarea',
                                'label' => 'Mensaje',
                                'required' => false,
                                'icon' => 'message-square'
                            ]
                        ],
                        'cta_text' => 'Enviar',
                        'privacy_text' => 'Al enviar este formulario, aceptas nuestros términos y condiciones.'
                    ];
                    
                    $landing->update(['content' => $content]);
                    $this->info("✓ Actualizado: ID {$landing->id} - {$landing->title}");
                }
                
                $updated++;
            }
        }
        
        if ($dryRun) {
            $this->info("\n📊 Resumen (modo dry-run):");
            $this->line("Landing pages totales: {$landings->count()}");
            $this->line("Se actualizarían: {$updated}");
            $this->line("\nEjecuta sin --dry-run para aplicar los cambios.");
        } else {
            $this->info("\n📊 Resumen:");
            $this->line("Landing pages totales: {$landings->count()}");
            $this->line("Actualizadas: {$updated}");
            $this->info('¡Actualización completada!');
        }
        
        return 0;
    }
}
