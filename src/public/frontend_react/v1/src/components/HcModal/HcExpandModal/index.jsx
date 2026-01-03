// frontend_react/v1/src/components/HcModal/HcExpandModal/index.jsx

import { useState, useEffect } from 'react';

export default function HcExpandModal({
    children,
    button = true,
    title = "Modal",

    // Customização do Modal
    modalBg = "bg-gray-800",              // ← Fundo chumbo/dark
    modalTextColor = "text-gray-100",     // ← Texto claro
    borderColor = "border-gray-700",      // ← Borda mais escura
    noBorder = false,
    noPadding = false,
    contentPadding = "p-6",

    // Customização do Botão
    buttonText = "Abrir Modal",
    buttonBg = "bg-blue-600 hover:bg-blue-700",
    buttonBorder = "border-transparent",
    buttonIcon = null,
    noButtonBg = false,
    noButtonBorder = false,
    buttonPadding = "px-4 py-2",
    buttonRounded = "rounded",
    buttonTextColor = "text-white",

    onClose
}) {
    const [isOpen, setIsOpen] = useState(!button);

    useEffect(() => {
        if (!button) setIsOpen(true);
    }, [button]);

    const handleClose = () => {
        setIsOpen(false);
        onClose?.();
    };

    // Ícones pré-definidos
    const renderIcon = () => {
        if (!buttonIcon) return null;

        const icons = {
            bullet: <span className="w-2 h-2 bg-current rounded-full mr-2"></span>,
            arrow: <span className="mr-2">→</span>,
            plus: <span className="mr-2">+</span>,
            dot: <span className="mr-2">•</span>,
            check: <span className="mr-2">✓</span>,
        };

        return typeof buttonIcon === 'string' ? icons[buttonIcon] : buttonIcon;
    };

    // Classes do botão
    const buttonClasses = [
        !noButtonBg && buttonBg,
        !noButtonBorder && `border ${buttonBorder}`,
        buttonPadding,
        buttonRounded,
        buttonTextColor,
        "transition-all duration-200 flex items-center"
    ].filter(Boolean).join(' ');

    // Classes do modal
    const modalBorderClass = noBorder ? '' : `border ${borderColor}`;
    const modalPaddingClass = noPadding ? '' : contentPadding;

    if (!isOpen) {
        return button ? (
            <button onClick={() => setIsOpen(true)} className={buttonClasses}>
                {renderIcon()}
                {buttonText}
            </button>
        ) : null;
    }

    return (
        <div
            className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 animate-fadeIn"
            onClick={(e) => e.target === e.currentTarget && handleClose()}
        >
            <div
                className={`${modalBg} ${modalTextColor} rounded-lg ${modalBorderClass} shadow-2xl w-[90%] max-w-[800px] max-h-[90vh] flex flex-col animate-expandCenter`}
            >
                {/* Header */}
                <div className={`flex items-center justify-between ${noPadding ? 'p-3' : 'p-6'} border-b border-gray-700`}>
                    <h5 className="text-xl font-semibold text-gray-100">{title}</h5>
                    <button
                        onClick={handleClose}
                        className="text-gray-400 hover:text-gray-200 transition-colors p-1"
                        aria-label="Fechar"
                    >
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {/* Body */}
                <div className={`${modalPaddingClass} overflow-y-auto flex-1`}>
                    {children}
                </div>
            </div>
        </div>
    );
}