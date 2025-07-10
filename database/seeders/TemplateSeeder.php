<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Template;
use Illuminate\Support\Facades\DB;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Eliminar templates existentes de manera segura
        Template::whereNotNull('id')->delete();

        // Template 1: SaaS Moderno con Animaciones
        Template::create([
            'name' => 'SaaS Pro - Animado',
            'description' => 'Template profesional para productos SaaS con animaciones y elementos interactivos',
            'preview_image' => 'https://images.unsplash.com/photo-1551434678-e076c223a692?w=800',
            'is_premium' => true,
            'is_active' => true,
            'content' => [
                'layout' => 'modern-saas',
                'animations' => [
                    'hero' => [
                        'type' => 'fadeInUp',
                        'delay' => 0.2,
                        'duration' => 0.8
                    ],
                    'features' => [
                        'type' => 'staggeredFadeIn',
                        'delay' => 0.1,
                        'duration' => 0.6
                    ],
                    'cta' => [
                        'type' => 'bounceIn',
                        'delay' => 0.5,
                        'duration' => 1.0
                    ]
                ],
                'hero' => [
                    'title' => 'Automatiza tu Negocio con IA',
                    'subtitle' => 'La plataforma más avanzada para gestionar tu empresa',
                    'description' => 'Aumenta tu productividad en un 300% con nuestras herramientas de automatización basadas en inteligencia artificial.',
                    'cta_text' => 'Prueba Gratis por 14 Días',
                    'background' => [
                        'type' => 'gradient',
                        'colors' => ['#667eea', '#764ba2'],
                        'direction' => '45deg'
                    ],
                    'video' => [
                        'enabled' => true,
                        'autoplay' => true,
                        'loop' => true,
                        'url' => 'https://player.vimeo.com/video/example',
                        'thumbnail' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=1200'
                    ]
                ],
                'features' => [
                    'title' => '¿Por qué elegir nuestra plataforma?',
                    'subtitle' => 'Funcionalidades que harán crecer tu negocio',
                    'items' => [
                        [
                            'icon' => 'zap',
                            'title' => 'Automatización Inteligente',
                            'description' => 'IA que aprende de tus procesos y los optimiza automáticamente',
                            'image' => 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=400',
                            'animation' => 'slideInLeft'
                        ],
                        [
                            'icon' => 'shield',
                            'title' => 'Seguridad Empresarial',
                            'description' => 'Encriptación de nivel bancario y cumplimiento GDPR',
                            'image' => 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=400',
                            'animation' => 'slideInUp'
                        ],
                        [
                            'icon' => 'trending-up',
                            'title' => 'Analytics Avanzados',
                            'description' => 'Dashboards en tiempo real con métricas que importan',
                            'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=400',
                            'animation' => 'slideInRight'
                        ]
                    ]
                ],
                'testimonials' => [
                    'title' => 'Lo que dicen nuestros clientes',
                    'items' => [
                        [
                            'name' => 'María González',
                            'position' => 'CEO, TechStart',
                            'avatar' => 'https://images.unsplash.com/photo-1494790108755-2616b332b633?w=100',
                            'content' => 'Increíble plataforma. Hemos aumentado nuestra eficiencia un 250% en solo 3 meses.',
                            'rating' => 5,
                            'company_logo' => 'https://via.placeholder.com/120x40/3b82f6/ffffff?text=TechStart'
                        ],
                        [
                            'name' => 'Carlos Mendoza',
                            'position' => 'Director de Operaciones, InnovaCorp',
                            'avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100',
                            'content' => 'La mejor inversión que hemos hecho. ROI del 400% en el primer año.',
                            'rating' => 5,
                            'company_logo' => 'https://via.placeholder.com/120x40/10b981/ffffff?text=InnovaCorp'
                        ]
                    ]
                ],
                'pricing' => [
                    'title' => 'Planes que se adaptan a tu negocio',
                    'subtitle' => 'Comienza gratis, escala cuando necesites',
                    'plans' => [
                        [
                            'name' => 'Starter',
                            'price' => '29',
                            'period' => 'mes',
                            'features' => [
                                'Hasta 1,000 automatizaciones',
                                'Soporte por email',
                                'Integraciones básicas',
                                'Dashboard estándar'
                            ],
                            'cta_text' => 'Comenzar Gratis',
                            'popular' => false
                        ],
                        [
                            'name' => 'Professional',
                            'price' => '89',
                            'period' => 'mes',
                            'features' => [
                                'Automatizaciones ilimitadas',
                                'Soporte prioritario 24/7',
                                'Todas las integraciones',
                                'Analytics avanzados',
                                'API personalizada'
                            ],
                            'cta_text' => 'Empezar Ahora',
                            'popular' => true
                        ]
                    ]
                ],
                'social_proof' => [
                    'title' => 'Trusted by 10,000+ companies worldwide',
                    'logos' => [
                        'https://via.placeholder.com/150x60/1f2937/ffffff?text=Microsoft',
                        'https://via.placeholder.com/150x60/1f2937/ffffff?text=Google',
                        'https://via.placeholder.com/150x60/1f2937/ffffff?text=Amazon',
                        'https://via.placeholder.com/150x60/1f2937/ffffff?text=Netflix'
                    ]
                ],
                'colors' => [
                    'primary' => '#667eea',
                    'secondary' => '#764ba2',
                    'accent' => '#f093fb',
                    'text' => '#1f2937',
                    'background' => '#ffffff'
                ],
                'fonts' => [
                    'heading' => 'Inter',
                    'body' => 'Inter'
                ],
                'form' => [
                    'title' => '¡Comienza tu prueba gratuita hoy!',
                    'subtitle' => 'Sin tarjeta de crédito requerida',
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
                            'label' => 'Email empresarial',
                            'required' => true,
                            'icon' => 'mail'
                        ],
                        [
                            'name' => 'company',
                            'type' => 'text',
                            'label' => 'Empresa',
                            'required' => true,
                            'icon' => 'building'
                        ],
                        [
                            'name' => 'phone',
                            'type' => 'tel',
                            'label' => 'Teléfono',
                            'required' => false,
                            'icon' => 'phone'
                        ]
                    ],
                    'cta_text' => 'Comenzar Prueba Gratuita',
                    'privacy_text' => 'Al registrarte, aceptas nuestros términos y política de privacidad.'
                ]
            ]
        ]);

        // Template 2: E-commerce Premium
        Template::create([
            'name' => 'E-commerce Luxury',
            'description' => 'Template elegante para productos premium con galería de imágenes y efectos parallax',
            'preview_image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=800',
            'is_premium' => true,
            'is_active' => true,
            'content' => [
                'layout' => 'ecommerce-luxury',
                'animations' => [
                    'parallax' => [
                        'enabled' => true,
                        'speed' => 0.5
                    ],
                    'image_gallery' => [
                        'type' => 'lightbox',
                        'transition' => 'fade'
                    ]
                ],
                'hero' => [
                    'title' => 'Colección Exclusiva 2025',
                    'subtitle' => 'Elegancia Redefinida',
                    'description' => 'Descubre nuestra línea más exclusiva, diseñada para quienes buscan lo extraordinario.',
                    'cta_text' => 'Explorar Colección',
                    'background' => [
                        'type' => 'image',
                        'url' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1920',
                        'overlay' => 'rgba(0,0,0,0.4)',
                        'parallax' => true
                    ]
                ],
                'product_showcase' => [
                    'title' => 'Productos Destacados',
                    'items' => [
                        [
                            'name' => 'Reloj Platinum Elite',
                            'price' => '2,999',
                            'currency' => 'USD',
                            'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600',
                            'gallery' => [
                                'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600',
                                'https://images.unsplash.com/photo-1594534475808-b18fc33b045e?w=600',
                                'https://images.unsplash.com/photo-1548169874-53e85f753f1e?w=600'
                            ],
                            'description' => 'Reloj suizo de lujo con movimiento automático y caja de platino.'
                        ],
                        [
                            'name' => 'Anillo Diamante Imperial',
                            'price' => '5,499',
                            'currency' => 'USD',
                            'image' => 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=600',
                            'gallery' => [
                                'https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=600',
                                'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=600'
                            ],
                            'description' => 'Anillo de compromiso con diamante de 2 quilates y certificación GIA.'
                        ]
                    ]
                ],
                'video_section' => [
                    'title' => 'Artesanía Excepcional',
                    'subtitle' => 'Cada pieza es única',
                    'video_url' => 'https://player.vimeo.com/video/example',
                    'thumbnail' => 'https://images.unsplash.com/photo-1565372722299-73fa96c3a3ee?w=1200'
                ],
                'colors' => [
                    'primary' => '#d4af37',
                    'secondary' => '#1f1f1f',
                    'accent' => '#ffffff',
                    'text' => '#333333',
                    'background' => '#fafafa'
                ],
                'fonts' => [
                    'heading' => 'Playfair Display',
                    'body' => 'Lato'
                ]
            ]
        ]);

        // Template 3: Startup Tech con Interacciones
        Template::create([
            'name' => 'Startup Tech Interactive',
            'description' => 'Template dinámico para startups tech con elementos interactivos y micro-animaciones',
            'preview_image' => 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?w=800',
            'is_premium' => false,
            'is_active' => true,
            'content' => [
                'layout' => 'startup-tech',
                'animations' => [
                    'counter' => [
                        'enabled' => true,
                        'duration' => 2.0
                    ],
                    'typing_effect' => [
                        'enabled' => true,
                        'texts' => [
                            'Desarrollamos Apps',
                            'Creamos Soluciones',
                            'Innovamos Procesos'
                        ],
                        'speed' => 100
                    ]
                ],
                'hero' => [
                    'title' => 'Transformamos Ideas en',
                    'typing_text' => 'Realidad Digital',
                    'description' => 'Somos un equipo de desarrolladores apasionados que convertimos tus ideas en productos digitales exitosos.',
                    'cta_text' => 'Conversemos',
                    'background' => [
                        'type' => 'particle_animation',
                        'color' => '#3b82f6',
                        'count' => 50
                    ]
                ],
                'stats' => [
                    'title' => 'Resultados que hablan por sí solos',
                    'items' => [
                        [
                            'number' => 150,
                            'suffix' => '+',
                            'label' => 'Proyectos Completados',
                            'icon' => 'check-circle'
                        ],
                        [
                            'number' => 98,
                            'suffix' => '%',
                            'label' => 'Clientes Satisfechos',
                            'icon' => 'heart'
                        ],
                        [
                            'number' => 5,
                            'suffix' => '',
                            'label' => 'Años de Experiencia',
                            'icon' => 'award'
                        ]
                    ]
                ],
                'interactive_demo' => [
                    'title' => 'Probá nuestra plataforma',
                    'subtitle' => 'Demo interactivo en tiempo real',
                    'enabled' => true,
                    'iframe_url' => 'https://demo.example.com/interactive'
                ],
                'colors' => [
                    'primary' => '#3b82f6',
                    'secondary' => '#1e40af',
                    'accent' => '#fbbf24',
                    'text' => '#1f2937',
                    'background' => '#ffffff'
                ]
            ]
        ]);
    }
}
