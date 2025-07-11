<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Template;

class CustomTemplateSeeder extends Seeder
{
    /**
     * Ejemplo: Crear template completamente personalizado
     */
    public function run(): void
    {
        // Template personalizado para consultoría
        Template::create([
            'name' => 'Consultoría Profesional',
            'description' => 'Template elegante para consultores y profesionales',
            'preview_image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800',
            'is_premium' => false,
            'is_active' => true,
            'content' => [
                'layout' => 'consulting',
                
                // Colores personalizados
                'colors' => [
                    'primary' => '#2c3e50',    // Azul oscuro profesional
                    'secondary' => '#34495e',  // Gris azulado
                    'accent' => '#e74c3c',     // Rojo de acento
                    'text' => '#2c3e50',
                    'background' => '#ffffff'
                ],
                
                // Fuentes elegantes
                'fonts' => [
                    'heading' => 'Playfair Display',  // Serif elegante
                    'body' => 'Source Sans Pro'       // Sans-serif legible
                ],
                
                // Animaciones suaves
                'animations' => [
                    'hero' => ['type' => 'fadeInUp', 'duration' => 1.0, 'delay' => 0.3],
                    'features' => ['type' => 'slideInLeft', 'duration' => 0.8, 'delay' => 0.2],
                    'typing_effect' => ['enabled' => true, 'texts' => [
                        'Consultoría Estratégica',
                        'Resultados Garantizados',
                        'Experiencia Comprobada'
                    ]],
                    'counter' => ['enabled' => true, 'duration' => 2.5]
                ],
                
                // Sección Hero personalizada
                'hero' => [
                    'title' => 'Transformamos Tu Negocio',
                    'subtitle' => 'Consultoría Estratégica de Alto Impacto',
                    'description' => 'Con más de 15 años de experiencia, ayudamos a empresas a alcanzar su máximo potencial a través de estrategias personalizadas y resultados medibles.',
                    'cta_text' => 'Agenda Tu Consulta Gratuita',
                    'background' => [
                        'type' => 'image',
                        'url' => 'https://images.unsplash.com/photo-1521737711867-e3b97375f902?w=1920',
                        'overlay' => 'rgba(44, 62, 80, 0.7)'
                    ]
                ],
                
                // Features únicas para consultoría
                'features' => [
                    'title' => 'Nuestro Método Probado',
                    'subtitle' => 'Resultados tangibles en 90 días',
                    'items' => [
                        [
                            'icon' => 'target',
                            'title' => 'Diagnóstico Integral',
                            'description' => 'Análisis profundo de tu situación actual con herramientas de última generación',
                            'image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=400'
                        ],
                        [
                            'icon' => 'trending-up',
                            'title' => 'Estrategia Personalizada',
                            'description' => 'Plan de acción específico diseñado para tus objetivos y recursos',
                            'image' => 'https://images.unsplash.com/photo-1559136555-9303baea8ebd?w=400'
                        ],
                        [
                            'icon' => 'check-circle',
                            'title' => 'Implementación Guiada',
                            'description' => 'Acompañamiento completo durante todo el proceso de transformación',
                            'image' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=400'
                        ]
                    ]
                ],
                
                // Estadísticas impresionantes
                'stats' => [
                    'title' => 'Resultados que Hablan por Sí Solos',
                    'items' => [
                        ['number' => '150', 'label' => 'Empresas Transformadas', 'suffix' => '+'],
                        ['number' => '300', 'label' => 'Crecimiento Promedio', 'suffix' => '%'],
                        ['number' => '15', 'label' => 'Años de Experiencia', 'suffix' => '+'],
                        ['number' => '98', 'label' => 'Satisfacción del Cliente', 'suffix' => '%']
                    ]
                ],
                
                // Testimoniales auténticos
                'testimonials' => [
                    'title' => 'Lo Que Dicen Nuestros Clientes',
                    'items' => [
                        [
                            'name' => 'María González',
                            'role' => 'CEO, TechStart',
                            'content' => 'En 6 meses triplicamos nuestros ingresos siguiendo su metodología. El ROI fue impresionante.',
                            'avatar' => 'https://images.unsplash.com/photo-1494790108755-2616b612b786?w=100',
                            'rating' => 5
                        ],
                        [
                            'name' => 'Carlos Ruiz',
                            'role' => 'Director, InnovateCorp',
                            'content' => 'Su enfoque estratégico nos ayudó a identificar oportunidades que no veíamos. Excelente trabajo.',
                            'avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100',
                            'rating' => 5
                        ]
                    ]
                ],
                
                // Planes de consultoría
                'pricing' => [
                    'title' => 'Planes de Consultoría',
                    'subtitle' => 'Elige el nivel de acompañamiento que necesitas',
                    'plans' => [
                        [
                            'name' => 'Diagnóstico',
                            'price' => 1500,
                            'period' => 'único',
                            'popular' => false,
                            'features' => [
                                'Análisis completo de la situación actual',
                                'Informe ejecutivo con recomendaciones',
                                'Sesión de presentación de resultados',
                                '30 días de consultas por email'
                            ],
                            'cta_text' => 'Comenzar Diagnóstico'
                        ],
                        [
                            'name' => 'Transformación',
                            'price' => 5000,
                            'period' => 'mes',
                            'popular' => true,
                            'features' => [
                                'Todo lo del plan Diagnóstico',
                                'Estrategia personalizada completa',
                                '8 horas de consultoría mensual',
                                'Implementación guiada paso a paso',
                                'Acceso directo vía WhatsApp'
                            ],
                            'cta_text' => 'Iniciar Transformación'
                        ],
                        [
                            'name' => 'Enterprise',
                            'price' => 12000,
                            'period' => 'mes',
                            'popular' => false,
                            'features' => [
                                'Todo lo del plan Transformación',
                                'Consultoría ilimitada',
                                'Equipo dedicado',
                                'Reportes ejecutivos semanales',
                                'Garantía de resultados'
                            ],
                            'cta_text' => 'Contactar Ventas'
                        ]
                    ]
                ],
                
                // Formulario especializado
                'form' => [
                    'title' => 'Agenda Tu Consulta Gratuita',
                    'subtitle' => 'Conversemos sobre los desafíos de tu empresa',
                    'cta_text' => 'Solicitar Consulta',
                    'fields' => [
                        ['name' => 'name', 'label' => 'Nombre Completo', 'type' => 'text', 'required' => true],
                        ['name' => 'email', 'label' => 'Email Corporativo', 'type' => 'email', 'required' => true],
                        ['name' => 'company', 'label' => 'Empresa', 'type' => 'text', 'required' => true],
                        ['name' => 'position', 'label' => 'Cargo', 'type' => 'text', 'required' => true],
                        ['name' => 'employees', 'label' => 'Número de Empleados', 'type' => 'select', 'required' => true, 'options' => [
                            '1-10', '11-50', '51-200', '201-500', '500+'
                        ]],
                        ['name' => 'challenge', 'label' => 'Principal Desafío de tu Empresa', 'type' => 'textarea', 'required' => true],
                        ['name' => 'budget', 'label' => 'Presupuesto Aproximado (USD)', 'type' => 'select', 'required' => false, 'options' => [
                            '1,000 - 5,000', '5,000 - 15,000', '15,000 - 50,000', '50,000+'
                        ]]
                    ],
                    'privacy_text' => 'Tus datos están seguros. No compartimos información con terceros.'
                ],
                
                // Social proof
                'social_proof' => [
                    'title' => 'Empresas que Confían en Nosotros',
                    'logos' => [
                        'https://logo.clearbit.com/microsoft.com',
                        'https://logo.clearbit.com/shopify.com', 
                        'https://logo.clearbit.com/stripe.com',
                        'https://logo.clearbit.com/airbnb.com'
                    ]
                ]
            ]
        ]);

        // Template para e-commerce
        Template::create([
            'name' => 'E-commerce Master',
            'description' => 'Template optimizado para tiendas online y productos digitales',
            'preview_image' => 'https://images.unsplash.com/photo-1472851294608-062f824d29cc?w=800',
            'is_premium' => true,
            'is_active' => true,
            'content' => [
                'layout' => 'ecommerce',
                
                'colors' => [
                    'primary' => '#e31c79',     // Rosa vibrante
                    'secondary' => '#2d3748',   // Gris oscuro
                    'accent' => '#ffd700',      // Dorado
                    'text' => '#1a202c',
                    'background' => '#ffffff'
                ],
                
                'fonts' => [
                    'heading' => 'Montserrat',
                    'body' => 'Open Sans'
                ],
                
                'animations' => [
                    'hero' => ['type' => 'bounceIn', 'duration' => 1.2],
                    'features' => ['type' => 'staggeredFadeIn', 'duration' => 0.6],
                    'parallax' => ['enabled' => true, 'speed' => 0.3]
                ],
                
                'hero' => [
                    'title' => 'Productos que Enamoran',
                    'subtitle' => 'Calidad Premium a Precios Increíbles',
                    'description' => 'Descubre nuestra colección exclusiva de productos cuidadosamente seleccionados para ti.',
                    'cta_text' => 'Explorar Catálogo',
                    'secondary_cta' => 'Ver Ofertas',
                    'background' => [
                        'type' => 'video',
                        'url' => 'https://player.vimeo.com/external/291648067.sd.mp4',
                        'overlay' => 'rgba(227, 28, 121, 0.4)'
                    ]
                ],
                
                // Productos destacados
                'products' => [
                    'title' => 'Productos Más Vendidos',
                    'subtitle' => 'Lo que nuestros clientes aman',
                    'items' => [
                        [
                            'name' => 'Producto Premium 1',
                            'price' => 299,
                            'original_price' => 399,
                            'images' => [
                                'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400',
                                'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=400'
                            ],
                            'description' => 'Descripción detallada del producto con características principales',
                            'rating' => 4.8,
                            'reviews' => 124
                        ],
                        [
                            'name' => 'Producto Premium 2', 
                            'price' => 199,
                            'images' => [
                                'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=400'
                            ],
                            'description' => 'Otro producto increíble con excelentes características',
                            'rating' => 4.9,
                            'reviews' => 89
                        ]
                    ]
                ],
                
                // Beneficios únicos
                'benefits' => [
                    'title' => '¿Por Qué Elegirnos?',
                    'items' => [
                        [
                            'icon' => 'truck',
                            'title' => 'Envío Gratis',
                            'description' => 'En compras superiores a $50'
                        ],
                        [
                            'icon' => 'shield',
                            'title' => 'Garantía Total',
                            'description' => '30 días para devoluciones'
                        ],
                        [
                            'icon' => 'headphones',
                            'title' => 'Soporte 24/7',
                            'description' => 'Atención cuando la necesites'
                        ]
                    ]
                ]
            ]
        ]);
    }
} 