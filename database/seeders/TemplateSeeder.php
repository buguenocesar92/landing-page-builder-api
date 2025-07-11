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

        // Template 2: E-commerce Luxury con efectos premium
        Template::create([
            'name' => 'E-commerce Luxury',
            'description' => 'Template elegante para productos premium con efectos visuales sofisticados',
            'preview_image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=800',
            'is_premium' => true,
            'is_active' => true,
            'content' => [
                'layout' => 'luxury-ecommerce',
                'animations' => [
                    'hero' => [
                        'type' => 'fadeInUp',
                        'delay' => 0.3,
                        'duration' => 1.0
                    ],
                    'features' => [
                        'type' => 'slideInLeft',
                        'delay' => 0.2,
                        'duration' => 0.8
                    ],
                    'cta' => [
                        'type' => 'bounceIn',
                        'delay' => 0.6,
                        'duration' => 1.2
                    ],
                    'parallax' => [
                        'enabled' => true,
                        'speed' => 0.5
                    ]
                ],
                'hero' => [
                    'title' => 'Artesanía de Lujo',
                    'subtitle' => 'Exclusiva Colección Premium',
                    'description' => 'Descubre piezas únicas creadas por maestros artesanos con más de 50 años de experiencia.',
                    'cta_text' => 'Explorar Colección',
                    'background' => [
                        'type' => 'image',
                        'url' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=1200',
                        'overlay' => 'rgba(0,0,0,0.4)'
                    ]
                ],
                'product_showcase' => [
                    'title' => 'Colección Exclusiva',
                    'subtitle' => 'Cada pieza cuenta una historia',
                    'products' => [
                        [
                            'name' => 'Reloj Artesanal Oro',
                            'price' => '€2,450',
                            'images' => [
                                'https://images.unsplash.com/photo-1594534475808-b18fc33b045e?w=600',
                                'https://images.unsplash.com/photo-1508057198894-247b23d32589?w=600'
                            ],
                            'description' => 'Reloj de oro de 18 quilates con mecanismo suizo',
                            'gallery_enabled' => true
                        ],
                        [
                            'name' => 'Anillo Diamante Vintage',
                            'price' => '€3,200',
                            'images' => [
                                'https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=600',
                                'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=600'
                            ],
                            'description' => 'Anillo vintage con diamante de 2 quilates',
                            'gallery_enabled' => true
                        ]
                    ]
                ],
                'video_section' => [
                    'title' => 'El Arte de la Perfección',
                    'subtitle' => 'Conoce nuestro proceso artesanal',
                    'video_url' => 'https://player.vimeo.com/video/example',
                    'thumbnail' => 'https://images.unsplash.com/photo-1565814329452-e1efa11c5b89?w=800',
                    'description' => 'Cada pieza requiere más de 40 horas de trabajo manual meticuloso.'
                ],
                'testimonials' => [
                    'title' => 'Testimonios de Clientes',
                    'items' => [
                        [
                            'name' => 'Isabella Rodriguez',
                            'position' => 'Coleccionista de Arte',
                            'avatar' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100',
                            'content' => 'Calidad excepcional y servicio impecable. Cada pieza es verdaderamente única.',
                            'rating' => 5
                        ]
                    ]
                ],
                'colors' => [
                    'primary' => '#1f2937',
                    'secondary' => '#111827',
                    'accent' => '#d4af37',
                    'text' => '#1f2937',
                    'background' => '#f9fafb'
                ],
                'fonts' => [
                    'heading' => 'Playfair Display',
                    'body' => 'Lato'
                ],
                'form' => [
                    'title' => 'Solicita Información Exclusiva',
                    'subtitle' => 'Accede a nuestra colección privada',
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
                        ]
                    ],
                    'cta_text' => 'Solicitar Información',
                    'privacy_text' => 'Tus datos están protegidos y no serán compartidos con terceros.'
                ]
            ]
        ]);

        // Template 3: Startup Tech con interacciones dinámicas
        Template::create([
            'name' => 'Startup Tech Interactive',
            'description' => 'Template dinámico para startups tech con elementos interactivos y modernos',
            'preview_image' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=800',
            'is_premium' => false,
            'is_active' => true,
            'content' => [
                'layout' => 'startup-tech',
                'animations' => [
                    'hero' => [
                        'type' => 'staggeredFadeIn',
                        'delay' => 0.2,
                        'duration' => 0.6
                    ],
                    'features' => [
                        'type' => 'slideInRight',
                        'delay' => 0.1,
                        'duration' => 0.7
                    ],
                    'cta' => [
                        'type' => 'bounceIn',
                        'delay' => 0.4,
                        'duration' => 1.0
                    ],
                    'typing_effect' => [
                        'enabled' => true,
                        'texts' => [
                            'Revolutionizing Tech',
                            'Building the Future',
                            'Innovation at Scale'
                        ],
                        'speed' => 100
                    ],
                    'counter' => [
                        'enabled' => true,
                        'duration' => 2.0
                    ]
                ],
                'hero' => [
                    'title' => 'Construyendo el Futuro',
                    'subtitle' => 'Tecnología que Transforma',
                    'description' => 'Somos una startup que desarrolla soluciones tecnológicas innovadoras para los desafíos del mañana.',
                    'cta_text' => 'Ver Demo',
                    'background' => [
                        'type' => 'particle_animation',
                        'particle_count' => 50,
                        'particle_colors' => ['#3b82f6', '#1e40af', '#fbbf24']
                    ]
                ],
                'features' => [
                    'title' => 'Características Revolucionarias',
                    'subtitle' => 'Tecnología de vanguardia',
                    'items' => [
                        [
                            'icon' => 'zap',
                            'title' => 'IA Generativa',
                            'description' => 'Algoritmos de machine learning de última generación'
                        ],
                        [
                            'icon' => 'shield',
                            'title' => 'Blockchain Security',
                            'description' => 'Seguridad descentralizada y transparente'
                        ],
                        [
                            'icon' => 'trending-up',
                            'title' => 'Escalabilidad Cloud',
                            'description' => 'Infraestructura que crece con tu negocio'
                        ]
                    ]
                ],
                'stats' => [
                    'title' => 'Números que Impresionan',
                    'items' => [
                        [
                            'number' => 50000,
                            'suffix' => '+',
                            'label' => 'Usuarios Activos'
                        ],
                        [
                            'number' => 99.9,
                            'suffix' => '%',
                            'label' => 'Uptime'
                        ],
                        [
                            'number' => 24,
                            'suffix' => '/7',
                            'label' => 'Soporte'
                        ]
                    ]
                ],
                'interactive_demo' => [
                    'title' => 'Prueba Nuestra Tecnología',
                    'subtitle' => 'Demo interactivo en vivo',
                    'embed_code' => '<div style="padding:56.25% 0 0 0;position:relative;"><iframe src="https://player.vimeo.com/video/example" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe></div>',
                    'cta_text' => 'Iniciar Demo'
                ],
                'colors' => [
                    'primary' => '#3b82f6',
                    'secondary' => '#1e40af',
                    'accent' => '#fbbf24',
                    'text' => '#1f2937',
                    'background' => '#ffffff'
                ],
                'fonts' => [
                    'heading' => 'Poppins',
                    'body' => 'Nunito'
                ],
                'form' => [
                    'title' => 'Únete a la Revolución Tech',
                    'subtitle' => 'Sé parte del cambio',
                    'fields' => [
                        [
                            'name' => 'name',
                            'type' => 'text',
                            'label' => 'Nombre',
                            'required' => true,
                            'icon' => 'user'
                        ],
                        [
                            'name' => 'email',
                            'type' => 'email',
                            'label' => 'Email',
                            'required' => true,
                            'icon' => 'mail'
                        ]
                    ],
                    'cta_text' => 'Comenzar Ahora',
                    'privacy_text' => 'Respetamos tu privacidad y nunca enviamos spam.'
                ]
            ]
        ]);
    }
}
