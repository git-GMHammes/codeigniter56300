import { useEffect } from 'react';

/**
 * OFFCANVAS - Painel lateral deslizante
 * 
 * Props:
 * - isOpen: boolean (controla visibilidade)
 * - onClose: function (fecha o offcanvas)
 * - position: 'left' | 'right' | 'top' | 'bottom' (default: 'right')
 * - title: string (título do offcanvas)
 * - children: ReactNode (conteúdo)
 * - width: string (largura/altura customizada, ex: 'w-96', 'h-64')
 * - belowNav: boolean (se true, aparece abaixo do nav fixo)
 * - customBg: string (cor de fundo customizada, ex: 'bg-black')
 * - customTitleColor: string (cor do título, ex: 'text-gold')
 */

export default function Offcanvas({
    isOpen = false,
    onClose,
    position = 'right',
    title,
    children,
    width,
    belowNav = false,
    customBg = 'bg-white',
    customTitleColor = 'text-gray-800'
}) {

    // Bloqueia scroll do body quando offcanvas está aberto
    useEffect(() => {
        if (isOpen) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = 'unset';
        }

        return () => {
            document.body.style.overflow = 'unset';
        };
    }, [isOpen]);

    // Fecha ao pressionar ESC
    useEffect(() => {
        const handleEscape = (e) => {
            if (e.key === 'Escape' && isOpen) {
                onClose();
            }
        };

        document.addEventListener('keydown', handleEscape);
        return () => document.removeEventListener('keydown', handleEscape);
    }, [isOpen, onClose]);

    // Altura do nav (ajuste conforme necessário)
    const navHeight = '4rem'; // 64px

    // Define posição e animação
    const getPositionClasses = () => {
        if (belowNav) {
            // Quando abaixo do nav
            return {
                left: {
                    panel: `left-0 ${width || 'w-80'}`,
                    style: { top: navHeight, height: `calc(100vh - ${navHeight})` },
                    transform: isOpen ? 'translate-x-0' : '-translate-x-full'
                },
                right: {
                    panel: `right-0 ${width || 'w-80'}`,
                    style: { top: navHeight, height: `calc(100vh - ${navHeight})` },
                    transform: isOpen ? 'translate-x-0' : 'translate-x-full'
                }
            };
        }

        // Posicionamento normal (tela toda)
        return {
            left: {
                panel: `left-0 top-0 h-full ${width || 'w-80'}`,
                style: {},
                transform: isOpen ? 'translate-x-0' : '-translate-x-full'
            },
            right: {
                panel: `right-0 top-0 h-full ${width || 'w-80'}`,
                style: {},
                transform: isOpen ? 'translate-x-0' : 'translate-x-full'
            },
            top: {
                panel: `top-0 left-0 w-full ${width || 'h-64'}`,
                style: {},
                transform: isOpen ? 'translate-y-0' : '-translate-y-full'
            },
            bottom: {
                panel: `bottom-0 left-0 w-full ${width || 'h-64'}`,
                style: {},
                transform: isOpen ? 'translate-y-0' : 'translate-y-full'
            }
        };
    };

    const positions = getPositionClasses();
    const currentPosition = positions[position];

    // Z-index sempre acima do nav
    const zIndexPanel = 'z-[60]';
    const zIndexBackdrop = 'z-[55]';

    // Cor da borda do header baseada no fundo
    const borderColor = customBg === 'bg-black' ? 'border-gray-dark' : 'border-gray-200';

    // Cor do botão fechar baseada no fundo
    const closeButtonColor = customBg === 'bg-black'
        ? 'text-gold hover:text-orange hover:bg-gray-dark'
        : 'text-gray-400 hover:text-gray-600 hover:bg-gray-100';

    return (
        <>
            {/* Backdrop (fundo escuro) */}
            <div
                className={`fixed inset-0 bg-black/50 ${zIndexBackdrop} transition-opacity duration-300 ${isOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'
                    }`}
                style={belowNav ? { top: navHeight } : {}}
                onClick={onClose}
                aria-hidden="true"
            />

            {/* Painel Offcanvas */}
            <div
                className={`fixed ${currentPosition.panel} ${customBg} ${zIndexPanel} shadow-2xl transition-transform duration-300 ease-in-out ${currentPosition.transform}`}
                style={currentPosition.style}
                role="dialog"
                aria-modal="true"
                aria-labelledby="offcanvas-title"
            >
                {/* Header */}
                <div className={`flex items-center justify-between p-4 border-b ${borderColor}`}>
                    <h2 id="offcanvas-title" className={`text-xl font-semibold ${customTitleColor}`}>
                        {title}
                    </h2>

                    {/* Botão Fechar */}
                    <button
                        onClick={onClose}
                        className={`${closeButtonColor} transition-colors p-1 rounded-lg`}
                        aria-label="Fechar"
                    >
                        <svg
                            className="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth={2}
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>

                {/* Body (conteúdo) */}
                <div className="p-4 overflow-y-auto" style={{ maxHeight: 'calc(100% - 73px)' }}>
                    {children}
                </div>
            </div>
        </>
    );
}