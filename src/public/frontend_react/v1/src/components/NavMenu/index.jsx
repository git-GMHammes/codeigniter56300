import React, { useState, useEffect } from 'react';
import Offcanvas from '../Offcanvas';
import logoImage from '/assets/imagens/Eu.png';

/**
 * NAVMENU - Menu de navegação fixo
 * 
 * Responsabilidades:
 * - Menu fixo no topo da página (fundo preto)
 * - Ícone hambúrguer (esquerda) para abrir menu lateral
 * - Painel central com informações deslizantes
 * - Ícone de casinha (direita) para rolar aos serviços
 * - Offcanvas com links âncora para cada card
 */

export default function NavMenu() {
    const [isFixed, setIsFixed] = useState(false);
    const [isOffcanvasOpen, setIsOffcanvasOpen] = useState(false);

    useEffect(() => {
        const handleScroll = () => {
            // Menu fica fixo após rolar 100px
            setIsFixed(window.scrollY > 100);
        };

        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    const scrollToServices = () => {
        const servicesSection = document.getElementById('services-section');
        if (servicesSection) {
            servicesSection.scrollIntoView({ behavior: 'smooth' });
        }
    };

    const handleMenuClick = (id) => {
        // Fecha o offcanvas
        setIsOffcanvasOpen(false);

        // Aguarda animação de fechamento e faz scroll
        setTimeout(() => {
            const element = document.getElementById(id);
            if (element) {
                element.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }, 300);
    };

    // Menu items com ícones e IDs correspondentes aos cards
    const menuItems = [
        {
            id: "desenvolvimento-software",
            title: "Desenvolvimento de Software",
            icon: (
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                </svg>
            )
        },
        {
            id: "analise-consultoria",
            title: "Análise e Consultoria",
            icon: (
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            )
        },
        {
            id: "integracao-apis",
            title: "Integração e APIs",
            icon: (
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            )
        },
        {
            id: "banco-dados",
            title: "Banco de Dados",
            icon: (
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                </svg>
            )
        },
        {
            id: "manutencao-suporte",
            title: "Manutenção e Suporte",
            icon: (
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            )
        },
        {
            id: "devops-infraestrutura",
            title: "DevOps e Infraestrutura",
            icon: (
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                </svg>
            )
        },
        {
            id: "seguranca",
            title: "Segurança",
            icon: (
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            )
        },
        {
            id: "outros-servicos",
            title: "Outros Serviços",
            icon: (
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            )
        }
    ];

    return (
        <>
            <style>
                {`
                    @keyframes marquee {
                        0% {
                            transform: translateX(100%);
                        }
                        100% {
                            transform: translateX(-100%);
                        }
                    }
                    
                    .animate-marquee {
                        animation: marquee 100s linear infinite;
                        animation-delay: -40s;  /* ← ADICIONE ESTA LINHA */
                    }
                    
                    .animate-marquee:hover {
                        animation-play-state: paused;
                    }
                `}
            </style>

            <nav
                className={`w-full bg-black transition-all duration-500 z-50 ${isFixed ? 'fixed top-0 shadow-lg' : 'relative'
                    }`}
            >
                <div className="max-w-[1400px] mx-auto px-5 py-4 flex justify-between items-center gap-4">

                    {/* Ícone Hambúrguer (Esquerda) */}
                    <button
                        onClick={() => setIsOffcanvasOpen(true)}
                        className="text-gold hover:text-orange transition-colors duration-300 flex-shrink-0"
                        aria-label="Abrir menu"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            className="h-8 w-8"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth={2}
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                        </svg>
                    </button>

                    {/* Painel Central com Informações Deslizantes */}
                    {isFixed && (
                        <div className="flex-1 overflow-hidden relative h-10 flex items-center">
                            <div className="flex items-center gap-8 whitespace-nowrap animate-marquee">

                                {/* ✅ LOOP PROFISSIONAL - Repete 3 vezes */}
                                {[...Array(3)].map((_, index) => (
                                    <React.Fragment key={index}>

                                        <img
                                            src={logoImage}
                                            alt="Profile"
                                            className="h-10 w-10 rounded-full border-2 border-gold object-cover"
                                        />

                                        <span className="text-gold font-bold text-lg">Habilidade .Com</span>
                                        <span className="text-white">•</span>

                                        <span className="text-gold font-semibold">Serviço de Desenvolvimento de Sistemas</span>
                                        <span className="text-white">•</span>

                                        <span className="text-gold">Soluções completas em Tecnologia da Informação</span>
                                        <span className="text-white">•</span>

                                        <a
                                            href="https://wa.me/5521980558545?text=Olá!%20Vim%20pelo%20site%20e%20gostaria%20de%20conhecer%20mais%20sobre%20os%20serviços%20de%20desenvolvimento%20de%20sistemas."
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            className="flex items-center gap-2 text-green-500 hover:text-green-400 transition-colors pointer-events-auto"
                                            onClick={(e) => e.stopPropagation()}
                                        >
                                            <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                            </svg>
                                            <span className="font-medium">WhatsApp</span>
                                        </a>

                                        <span className="text-white">•</span>

                                    </React.Fragment>
                                ))}

                            </div>
                        </div>
                    )}

                    {/* Ícone Casinha (Direita) */}
                    <button
                        onClick={scrollToServices}
                        className="text-gold hover:text-orange transition-colors duration-300 flex-shrink-0"
                        aria-label="Ir para serviços"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            className="h-8 w-8"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth={2}
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                            />
                        </svg>
                    </button>
                </div>
            </nav>

            {/* Offcanvas - Aparece abaixo do Nav */}
            <Offcanvas
                isOpen={isOffcanvasOpen}
                onClose={() => setIsOffcanvasOpen(false)}
                position="left"
                title="Menu de Serviços"
                belowNav={true}
                customBg="bg-black"
                customTitleColor="text-gold"
            >
                <div className="space-y-2">
                    {menuItems.map((item, index) => (
                        <button
                            key={index}
                            onClick={() => handleMenuClick(item.id)}
                            className="w-full flex items-center gap-3 p-3 text-gold hover:bg-gray-dark rounded-lg transition-colors group text-left"
                        >
                            <span className="text-gold group-hover:text-orange transition-colors">
                                {item.icon}
                            </span>
                            <span className="font-medium">{item.title}</span>
                        </button>
                    ))}
                </div>
            </Offcanvas>
        </>
    );
}