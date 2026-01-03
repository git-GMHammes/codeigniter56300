/**
 * SERVICECARD - Card de serviço com vídeo, título e lista
 * 
 * Props:
 * - title: string (título do card)
 * - items: array (lista de itens)
 * - videoSrc: string (caminho do vídeo)
 * - icon: ReactNode (ícone SVG)
 * - id: string (ID para âncora/navegação)
 */
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogTrigger } from "@/components/ui/dialog"
export default function ServiceCard({ title, items, videoSrc, icon, id }) {
    return (
        <div
            id={id}
            className="bg-gray-dark rounded-2xl overflow-hidden shadow-card transition-all duration-300 ease-in-out hover:-translate-y-2.5 hover:shadow-card-hover border-2 border-gray-border hover:border-gold"
        >
            {/* Vídeo do Card */}
            <video
                className="w-full h-[250px] object-cover bg-black"
                autoPlay
                loop
                muted
                playsInline
            >
                <source src={videoSrc} type="video/mp4" />
                Seu navegador não suporta vídeos HTML5.
            </video>

            {/* Conteúdo do Card */}
            <div className="p-8">
                {/* Título com Ícone */}
                <div className="flex items-center gap-3 mb-5 pb-2.5 border-b-4 border-gold">
                    {icon && (
                        <span className="text-gold">
                            {icon}
                        </span>
                    )}
                    <h2 className="text-3xl text-gold m-0">
                        {title}
                    </h2>
                </div>

                {/* Lista com Dialogs */}
                <ul className="list-none p-0 space-y-1">
                    {items.map((item, index) => (
                        <li
                            key={index}
                            className="relative text-gray-medium text-[0.95rem]"
                        >
                            <Dialog>
                                <DialogTrigger className="w-full text-left px-0 py-2 text-white hover:text-gold transition-colors flex items-start gap-2">
                                    <span className="text-gold font-bold min-w-[1rem]">▸</span>
                                    <span className="flex-1">{item.name}</span>
                                </DialogTrigger>
                                <DialogContent className="max-w-[800px]">
                                    <DialogHeader>
                                        <DialogTitle className="text-xl">{item.name}</DialogTitle>
                                    </DialogHeader>
                                    {/* AQUI: pr-4 APENAS no conteúdo com scroll */}
                                    <div className="overflow-y-auto max-h-[60vh] text-gray-medium space-y-4 text-justify pr-4">
                                        {item.description.map((paragraph, pIndex) => (
                                            <p key={pIndex} className="leading-relaxed">
                                                {paragraph}
                                            </p>
                                        ))}
                                    </div>
                                </DialogContent>
                            </Dialog>
                        </li>
                    ))}
                </ul>
            </div>

        </div>
    );
}