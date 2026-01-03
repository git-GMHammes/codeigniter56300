import { useState, useEffect } from 'react';
import logoImage from '/assets/imagens/Eu.png';

/**
 * HEADER - Componente de cabeçalho com vídeo de fundo
 * 
 * Responsabilidades:
 * - Exibe logo circular + nome da empresa
 * - Título animado com gradientes
 * - Vídeo de fundo com overlay
 * - Some ao rolar a página (controlado por scroll)
 */

export default function Header() {
    const [isVisible, setIsVisible] = useState(true);

    useEffect(() => {
        const handleScroll = () => {
            // Header some após rolar 100px
            setIsVisible(window.scrollY < 100);
        };

        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    return (
        <header
            className={`relative py-10 md:py-20 px-5 text-center shadow-header overflow-hidden min-h-100 md:min-h-150`}
        >
            {/* Vídeo de Fundo */}
            <video
                className="absolute top-0 left-0 w-full h-full object-cover z-0"
                autoPlay
                loop
                muted
                playsInline
            >
                <source src="assets/videos/fundo_video005.mp4" type="video/mp4" />
                Seu navegador não suporta vídeos HTML5.
            </video>

            {/* Overlay Gradiente Amarelo */}
            <div
                className="absolute top-0 left-0 w-full h-full z-10 pointer-events-none"
                style={{
                    background: 'linear-gradient(135deg, rgba(255, 215, 0, 0.7) 0%, rgba(255, 165, 0, 0.7) 100%)'
                }}
            />

            {/* Conteúdo do Header */}
            <div className="relative z-20">

                {/* Logo Container */}
                <div className="flex items-center justify-center gap-8 mb-10 flex-col md:flex-row md:gap-8">

                    {/* Logo Circular */}
                    <div className="w-[100px] h-[100px] md:w-[120px] md:h-[120px] rounded-full border-[5px] border-black bg-gold overflow-hidden flex-shrink-0 shadow-logo">
                        <img
                            src={logoImage}
                            alt="Logo Habilidade.com"
                            className="w-full h-full object-cover block"
                        />
                    </div>

                    {/* Nome da Empresa */}
                    <h2 className="text-[2.5rem] md:text-[4rem] font-black text-black tracking-[3px] m-0">
                        HABILIDADE {` `}
                        <span className="text-gray-dark font-bold italic inline-block skew-x-[-18deg]">
                            .Com
                        </span>
                    </h2>
                </div>

                {/* Título com Efeitos e Animações */}
                <div className="relative inline-block">
                    <h1
                        className="inline-block text-sm md:text-2xl my-8 md:my-7 font-black tracking-[2px] md:tracking-[8px] uppercase py-4 px-6 md:py-6 md:px-11 relative text-black rounded-2xl animate-gradient-shift max-w-[95%] md:max-w-none"
                        style={{
                            backgroundImage: 'linear-gradient(45deg, #000000, #1a1a1a, #000000, #1a1a1a)',
                            backgroundSize: '300% 300%',
                            boxShadow: '0 0 30px rgba(255, 215, 0, 0.5), 0 0 60px rgba(255, 165, 0, 0.3), inset 0 0 20px rgba(255, 215, 0, 0.1)',
                        }}
                    >
                        {/* Borda animada */}
                        <span
                            className="absolute -top-[3px] -left-[3px] -right-[3px] -bottom-[3px] rounded-2xl -z-10 animate-border-glow"
                            style={{
                                backgroundImage: 'linear-gradient(45deg, #FFD700, #FFA500, #FFD700, #FFA500)',
                                backgroundSize: '400% 400%',
                            }}
                        />

                        {/* Texto com gradiente animado */}
                        <span
                            className="animate-text-glow"
                            style={{
                                backgroundImage: 'linear-gradient(45deg, #FFD700, #FFA500, #FFD700, #FFA500)',
                                backgroundSize: '300% 300%',
                                backgroundClip: 'text',
                                WebkitBackgroundClip: 'text',
                                WebkitTextFillColor: 'transparent',
                            }}
                        >
                            Serviços de Desenvolvimento de Sistemas
                        </span>

                        {/* Linha decorativa inferior animada */}
                        <span
                            className="absolute -bottom-4 left-1/2 -translate-x-1/2 w-4/5 h-[3px] rounded-sm animate-line-glow"
                            style={{
                                backgroundImage: 'linear-gradient(90deg, transparent, #FFD700 20%, #FFA500 50%, #FFD700 80%, transparent)',
                            }}
                        />
                    </h1>
                </div>

                {/* Subtítulo */}
                <p className="text-sm md:text-2xl text-gray-dark font-medium mt-8 italic">
                    Soluções completas em tecnologia para o seu negócio
                </p>
            </div>
        </header>
    );
}