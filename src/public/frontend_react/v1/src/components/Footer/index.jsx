/**
 * FOOTER - Rodapé da aplicação
 * 
 * Responsabilidades:
 * - Exibe mensagem de call-to-action
 * - Botão de contato via WhatsApp
 * - Gradiente dourado/laranja
 */

export default function Footer() {
    return (
        <footer
            className="py-10 px-5 text-center mt-16"
            style={{
                background: 'linear-gradient(135deg, #FFD700 0%, #FFA500 100%)'
            }}
        >
            <p className="text-black text-lg font-medium">
                Transforme seu negócio com tecnologia de ponta
            </p>

            {/* Botão CTA */}
            <a
                href="https://wa.me/5521980558545?text=Olá!%20Vim%20pelo%20site%20e%20gostaria%20de%20conhecer%20mais%20sobre%20os%20serviços%20de%20desenvolvimento%20de%20sistemas."
                target="_blank"
                rel="noopener noreferrer"
                className="inline-block mt-5 py-4 px-10 bg-black text-gold no-underline rounded-full font-bold text-lg transition-all duration-300 border-2 border-gold hover:bg-gold hover:text-black hover:scale-105"
            >
                Entre em Contato
            </a>
        </footer>
    );
}